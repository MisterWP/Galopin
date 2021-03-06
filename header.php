<!doctype html>
<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<link rel="shortcut icon" href="/favicon.ico?v=0">

	<meta name="viewport" content="width=device-width">

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<!-- Scripts that need to be loaded first -->
	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

	<?php do_action('galopin_body_top'); ?>

	<div class="page-wrapper">
		<div class="content-wrapper <?php if ((is_front_page()) && get_option('galopin_use_hero')) echo 'cover'; ?>">

			<header class="menu-wrapper">
				<div class="search-wrapper">
					<button class="form-toggle typcn typcn-zoom"></button>
					 <?php get_search_form(true); ?>
				</div>

				<nav class="main-menu" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
					<?php
					wp_nav_menu(array(
						'theme_location' => 'primary',
						'menu_class'     => 'top-level-menu',
						'container'      => false,
						'depth'          => 2,
						'fallback_cb'    => 'galopin_nomenu'
					));
					?>
				</nav>

				<ul class="social-menu">
					<?php echo galopin_social(); ?>
				</ul>

			</header><!--END .menu-wrapper-->

			<div class="content <?php if (galopin_is_masonry()) echo 'masonry-wrapper'; ?>">

				<div class="hero-image" style="background-image: url(<?php echo galopin_get_hero_image_url(); ?>);">

					<button class="menu-toggle typcn typcn-th-menu <?php if (get_option('galopin_dark_hero')) echo 'inverted'; ?>"></button>

					<?php if(get_option('galopin_hero_logo')) : ?>

						<a href="<?php echo home_url(); ?>" class="hero-logo">
							<img src="<?php echo esc_url(get_option('galopin_hero_logo')); ?>" alt="<?php echo bloginfo('name'); ?>">
						</a>

					<?php else: ?>

						<a href="<?php echo home_url(); ?>" class="hero-text">
							<?php echo apply_filters('galopin_hero_text', (get_option('galopin_hero_text') ? sanitize_text_field(get_option('galopin_hero_text')) : bloginfo('name'))); ?>
						</a>

					<?php endif; ?>

				</div><!--END .hero-image-->