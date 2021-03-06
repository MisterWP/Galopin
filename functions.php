<?php

/**
 * Galopin functions and definitions
 *
 * @package Galopin
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Define theme constants (relative to licensing)
define('GALOPIN_STORE_URL', 'https://www.themesdefrance.fr');
define('GALOPIN_THEME_NAME', 'Galopin');
define('GALOPIN_THEME_VERSION', '1.003');
define('GALOPIN_LICENSE_KEY', 'galopin_license_edd');

// Include theme updater (relative to licensing)
if(!class_exists('EDD_SL_Theme_Updater'))
	include(dirname( __FILE__ ).'/admin/EDD_SL_Theme_Updater.php');

// Define framework constant then load the Cocorico Framework
define('GALOPIN_COCORICO_PREFIX', 'galopin_');
if(is_admin())
	require_once 'admin/Cocorico/Cocorico.php';

// Widgets
require 'admin/widgets/social.php';
require 'admin/widgets/calltoaction.php';
require 'admin/widgets/video.php';

// Themes functions
require 'admin/functions/galopin-functions.php';
require_once 'admin/functions/color-functions.php';

//Refresh the permalink structure
add_action('after_switch_theme', 'flush_rewrite_rules');

//Remove accents in uploaded files
add_filter( 'sanitize_file_name', 'remove_accents' );

//Remove extra stuff from header
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since 1.0
 * @return void
 */
