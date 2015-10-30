<?php
	/*
	Plugin Name:  Overlay It Plugin
	Plugin URI: https://github.com/joshhannan/overlay-it-wordpress
	Description:  Plugin that shows an Overlay Popup 
	Version: 1.0.0
	Author: <a href="http://github.com/joshhannan">Josh Hannan</a>
	Author URI: http://www.inverseparadox.com
	*/

	$ip_overlay = new ip_overlay_it();
	add_action( 'wp_footer', [ $ip_overlay, 'ip_overlay_check' ] );
	add_action('wp_enqueue_scripts', [ $ip_overlay, 'ip_overlay_it_scripts_styles'] );

	class ip_overlay_it {

		function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		function admin_menu () {
			add_options_page( 'Overlay It Settings','Overlay It Settings','manage_options','ip-overlay-it-plugin', array( $this, 'ip_overlay_plugin_page' ) );
		}

		function register_overlay_it_settings() {
			register_setting( 'overlay_it_settings', 'overlay_it_display' );
			register_setting( 'overlay_it_settings', 'overlay_it_content_editor' );
		}

		function ip_overlay_plugin_page() {

			// Set Defaults
			$overlay_it_settings_defaults = array(
				'overlay_it_display' => 'false',
				'overlay_it_content_editor' => '',
				'overlay_it_locations' => ''
			);
			$options = wp_parse_args( get_option( 'overlay_it_settings' ), $overlay_it_settings_defaults );
			update_option( 'overlay_it_settings', $options );

			$overlay_it_settings = get_option('overlay_it_settings');
			if( $_POST['update_settings'] == 'Y' ) {
				$overlay_it_settings['overlay_it_display'] = $_POST['overlay_it_display'];
				$overlay_it_settings['overlay_it_locations'] = $_POST['overlay_it_locations'];
				$overlay_it_settings['overlay_it_content_editor'] = $_POST['overlay_it_content_editor'];
				update_option( "overlay_it_settings", $overlay_it_settings );
			}
?>
			<script type="text/javascript">
			/*
			jQuery(function($) {
				$('#overlay_it_settings_form').submit(function() {
					$('#overlay_it_locations input[type=checkbox]').each(function(){
						console.log( $(this).val() );
					});
					return false;
				});
			});
			*/
			</script>
			<div class="wrap">
				<h2>Overlay It Settings</h2>
				<form id="overlay_it_settings_form" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="update_settings" value="Y" />
					<table class="form-table">
						<tr>
							<th scope="row">Display Overlay?</th>
							<td>
								<select id="overlay_it_display" name="overlay_it_display">
<?php
			$display = array(
				'false'  => 'No',
				'true' => 'Yes'
			);
			foreach( $display as $toggle => $value ) :
				$selected = '';
				$type_set = $overlay_it_settings['overlay_it_display'];
				if( $toggle == $type_set ) :
					$selected = 'selected="selected"';
				endif;
				echo '<option value="' . esc_attr( $toggle ) . '" ' . $selected . '>' . $value . '</option>';
			endforeach;
?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">Where should the overlay Display?</th>
							<td>
<?php
			$where_to_display = array(
				'posts'  => 'Posts',
				'pages' => 'Pages',
				'home' => 'On Home Page'
			);
			foreach( $where_to_display as $checkbox => $value ) :
				$checked = '';
				$type_set = $overlay_it_settings['overlay_it_locations'];
				if( $overlay_it_settings['overlay_it_locations'][$checkbox] == 'on' ) :
					$checked = 'checked="checked"';
				endif;
				echo '<div id="overlay_it_location_' . $checkbox . '" style="display: inline-block; margin: 0 10px 0 0;"><input name="overlay_it_locations[' . $checkbox . ']" type="checkbox" ' . $checked . ' /><label>' . $value . '</label></div>';
			endforeach;
?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
<?php
			$content = $overlay_it_settings['overlay_it_content_editor'];
			$editor_id = 'overlay_it_content_editor';
			wp_editor( stripslashes( $content ), $editor_id );
?>
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
			</div><!--/wrap-->
<?php
		}

		public function ip_overlay_check() {
			if( get_option('overlay_it_settings') ) :

				$overlay_it_settings = get_option( 'overlay_it_settings' );
				if( $overlay_it_settings['overlay_it_display'] == 'true' ) :
					if( $overlay_it_settings['overlay_it_locations']['home']  == 'on' ) :
						if( is_front_page() ) :
							echo '<div class="overlay_it_container" style="display: none;"><div class="overlay_it_box content">' . $overlay_it_settings['overlay_it_content_editor'] . '<div class="close"><span>Close</span></div></div></div>';
						endif;
					endif;
					if( $overlay_it_settings['overlay_it_locations']['pages']  == 'on' ) :
						if( is_page() ) :
							echo '<div class="overlay_it_container" style="display: none;"><div class="overlay_it_box content">' . $overlay_it_settings['overlay_it_content_editor'] . '<div class="close"><span>Close</span></div></div></div>';
						endif;
					endif;
					if( $overlay_it_settings['overlay_it_locations']['posts']  == 'on' ) :
						if( is_single() ) :
							echo '<div class="overlay_it_container" style="display: none;"><div class="overlay_it_box content">' . $overlay_it_settings['overlay_it_content_editor'] . '<div class="close"><span>Close</span></div></div></div>';
						endif;
					endif;
				endif;
			endif;
		}

		public function ip_overlay_it_scripts_styles() {

			// register scripts
			wp_register_script( 'ip_overlay_plugin_script', get_bloginfo('url') . '/wp-content/plugins/ip-overlay-it-plugin/js/ip-overlay-it.js', array( 'jquery' ), null, true );
			wp_register_style( 'ip_overlay_plugin_style', get_bloginfo('url') . '/wp-content/plugins/ip-overlay-it-plugin/css/ip-overlay-it.css', false, null );

			// enqueue
			wp_enqueue_script( 'ip_overlay_plugin_script' );
			wp_enqueue_style( 'ip_overlay_plugin_style');

		}
	}