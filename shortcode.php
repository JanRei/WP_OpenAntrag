<?php
/*
Plugin Name: WP_OpenAntrag
Plugin URI: http://github.com
Description: Display OpenAntrag
Version: 0.1
Author: Jochen Sch&auml;fer
Author URI: http://www.github.com/josch1710
License: GPLv2
Text Domain: wp_openantrag
*/

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
var_dump('short');
add_shortcode('wp_openantrag', 'wp_openantrag_display_shortcode');

function wp_openantrag_display_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
        'parlament' => null,
        'count' => 5
    ), $atts));
    if (empty($parlament)) {
        \WP_OpenAntrag\Plugin::bail_on_activation('Bitte geben Sie ein Parlament an.', false);
        exit;
    }

    $url = sprintf('%s/representation/GetByKey/%s', \WP_OpenAntrag\Plugin::API_HOST, $parlament);
    $rep = json_decode(wp_remote_retrieve_body(wp_remote_get($url)));
    $displayname = $rep->Name2;

    $url = sprintf('%s/proposal/%s/GetTop/%d', \WP_OpenAntrag\Plugin::API_HOST, $parlament, $count);
    $proposals = (array)json_decode(wp_remote_retrieve_body(wp_remote_get($url)));
    foreach($proposals as $prop) {
        $prop->status = '';
        $prop->color = '';
        $statusid = $prop->ID_CurrentProposalStep;
        foreach($prop->ProposalSteps as $step) {
            if ($step->Id == $statusid) {
                $prop->status = $step->ProcessStep->Caption;
                $prop->color = $step->ProcessStep->Color;
                break;
            }
        }
    }
    ob_start();
    include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'shortcode_display.php';
    return ob_get_clean();
}