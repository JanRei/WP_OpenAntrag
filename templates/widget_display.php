<?php
/**
 * Created by PhpStorm.
 * User: jochen
 * Date: 07.09.14
 * Time: 14:39
 */

echo $before_widget;
echo $before_title;
echo __('Antr&auml;ge');
?>
<br/>
<?php
echo esc_html($rep->Name2);
echo $after_title;
?>
<ul>
<?php foreach($displaydata as $data): ?>
    <li style="margin: 3px; border-radius: 2px 2px 2px 2px; padding: 5px; border-style: none ridge inset none; border-width: 1px; border-color: grey; <?php echo empty($data['color'])?'':'background-color:'.$data['color']; ?> ">
    <a href="<?php echo $data['fullurl']; ?>" target="_blank"><?php echo $data['title']; ?></a>
    <br/>
    <span><?php echo $data['status']; ?></span>
    </li>
<?php endforeach ?>
</ul>
<?php
echo $after_widget;
?>