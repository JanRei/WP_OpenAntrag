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
            array( 'description' => __( 'Zeige Anträge aus dem jeweiligen OpenAntrag-Parlament an.' , 'wp_openantrag') )
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
            throw new \Exception(__('Parlament darf nicht leer sein', 'wp_openantrag'));
        }
        $instance['count'] = strip_tags( $new_instance['id']);
        if (!is_numeric($instance['count'])) {
            $instance['count'] = 5;
        }
        return $instance;
    }

    public function widget( $args, $instance ) {
        echo '<b>NRW</b>';
    }
}

add_action( 'widgets_init', function() {
    register_widget('\WP_OpenAntrag\Widget');
});
