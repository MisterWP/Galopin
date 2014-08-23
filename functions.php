<?php

//////////////////
// Bootstraping //
//////////////////
if (!function_exists('galopin_activation')){
	function galopin_activation(){
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
}
add_action('after_switch_theme', 'galopin_activation');

//Register menus, sidebars and image sizes
if (!function_exists('galopin_setup')){
	function galopin_setup(){
		// Register menus
		register_nav_menu('primary', __('Main menu', 'galopin'));
		register_nav_menu('footer', __('Footer menu', 'galopin'));
		
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
		
		// Set images sizes
		add_image_size('galopin-post-thumbnail', 633, 400, true);
		
		// Load language
		//load_theme_textdomain('galopin', get_template_directory().'/local');
	}
}
add_action('after_setup_theme', 'galopin_setup');

//add custom image size to native dailogs
if (!function_exists('galopin_image_size_names_choose')){
	function galopin_image_size_names_choose($sizes) {
		$added = array('etendard-post-thumbnail'=>__('Post', 'galopin'));
		$newsizes = array_merge($sizes, $added);
		return $newsizes;
	}
}
add_filter('image_size_names_choose', 'galopin_image_size_names_choose');

//register supported post formats
if(!function_exists('galopin_custom_format')){
	function galopin_custom_format() {
		$cpts = array('post' => array('video', 'link', 'quote'));
		$current_post_type = $GLOBALS['typenow'];
		if ($current_post_type == 'post') add_theme_support('post-formats', $cpts[$GLOBALS['typenow']]);
	}
}
add_action( 'load-post.php', 'galopin_custom_format' );
add_action( 'load-post-new.php', 'galopin_custom_format' );

//enqueue styles & scripts
if (!function_exists('galopin_enqueue')){
	function galopin_enqueue(){
	
		$theme = wp_get_theme();
		
		//main stylesheet
		wp_enqueue_style('stylesheet', get_stylesheet_directory_uri().'/style.css', array(), $theme->get('Version'));
		
		//icons
		wp_enqueue_style('icons', get_stylesheet_directory_uri().'/fonts/typicons.min.css', array(), $theme->get('Version'));
	}
}
add_action('wp_enqueue_scripts', 'galopin_enqueue');


/////////////////////////
// Utility functions   //
/////////////////////////
if (!function_exists('etendard_excerpt')){
	function etendard_excerpt($length){
		if($length==0)
			return '';
		
		$content = strip_shortcodes(get_the_content());
		$excerpt = "<p>" . wp_trim_words( $content , $length ) . "</p>";
		return $excerpt;
	}
}

// Thanks to https://gist.github.com/tommcfarlin/f2310bfad60b60ae00bf#file-is-paginated-post-php
if (!function_exists('etendard_is_paginated_post')){
	function etendard_is_paginated_post() {
		global $multipage;
		return 0 !== $multipage;
	}
}

// Function to call if no primary menu
if (!function_exists('galopin_nomenu')){
	function galopin_nomenu(){
		echo '<ul class="top-level-menu"><li><a href="'.admin_url('nav-menus.php').'">'.__('Set up the main menu', 'galopin').'</a></li></ul>';
	}
}

//customized pagination links
if (!function_exists('galopin_posts_nav')){
	//derived from http://www.wpbeginner.com/wp-themes/how-to-add-numeric-pagination-in-your-wordpress-theme/
	/*
	 @param $extremes : display or not previous & next links
	 @param $separator : string to insert between each page
	*/
	
	function galopin_posts_nav($extremes=true, $separator='|'){
		if (is_singular()) return;
	
		global $wp_query;
		$output = '';
	
		// Stop execution if there's only 1 page
		if($wp_query->max_num_pages <= 1) return;
	
		$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
		$max = intval($wp_query->max_num_pages);
	
		// Add current page to the array
		if ($paged >= 1) $links[] = $paged;
	
		// Add the pages around the current page to the array
		if ($paged >= 3){
			$links[] = $paged - 1;
			$links[] = $paged - 2;
		}
	
		if (($paged + 2 ) <= $max){
			$links[] = $paged + 2;
			$links[] = $paged + 1;
		}
		
		$current = apply_filters('etendard_post_nav_current', '<span class="current">%s</span>');
		$linkTemplate = apply_filters('etendard_post_nav_link', '<a href="%s">%s</a>');
	
		// Previous Post Link
		if ($extremes && get_previous_posts_link()) previous_posts_link();
	
		// Link to first page, plus ellipses if necessary */
		if (!in_array(1, $links)){
			if ($paged == 1)
				$output .= sprintf($current, '1');
			else
				$output .= sprintf($linkTemplate, esc_url(get_pagenum_link(1)), '1');
			
			echo $separator;
			if (!in_array(2, $links)) $output .= '…'.$separator;
		}
	
		// Link to current page, plus 2 pages in either direction if necessary
		sort($links);
		foreach ((array) $links as $link){
			if ($paged == $link)
				$output .= sprintf($current, $link);
			else
				$output .= sprintf($linkTemplate, esc_url(get_pagenum_link($link)), $link);
				
			if ($link < $max) echo $separator;
		}
	
		// Link to last page, plus ellipses if necessary
		if (!in_array($max, $links)){
			if (!in_array($max-1, $links)) echo '…'.$separator;
	
			if ($paged == $max)
				$output .= sprintf($current, $link);
			else
				$output .= sprintf($linkTemplate, esc_url(get_pagenum_link($max)), $max);
		}
		
		echo apply_filters('etendard_post_nav', $output);
	
		// Next Post Link
		if ($extremes && get_next_posts_link()) next_posts_link();
	}
}

// Borrowed from http://themeshaper.com/2012/11/04/the-wordpress-theme-comments-template/
if (!function_exists('galopin_comment')){
	function galopin_comment($comment, $args, $depth){
		$GLOBALS['comment'] = $comment;
		switch ($comment->comment_type) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p>
				<?php echo apply_filters('etendard_pingback', __('Pingback:', 'galopin')); ?>
				<?php comment_author_link(); ?>
			</p>
		<?php
			break;
		default :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<aside class="">
					<?php if ($comment->comment_approved == '0') : ?>
						<em><?php echo apply_filters('etendard_commentaire_modere', __('Your comment is waiting for moderation.', 'galopin')); ?></em>
					<?php endif; ?>
					<?php echo get_avatar($comment, 104); ?>
				</aside>
				
				<div class="">
					<header class="comment-header">
						<div class="comment-author vcard">
							<?php echo apply_filters('etendard_commentaire_auteur', sprintf(__('%s', 'galopin'), sprintf(__('<cite class="fn">%s</cite>', 'etendard'), get_comment_author_link()))); ?>
						</div>
						<span class="comment-date">
							<?php echo apply_filters('etendard_commentaire_date', sprintf(__('Published on %s at %s', 'galopin'),get_comment_date(),get_comment_time('H:i'))); ?>
						</span>
					</header>
		 
					<div class="content">
						<?php comment_text(); ?>
					</div>
					
					<div class="reply">
						<?php 
						comment_reply_link(array_merge($args, 
							array(	'depth'=>$depth, 
									'max_depth'=>$args['max_depth'],
									'reply_text'=>apply_filters('etendard_commentaire_repondre', __('Reply', 'galopin'))))); 
						?>
					</div><!-- .reply -->
				</div>
			</article><!-- #comment-## -->
		<?php
			break;
		endswitch;
	}
}