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

namespace WP_OpenAntrag;


class Widget extends \WP_Widget {
    public function __construct() {
        load_plugin_textdomain( 'wp_openantrag' );

        $this->id_base = 'wp_openantrag';
        parent::__construct(
            'wp_openantrag_widget',
            __( 'WP OpenAntrag Widget' , 'wp_openantrag'),
            array( 'description' => __( 'Zeige Antr&auml;ge aus dem jeweiligen OpenAntrag-Parlament an.' , 'wp_openantrag') )
        );
    }

    public function form( $instance ) {
        if ( !empty($instance['parliament']) ) {
            $parliament = $instance['parliament'];
        } else {
            $parliament = '';
        }
        if ( !empty($instance['count']) ) {
            $count = $instance['count'];
        } else {
            $count = 5;
        }
        include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'widget_form.php';
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        if (!empty($new_instance['parliament'])) {
            $instance['parliament'] = strip_tags( $new_instance['parliament'] );
        } else {
            $instance['parliament'] = '';
        }
        if (!empty($new_instance['count']) && intval($new_instance['count']) > 0) {
            $instance['count'] = intval($new_instance['count']);
        } else {
            $instance['count'] = 5;
        }
        return $instance;
    }

    public function widget( $args, $instance ) {
        extract($args);

        if (!empty($instance['parliament'])) {
            $parliament = $instance['parliament'];
        } else {
            return;
        }
        if (!empty($instance['count']) && intval($instance['count']) > 0) {
            $count = intval($instance['count']);
        } else {
            $count = 5;
        }

        $transient_name = 'openantrag_' . md5(serialize($instance));

        if (false === ($widget_output = get_transient($transient_name))) {
            $displayname = \WP_OpenAntrag\Plugin::openantrag_parliament_getdisplayname($parliament);

            $displayerror = false;
            $displayerrormessage = '';
            $proposals = array();

            try {
                $proposals = \WP_OpenAntrag\Plugin::openantrag_parliament_getproposals($parliament, $count);
            } catch (\Exception $e) {
                $displayerror = true;
                $displayerrormessage = $e->getMessage();
            }

            foreach($proposals as $prop) {
                $data = array();
                $data['status'] = '';
                $data['color'] = '';
                $statusid = $prop->ID_CurrentProposalStep;
                foreach($prop->ProposalSteps as $step) {
                    if ($step->Id == $statusid) {
                        $data['status'] = $step->ProcessStep->ShortCaption;
                        $data['color'] = $step->ProcessStep->Color;
                        break;
                    }
                }
                $data['fullurl'] = $prop->FullUrl;
                $data['title'] = $prop->Title;
                $displaydata[] = $data;
            }

            ob_start();
            include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'widget_display.php';
            $widget_output = ob_get_clean();

            set_transient($transient_name, $widget_output, WP_OPENANTRAG__CACHE_TIME);
        }
        echo $widget_output;
    }
}

add_action( 'widgets_init', function() {
    register_widget('\WP_OpenAntrag\Widget');
});
