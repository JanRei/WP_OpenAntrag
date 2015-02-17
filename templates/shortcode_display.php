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
?>
<h2><?php echo __('Antr&auml;ge'); echo ' '; echo esc_html($displayname); ?></h2>
<?php foreach($proposals as $prop): ?>
    <div style="margin-bottom: 2em;">
    <a href="<?php echo $prop->FullUrl; ?>" target="_blank"><?php echo esc_html($prop->Title); ?></a>
    <br/>
    Aktueller Status:
    <span <?php echo empty($prop->color)?'':'style="background-color:'.$prop->color.'"'; ?>><?php echo $prop->status; ?></span> <br/>
<?php if (!empty($prop->nextstatus)): ?>
    <span>M&ouml;gliche n&auml;chste Schritte:</span><ul>
    <?php foreach($prop->nextstatus as $i => $status): ?>
        <li>
            <span <?php echo empty($prop->nextcolor[$i])?'':'style="background-color:'.$prop->nextcolor[$i].'"'; ?>>
            <?php echo $status; ?>
            <span/>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif;
    if (!$compact):
        echo $prop->TextHtml;
    endif; ?>
    </div>
<?php endforeach ?>