if (!function_exists('galopin_setup')){
	function galopin_setup(){

		// Load language
		load_theme_textdomain('galopin', get_template_directory().'/languages');

		// Register menus
		register_nav_menus( array(
			'primary'   => __('Main menu', 'galopin'),
			'footer' => __('Footer menu', 'galopin'),
		) );

		//Register sidebars
		register_sidebar(array(
			'name'          => __('Sidebar', 'galopin'),
			'id'            => 'blog',
			'description'   => __('Add widgets in the sidebar.', 'galopin'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		));

		register_sidebar(array(
			'name'          => __('Footer', 'galopin'),
			'id'            => 'footer',
			'description'   => __('Add widgets in the footer.', 'galopin'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>',
		));

		// Enable thumbnails
		add_theme_support('post-thumbnails');

		// Enable custom title tag for 4.1
		add_theme_support( 'title-tag' );

		// Enable Feed Links
		add_theme_support( 'automatic-feed-links' );

		// Set images sizes
		set_post_thumbnail_size('galopin-post-thumbnail', 633, 400, true);
		add_image_size('galopin-post-thumbnail-full', 900, 400, true);

		// Add Meta boxes for post formats
		require_once 'admin/metaboxes/post-formats.php';

	}
}
add_action('after_setup_theme', 'galopin_setup');

/**
 * Add custom image sizes in the WordPress Media Library
 *
 * @since 1.0
 * @param array $sizes The current image sizes list
 * @return array
 */
if (!function_exists('galopin_image_size_names_choose')){
	function galopin_image_size_names_choose($sizes) {
		$added = array('galopin-post-thumbnail'=>__('Post width', 'galopin'));
		$added = array('galopin-post-thumbnail-full'=>__('Fullpage width', 'galopin'));
		$newsizes = array_merge($sizes, $added);
		return $newsizes;
	}
}
add_filter('image_size_names_choose', 'galopin_image_size_names_choose');

/**
 * Register supported post formats
 *
 * @since 1.0
 * @return void
 */
if(!function_exists('galopin_custom_format')){
	function galopin_custom_format() {
		$cpts = array('post' => array('video', 'link', 'quote'));
		$current_post_type = $GLOBALS['typenow'];
		if ($current_post_type == 'post') add_theme_support('post-formats', $cpts[$GLOBALS['typenow']]);
	}
}
add_action( 'load-post.php', 'galopin_custom_format' );
add_action( 'load-post-new.php', 'galopin_custom_format' );

/**
 * Enqueue styles & scripts
 *
 * @since 1.0
 * @return void
 */
if (!function_exists('galopin_enqueue')){
	function galopin_enqueue(){

		wp_register_script('fitvids', get_template_directory_uri().'/js/min/jquery.fitvids.min.js', array('jquery'), false, true);

		wp_register_script('jq-aim', get_template_directory_uri().'/js/min/jquery.aim.min.js', array('jquery'), false, true);

		wp_register_script('galopin', get_template_directory_uri().'/js/min/galopin.min.js', array('jquery'), false, true);

		wp_enqueue_style( 'galopin-fonts', '//fonts.googleapis.com/css?family=Montserrat|Raleway:400,700&subset=latin,latin-ext');

		//main stylesheet
		wp_enqueue_style('stylesheet', get_stylesheet_directory_uri().'/style.css', array(), false);

		//icons
		wp_enqueue_style('icons', get_template_directory_uri().'/fonts/typicons.min.css', array(), false);

		wp_enqueue_script('fitvids');
		wp_enqueue_script('jq-aim');
		wp_enqueue_script('jquery-masonry');

		wp_enqueue_script('galopin');

		if ( is_singular() ){
			wp_enqueue_script( "comment-reply" );
		}
	}
}
add_action('wp_enqueue_scripts', 'galopin_enqueue');

/**
 * Register the theme options page in the administration
 *
 * @since 1.0
 * @return void
 */
if (!function_exists('galopin_admin_menu')){
	function galopin_admin_menu(){
		add_theme_page(__('Galopin Settings', 'galopin'),__('Galopin Settings', 'galopin'), 'edit_theme_options', 'galopin_options', 'galopin_options');
	}
}
add_action('admin_menu', 'galopin_admin_menu');

/**
 * Loads the theme options page
 *
 * @since 1.0
 * @return void
 */
if (!function_exists('galopin_options')){
	function galopin_options(){
		if (!current_user_can('edit_theme_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

       	include 'admin/index.php';
    }
}

/**
 * Custom CSS loading
 *
 * @since 1.0
 * @return void
 */
if(!function_exists('galopin_custom_styles')){
	function galopin_custom_styles(){
		if (get_option("galopin_custom_css")){
			echo '<style type="text/css">';
			echo strip_tags(stripslashes(get_option("galopin_custom_css")));
			echo '</style>';
		}
	}
}
add_action('wp_head', 'galopin_custom_styles', 99);

/**
 * Applying the theme main color
 *
 * @since 1.0
 * @return void
 */
if(!function_exists('galopin_user_styles')){
	function galopin_user_styles(){
		if (get_option('galopin_color')){
			$color = apply_filters('galopin_color', get_option('galopin_color'));

			$hsl = galopin_RGBToHSL(galopin_HTMLToRGB($color));
			if ($hsl->lightness > 180){
				$contrast = apply_filters('galopin_color_contrast', '#333');
			}
			else{
				$contrast = apply_filters('galopin_color_contrast', '#fff');
			}

			$hsl->lightness -= 30;
			$complement = apply_filters('galopin_color_complement', galopin_HSLToHTML($hsl->hue, $hsl->saturation, $hsl->lightness));
		}
		else{ // Default color
			$color = '#E54C3C';
			$complement = '#c73829';
			$contrast = '#fff';
		}
		?>
			<style type="text/css">
			.button,
			.comment-form input[type="submit"],
			html a.button,
			input[type='submit'],
			input[type='button'],
			.widget_calendar #next a,
			.widget_calendar #prev a,
			.menu-wrapper,
			.search-form .submit-btn,
			.post-content th,
			.post-pagination,
			.pagination,
			.menu-wrapper .sub-menu a:hover,
			.back-to-top{
				background: <?php echo $color; ?>;
				color: <?php echo $contrast; ?>;
			}
			.button:hover,
			.comment-form input[type="submit"]:hover,
			html a.button:hover,
			input[type='submit']:hover,
			input[type='button']:hover,
			.widget_calendar #next a:hover,
			.widget_calendar #prev a:hover,
			.search-form .submit-btn:hover,
			.post-content th:hover,
			.post-pagination:hover,
			.back-to-top:hover{
				background: <?php echo $complement; ?>;
				color: <?php echo $contrast; ?>;
			}
			.masonry .brick-link:before,
			.post-thumbnail .post-permalink:before{
				background: <?php echo $contrast; ?>;
				color: <?php echo $color; ?>;
			}
			a,
			.content a,
			.menu-wrapper .sub-menu a,
			.footer a,
			.post-header-title a:hover,
			.post-header-meta a,
			.entry-navigation a,
			.masonry .post-header-title,
			.post-content ul > li:before,
			.post-content ol > li:before,
			.post-content a,
			.post-footer-meta a,
			.comment-author a,
			.comment-reply-link,
			.comment-navigation a,
			.widget a,
			.comment-form .logged-in-as a,
			.post-header-title:before,
			.widget > h3:before{
				color: <?php echo $color; ?>;
			}
			a:hover,
			.content a:hover,
			.footer a:hover,
			.post-header-meta a:hover,
			.entry-navigation a:hover,
			.post-content a:hover,
			.post-footer-meta a:hover,
			.comment-author a:hover,
			.comment-reply-link:hover,
			.comment-navigation a:hover,
			.widget a:hover,
			.comment-form .logged-in-as a:hover{
				color: <?php echo $complement; ?>;
			}

			.footer,
			.post-header,
			.comment-footer,
			.masonry-footer,
			.masonry-header{
				border-color: <?php echo $color; ?>;
			}

			.masonry .brick:hover,
			.blog .post-thumbnail:hover,
			.archive .post-thumbnail:hover,
			.hero-image{
				background-color: <?php echo $color; ?>;
			}

			.masonry .brick:hover .post-header-title,
			.masonry .brick:hover .post-header-title:before,
			.masonry .brick:hover .post-header-title blockquote a,
			.masonry .brick:hover .post-content,
			.masonry .brick:hover .post-quote-author,
			.masonry .brick:hover .masonry-footer,
			.menu-toggle,
			.menu-wrapper a{
				color: <?php echo $contrast; ?>;
			}
			</style>
		<?php }
}
add_action('wp_head','galopin_user_styles', 98);


/**
 * License activation stuff (from Easy Digital Downloads Software Licensing Addon)
 * This function will activate the theme licence on Themes de France
 *
 * @since 1.0
 * @return void
 */
if(!function_exists('galopin_edd')){
	function galopin_edd(){
		$license = trim(get_option(GALOPIN_LICENSE_KEY));
		$status = get_option('galopin_license_status');

		if (!$status){
			//valider la license
			$api_params = array(
				'edd_action'=>'activate_license',
				'license'=>$license,
				'item_name'=>urlencode(GALOPIN_THEME_NAME)
			);

			$response = wp_remote_get(add_query_arg($api_params, GALOPIN_STORE_URL), array('timeout'=>15, 'sslverify'=>false));

			if (!is_wp_error($response)){
				$license_data = json_decode(wp_remote_retrieve_body($response));
				if ($license_data->license === 'valid') update_option('galopin_license_status', true);
			}
		}

		$edd_updater = new EDD_SL_Theme_Updater(array(
				'remote_api_url'=> GALOPIN_STORE_URL,
				'version' 	=> GALOPIN_THEME_VERSION,
				'license' 	=> $license,
				'item_name' => GALOPIN_THEME_NAME,
				'author'	=> __('Themes de France','galopin')
			)
		);
	}
}
add_action('admin_init', 'galopin_edd');

/**
 * Display an admin notice if the licence isn't activated
 *
 * @since 1.0
 * @return void
 */
if(!function_exists('galopin_admin_notice')){
	function galopin_admin_notice(){
		global $current_user;
        $user_id = $current_user->ID;

		if(!get_option('galopin_license_status')){
			echo '<div class="error"><p>';
			_e("In order to get updates, please enter your licence that you received by email.", 'galopin');
			echo '</p></div>';
		}
	}
}
add_action('admin_notices', 'galopin_admin_notice');