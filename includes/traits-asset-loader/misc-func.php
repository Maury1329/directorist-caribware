<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

use \Directorist\Script_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

class Misc_Functions {

	public function debug_enabled() {
		return get_directorist_option( 'script_debugging', false, true );
	}

	public function register_single_script( $handle, $script ) {
        $url = $this->script_file_url( $script );

        if ( !empty( $script['dep'] ) ) {
            $dep = $script['dep'];
        }
        else {
            $dep = ( $script['type'] == 'js' ) ? ['jquery'] : [];
        }

        if ( $script['type'] == 'css' ) {
            wp_register_style( $handle, $url, $dep, $this->version );
        }
        else {
            wp_register_script( $handle, $url, $dep, $this->version );
        }
	}
	/**
	 * Absoulute url based on various factors eg. min, rtl etc.
	 *
	 * @param  array $script Single item of $Asset_Loader::scripts array.
	 *
	 * @return string        URL string.
	 */
	public function script_file_url( $script ) {
		if ( !empty( $script['ext'] ) ) {
			return $script['ext'];
		}

		$min  = $this->debug_enabled() ? '' : '.min';
		$rtl  = ( $script['type'] == 'css' && !empty( $script['rtl'] ) && is_rtl() ) ? '.rtl' : '';
		$ext  = $script['type'] == 'css' ? '.css' : '.js';
		$url = $script['path'] . $min . $rtl . $ext;
		return $url;
	}

	/**
	 * Minify inline styles.
	 *
	 * @link https://gist.github.com/Rodrigo54/93169db48194d470188f
	 *
	 * @param  string $input
	 *
	 * @return string
	 */
	public static function minify_css( $input ) {
		if(trim($input) === "") return $input;
		return preg_replace(
			array(
				// Remove comment(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
				// Remove unused white-space(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
				// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
				'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
				// Replace `:0 0 0 0` with `:0`
				'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
				// Replace `background-position:0` with `background-position:0 0`
				'#(background-position):0(?=[;\}])#si',
				// Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
				'#(?<=[\s:,\-])0+\.(\d+)#s',
				// Minify string value
				'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
				'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
				// Minify HEX color code
				'#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
				// Replace `(border|outline):none` with `(border|outline):0`
				'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
				// Remove empty selector(s)
				'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
			),
			array(
				'$1',
				'$1$2$3$4$5$6$7',
				'$1',
				':0',
				'$1:0 0',
				'.$1',
				'$1$3',
				'$1$2$4$5',
				'$1$2$3',
				'$1:0',
				'$1$2'
			),
			$input);
	}
	/**
	 * @todo apply icon condition
	 */
	public function enqueue_icon_styles() {
		wp_enqueue_style( 'directorist-line-awesome' );
		wp_enqueue_style( 'directorist-font-awesome' );

		return;

		$icon_type = get_directorist_option( 'font_type', '', true );

		if ( 'line' === $icon_type ) {
			wp_enqueue_style( 'directorist-line-awesome' );
		} else {
			wp_enqueue_style( 'directorist-font-awesome' );
		}
	}

	public function enqueue_map_styles() {
		$map_type = get_directorist_option( 'select_listing_map', 'openstreet' );

		if ( $map_type == 'openstreet' ) {
			wp_enqueue_style( 'directorist-openstreet-map-leaflet' );
			wp_enqueue_style( 'directorist-openstreet-map-openstreet' );
		}
	}
	public function enqueue_single_listing_shortcode_scripts() {
		// Vendor Scripts
		wp_enqueue_script( 'directorist-jquery-barrating' );
		wp_enqueue_script( 'directorist-sweetalert-script' );
		// wp_enqueue_script( 'directorist-plasma-slider' );
		wp_enqueue_script( 'directorist-slick' );

		// Map Scripts
		$this->enqueue_single_listing_page_map_scripts();

		// Common Scripts
		$this->enqueue_common_shortcode_scripts();
	}

