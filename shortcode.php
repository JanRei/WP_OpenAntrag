<?php
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

add_shortcode('wp_openantrag', 'wp_openantrag_display_shortcode');

function wp_openantrag_display_shortcode($atts, $content = null) {
    foreach ($atts as $attribute => $value) {
        if (is_int($attribute)) {
            $atts[strtolower($value)] = true;
            unset($atts[$attribute]);
        }
    }
    extract(shortcode_atts(array(
        'parlament' => null,
        'count' => 5,
        'compact' => false
    ), $atts));

    if (empty($parlament)) {
        return esc_html__( 'Bitte geben Sie ein Parlament an.' , 'wp_openantrag');;
    }

    $transient_name = 'openantrag_' . md5(serialize($atts));

    if (false === ($shortcode_output = get_transient($transient_name))) {
        $steps = \WP_OpenAntrag\Plugin::openantrag_parliament_getprocesssteps($parlament);
        $displayname = \WP_OpenAntrag\Plugin::openantrag_parliament_getdisplayname($parlament);

        $displayerror = false;
        $displayerrormessage = '';
        $proposals = array();

        try {
            $proposals = \WP_OpenAntrag\Plugin::openantrag_parliament_getproposals($parlament, $count);
        } catch (\Exception $e) {
            $displayerror = true;
            $displayerrormessage = $e->getMessage();
        }

        foreach($proposals as $prop) {
            $prop->status = '';
            $prop->color = '';
            $statusid = $prop->ID_CurrentProposalStep;
            foreach($prop->ProposalSteps as $step) {
                if ($step->Id == $statusid) {
                    $prop->status = $step->ProcessStep->ShortCaption;
                    $prop->color = $step->ProcessStep->Color;
                    $prop->nextstatus = array();
                    $prop->nextcolor = array();
                    $nextsteps = explode(',', $step->ProcessStep->ID_NextSteps);
                    foreach($steps as $_step) {
                        if (in_array($_step->ID, $nextsteps)) {
                            $prop->nextstatus[] = $_step->ShortCaption;
                            $prop->nextcolor[] = $_step->Color;
                        }
                    }
                    break;
                }
            }
        }

        ob_start();
        include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'shortcode_display.php';
        $shortcode_output = ob_get_clean();

        set_transient($transient_name, $shortcode_output, WP_OPENANTRAG__CACHE_TIME);
    }

    return $shortcode_output;
}