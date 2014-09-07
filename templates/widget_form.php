<!--
/**
 * Created by PhpStorm.
 * User: jochen
 * Date: 07.09.14
 * Time: 13:27
 */
-->
<p>
    <label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php esc_html_e( 'Parlament:' , 'wp_openantrag'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" value="<?php echo esc_attr( $id ); ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php esc_html_e( 'Anzahl Antr&auml;ge:' , 'wp_openantrag'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
</p>
