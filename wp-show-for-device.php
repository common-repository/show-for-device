<?php
/*
Plugin Name: Hide and show for devices 
Plugin URI: https://danielesparza.studio/show-for-device/
Description: Hide and show for devices es un plugin para WordPress basado en la clase PHP Mobile Detect (http://mobiledetect.net/). Sirve para mostrar u ocultar elementos dentro del contenido de nuestras páginas, dependiendo el tipo de dispositivo (Escritorio, Tablet o Móvil) a través del uso de Shortcodes.
Version: 2.0
Author: Daniel Esparza
Author URI: https://danielesparza.studio/
License: GPL v3

Hide and show for devices
©2020 Daniel Esparza, inspirado por #openliveit #dannydshore | Consultoría en servicios y soluciones de entorno web - https://danielesparza.studio/

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(function_exists('admin_menu_desparza')) { 
    //menu exist
} else {
	add_action('admin_menu', 'admin_menu_desparza');
	function admin_menu_desparza(){
		add_menu_page('DE Plugins', 'DE Plugins', 'manage_options', 'desparza-menu', 'wp_desparza_function', 'dashicons-editor-code', 90 );
		add_submenu_page('desparza-menu', 'Sobre Daniel Esparza', 'Sobre Daniel Esparza', 'manage_options', 'desparza-menu' );
	
    function wp_desparza_function(){  	
	?>
		<div class="wrap">
            <h2>Daniel Esparza</h2>
            <p>Consultoría en servicios y soluciones de entorno web.<br>¿Qué tipo de servicio o solución necesita tu negocio?</p>
            <h4>Contact info:</h4>
            <p>
                Sitio web: <a href="https://danielesparza.studio/" target="_blank">https://danielesparza.studio/</a><br>
                Contacto: <a href="mailto:hi@danielesparza.studio" target="_blank">hi@danielesparza.studio</a><br>
                Messenger: <a href="https://www.messenger.com/t/danielesparza.studio" target="_blank">enviar mensaje</a><br>
                Información acerca del plugin: <a href="https://danielesparza.studio/show-for-device/" target="_blank">sitio web del plugin</a><br>
                Daniel Esparza | Consultoría en servicios y soluciones de entorno web.<br>
                ©2020 Daniel Esparza, inspirado por #openliveit #dannydshore
            </p>
		</div>
	<?php }
        
    }	
    
    add_action( 'admin_enqueue_scripts', 'wpba_register_adminstyle' );
    function wpba_register_adminstyle() {
        wp_register_style( 'wpba_register_adminstyle_css', plugin_dir_url( __FILE__ ) . 'css/wpsd_style_admin.css', array(), '1.0' );
        wp_enqueue_style( 'wpba_register_adminstyle_css' );
    }
    
}


if ( ! function_exists( 'wp_hide_and_shows_add' ) ) {

add_action( 'admin_menu', 'wp_hide_and_shows_add' );
function wp_hide_and_shows_add() {
    add_submenu_page('desparza-menu', 'Hide and show', 'Hide and show', 'manage_options', 'wp-hide-and-shows-settings', 'hsfd_how_to_use' );
}

function hsfd_how_to_use(){ 
    
    echo '
    <div class="wrap">
        <h2>Hide and show for devices, ¿Como usar los shortcodes?</h2>
        <h4>Shortcodes mostrar en algunos dispositivos:</h4>
        <ul>
            <li>[hsfd-only-device]Coloque el contenido aquí que desee mostrar solo en computadoras[/hsfd-only-device]</li>
            <li>[hsfd-only-tablet]Coloque el contenido aquí que desee mostrar solo en Tablets[/hsfd-only-tablet]</li>
            <li>[hsfd-only-phone]Coloque el contenido aquí que desee mostrar solo en teléfonos[/hsfd-only-phone]</li>
        </ul>
        <h4>Shortcodes mostrar en algunos dispositivos específicos:</h4>
        <ul>
            <li>[hsfd-only-ipad]Coloque el contenido aquí que desee mostrar en teléfonos iPad[/hsfd-only-ipad]</li>
            <li>[hsfd-only-iphone]Coloque el contenido aquí que desee mostrar en teléfonos iPhone[/hsfd-only-iphone]</li>
            <li>[hsfd-only-android]Coloque el contenido aquí que desee mostrar en  dispositivos android[/hsfd-only-android]</li>
        </ul>
        <h4>Shortcodes ocultar en algunos dispositivos:</h4>
        <ul>
            <li>[hsfd-not-device]Coloque el contenido aquí que desee ocultar solo en computadoras[/hsfd-not-device]</li>
            <li>[hsfd-not-tablet]Coloque el contenido aquí que desee ocultar solo en Tablets[/hsfd-not-tablet]</li>
            <li>[hsfd-not-phone]Coloque el contenido aquí que desee ocultar solo en teléfonos[/hsfd-not-phone]</li>
        </ul>
    </div>';
    
}

//PHP Mobile Detect class (http://mobiledetect.net/)
define( 'HSFD__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( HSFD__PLUGIN_DIR . 'mobile-detect.php' );

if (class_exists('Mobile_Detect')) {
    $detect = new Mobile_Detect();
}

//Sortcode shows only content for phones
add_shortcode( 'hsfd-only-phone', 'hsfd_phone' );
function hsfd_phone( $atts, $content = null ) {
	global $detect;
	if( $detect->isMobile() && ! $detect->isTablet() ) return do_shortcode($content);
}

//Sortcode shows only content for tablets
add_shortcode( 'hsfd-only-tablet', 'hsfd_tablet' );
function hsfd_tablet( $atts, $content = null ) {
	global $detect;
	if( $detect->isTablet() ) return do_shortcode($content);
}


//Sortcode shows only content for desktops
add_shortcode( 'hsfd-only-device', 'hsfd_notdevice' );
function hsfd_notdevice( $atts, $content = null ) {
	global $detect;
	if( ! $detect->isMobile() && ! $detect->isTablet() ) return do_shortcode($content);
}


//Sortcode hide only content for phones
add_shortcode( 'hsfd-not-phone', 'hsfd_notphone' );
function hsfd_notphone( $atts, $content = null ) {
	global $detect;
	if( ! $detect->isMobile() || $detect->isTablet() ) return do_shortcode($content);
}

//Sortcode hide only content for tablets
add_shortcode( 'hsfd-not-tablet', 'hsfd_nottab' );
function hsfd_nottab( $atts, $content = null ) {
	global $detect;
	if( ! $detect->isTablet() ) return do_shortcode($content);
}

//Sortcode hide only content for desktops
add_shortcode( 'hsfd-not-device', 'hsfd_device' );
function hsfd_device( $atts, $content = null ) {
	global $detect;
	if( $detect->isMobile() || $detect->isTablet() ) return do_shortcode($content);
}


/*** news ***/
/*
//Sortcode shows only content for chrome (mobile)
add_shortcode( 'hsfd-only-chrome', 'hsfd_chrome' );
function hsfd_chrome( $atts, $content = null ) {
	global $detect;
	if( $detect->isChrome() ) return do_shortcode($content);
}

//Sortcode shows only content for safari (mobile)
add_shortcode( 'hsfd-only-safari', 'hsfd_safari' );
function hsfd_safari( $atts, $content = null ) {
	global $detect;
	if( $detect->isSafari() ) return do_shortcode($content);
}

//Sortcode shows only content for firefox (mobile)
add_shortcode( 'hsfd-only-firefox', 'hsfd_firefox' );
function hsfd_firefox( $atts, $content = null ) {
	global $detect;
	if( $detect->isFirefox() ) return do_shortcode($content);
}
*/

add_shortcode( 'hsfd-only-ipad', 'hsfd_ipad' );
function hsfd_ipad( $atts, $content = null ) {
	global $detect;
	if( $detect->isiPad() ) return do_shortcode($content);
}

//Sortcode shows only content for iphone
add_shortcode( 'hsfd-only-iphone', 'hsfd_iphone' );
function hsfd_iphone( $atts, $content = null ) {
	global $detect;
	if( $detect->isiPhone() ) return do_shortcode($content);
}

//Sortcode shows only content for andriod
add_shortcode( 'hsfd-only-android', 'hsfd_android' );
function hsfd_android( $atts, $content = null ) {
	global $detect;
	if( $detect->isAndroidOS() ) return do_shortcode($content);
}
}