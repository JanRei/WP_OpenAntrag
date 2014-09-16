<!--
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
-->
<!doctype html>
<html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <style>
        * {
    text-align: center;
            margin: 0;
            padding: 0;
            font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
        }
        p {
    margin-top: 1em;
            font-size: 18px;
        }
    </style>
<body>
<p><?php echo $message; ?></p>
</body>
</html>
