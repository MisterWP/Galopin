<?php $video_link = get_post_meta($post->ID, '_galopin_video_meta', true); ?>

<article <?php post_class('article'); ?>>

	<header class="post-header">
	
		<div class="post-video">
									
			<?php echo wp_oembed_get( $video_link); ?>
			
		</div>
		
		<?php if (is_single()): ?>
			
			<h1 class="post-header-title"><?php the_title(); ?></h1>
			
		<?php else: ?>
		
			<h2 class="post-header-title">
			
				<?php if (galopin_is_masonry()): ?>
						
					<?php the_title(); ?>
					
				<?php else: ?>
				
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<?php the_title(); ?>
					</a>
				
				<?php endif; ?>
			
			</h2>
			
		<?php endif; ?>
		
		<?php if (!galopin_is_masonry()) get_template_part('content', 'header-meta'); ?>
		
	</header>
	
	<div class="post-content">
		
		<?php get_template_part( 'content', 'body' ); ?>	

	</div>
	
	<footer class="post-footer">
	
		<?php get_template_part( 'content', 'footer-meta' ); ?>
		
	</footer>
	
</article>