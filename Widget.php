<?php
/**
 * Created by PhpStorm.
 * User: jochenschaefer
 * Date: 15.08.14
 * Time: 18:08
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
        if ( $instance['id'] ) {
            $id = $instance['id'];
        } else {
            $id = '';
        }
        if ( $instance['count'] ) {
            $count = $instance['count'];
        } else {
            $count = 5;
        }
        include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'widget_form.php';
    }

    public function update( $new_instance, $old_instance ) {
        $instance['id'] = strip_tags( $new_instance['id'] );
        if (empty($instance['id'])) {
            // TODO throw new \Exception(__('Parlament darf nicht leer sein', 'wp_openantrag'));
        }
        $instance['count'] = strip_tags( $new_instance['count']);
        if (!is_numeric($instance['count'])) {
            $instance['count'] = 5;
        }
        return $instance;
    }

    public function widget( $args, $instance ) {
        extract($args);
        echo $before_widget;

        $url = sprintf('%s/representation/GetByKey/%s', Plugin::API_HOST, $instance['id']);
        $rep = json_decode(wp_remote_retrieve_body(wp_remote_get($url)));
        $displayname = $rep->Name2;

        $url = sprintf('%s/proposal/%s/GetTop/%d', Plugin::API_HOST, $instance['id'], $instance['count']);
        $proposals = json_decode(wp_remote_retrieve_body(wp_remote_get($url)));
        $displaydata = array();
        foreach($proposals as $prop) {
            $data = array();
            $data['status'] = '';
            $data['color'] = '';
            $statusid = $prop->ID_CurrentProposalStep;
            foreach($prop->ProposalSteps as $step) {
                if ($step->Id == $statusid) {
                    $data['status'] = $step->ProcessStep->Caption;
                    $data['color'] = $step->ProcessStep->Color;
                    break;
                }
            }
            $data['fullurl'] = $prop->FullUrl;
            $data['title'] = $prop->Title;
            $displaydata[] = $data;
        }
        include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'widget_display.php';
    }
}

add_action( 'widgets_init', function() {
    register_widget('\WP_OpenAntrag\Widget');
});