	protected function enqueue_common_shortcode_scripts() {
		// Vendor JS
		wp_enqueue_script( 'directorist-popper' );
		wp_enqueue_script( 'directorist-tooltip' );
		wp_enqueue_script( 'directorist-plasma-slider' );
		wp_enqueue_script( 'directorist-no-script' );

		// Custom JS
		wp_enqueue_script( 'directorist-global-script' );
		wp_enqueue_script( 'directorist-main-script' );
		wp_enqueue_script( 'directorist-atmodal' );
	}

	public function enqueue_single_listing_page_map_scripts() {
		$map_type = get_directorist_option( 'select_listing_map', 'openstreet' );

		if ( $map_type == 'openstreet' ) {
			$args = [];
			$default['with_cluster'] = false;
			$args = array_merge( $default, $args );
	
			$with_cluster = ( ! empty( $args['with_cluster'] ) ) ? true : false;
	
			wp_enqueue_script( 'directorist-openstreet-layers' );
			wp_enqueue_script( 'directorist-openstreet-unpkg' );
			wp_enqueue_script( 'directorist-openstreet-unpkg-index' );
			wp_enqueue_script( 'directorist-openstreet-unpkg-libs' );
			wp_enqueue_script( 'directorist-openstreet-leaflet-versions' );
	
			if ( $with_cluster ) {
				wp_enqueue_script( 'directorist-openstreet-leaflet-markercluster-versions' );
			}
	
			wp_enqueue_script( 'directorist-openstreet-libs-setup' );
			wp_enqueue_script( 'directorist-openstreet-open-layers' );
			wp_enqueue_script( 'directorist-openstreet-crosshairs' );
		}
	}
	public function load_localized_data() {
		// Public JS
		wp_localize_script( 'directorist-main-script', 'atbdp_public_data', Script_Helper::get_main_script_data() );
		wp_localize_script( 'directorist-main-script', 'directorist_options', $this->directorist_options_data() );
		wp_localize_script( 'directorist-search-form-listing', 'atbdp_search_listing', $this->search_form_localized_data() );

		wp_localize_script( 'directorist-range-slider', 'atbdp_range_slider', $this->search_listing_localized_data() );
		wp_localize_script( 'directorist-search-listing', 'atbdp_search_listing', $this->search_listing_localized_data() );
		wp_localize_script( 'directorist-search-listing', 'atbdp_search', [
			'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
			'ajax_url' => admin_url('admin-ajax.php'),
		]);

		// Admin JS
		wp_localize_script( 'directorist-admin-script', 'atbdp_admin_data', Script_Helper::get_admin_script_data() );
		wp_localize_script( 'directorist-admin-script', 'directorist_options', $this->directorist_options_data() );
		wp_localize_script( 'directorist-admin-script', 'atbdp_public_data', Script_Helper::get_main_script_data() );
		wp_localize_script( 'directorist-multi-directory-builder', 'ajax_data', $this->admin_ajax_localized_data() );
		wp_localize_script( 'directorist-multi-directory-archive', 'ajax_data', $this->admin_ajax_localized_data() );
		wp_localize_script( 'directorist-settings-manager', 'ajax_data', $this->admin_ajax_localized_data() );
		wp_localize_script( 'directorist-import-export', 'import_export_data', $this->admin_ajax_localized_data() );
	}

	private function search_listing_localized_data() {
		return Script_Helper::get_search_script_data([
			'directory_type_id' => get_post_meta( '_directory_type', get_the_ID(), true ),
		]);
	}

	private function search_form_localized_data() {
		$directory_type_id = ( isset( $args['directory_type_id'] ) ) ? $args['directory_type_id'] : '';
		$data = Script_Helper::get_search_script_data([
			'directory_type_id' => $directory_type_id
		]);
		return $data;
	}

	private function directorist_options_data() {
		return Script_Helper::get_option_data();
	}

	private function admin_ajax_localized_data() {
		return [ 'ajax_url' => admin_url('admin-ajax.php') ];
	}


}