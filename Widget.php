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
?>
    <p>
        <label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php esc_html_e( 'Parlament:' , 'wp_openantrag'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" value="<?php echo esc_attr( $id ); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php esc_html_e( 'Anzahl Antr&auml;ge:' , 'wp_openantrag'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
    </p>
<?php
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
        echo $before_title;
        echo __('Antr&auml;ge');
        echo '<br/>';
        echo esc_html($rep->Name2);
        echo $after_title;

        echo '<ul>';
        $url = sprintf('%s/proposal/%s/GetTop/%d', Plugin::API_HOST, $instance['id'], $instance['count']);
        $proposals = json_decode(wp_remote_retrieve_body(wp_remote_get($url)));
        foreach($proposals as $prop) {
            $status = '';
            $color = '';
            $statusid = $prop->ID_CurrentProposalStep;
            foreach($prop->ProposalSteps as $step) {
                if ($step->Id == $statusid) {
                    $status = $step->ProcessStep->Caption;
                    $color = $step->ProcessStep->Color;
                }
            }
            echo '<li style="margin: 3px; border-radius: 2px 2px 2px 2px; padding: 5px; border-style: none ridge inset none; border-width: 1px; border-color: grey; ';
            if (!empty($color)) {
                echo 'background-color: '.$color.';';
            }
            echo '">';
            echo '<a href="' . $prop->FullUrl . '" target="_blank">' . $prop->Title . '</a>';
            echo '<br/>';
            echo '<span>'. $status . '</span>';
            echo '</li>';
        }
        echo '</ul>';
        echo $after_widget;
    }
}

add_action( 'widgets_init', function() {
    register_widget('\WP_OpenAntrag\Widget');
});
