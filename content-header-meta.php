<?php
	$post_header_date     = apply_filters('galopin_post_header_date', true);
	$post_header_author   = apply_filters('galopin_post_header_author', true);
	$post_header_category = apply_filters('galopin_post_header_category', true);
	$post_header_comments = apply_filters('galopin_post_header_comments', true);
?>

<?php if(!is_page()): ?> 
		
	<span class="post-header-meta">
		
	<?php
		
		if($post_header_date || $post_header_author || $post_header_category){
			_e('Published ','galopin');
		}
		if($post_header_date){ ?>
			
			<?php _e('on','galopin'); ?>
			
			<time class="date published" itemprop="datePublished" datetime="<?php the_time('c'); ?>">
				<?php the_time( get_option( 'date_format' ) ); ?>
			</time>
			
		<?php
		}
		if($post_header_author){ ?>
			
			<?php _e('by','galopin'); ?>
			
			<span class="vcard author">
				<span class="fn" itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person">
					<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>" itemprop="url" rel="author"><?php the_author_meta('display_name'); ?></a>
				</span>
			</span>
			
		<?php
		}
		if($post_header_category){
			if(!is_category()){
				printf(__('in','galopin') . ' ' . get_the_category_list('/') . ' ');
			}
		}
		if($post_header_date || $post_header_author || $post_header_category){
			echo '| ';
		}
		if($post_header_comments){
			
			if(comments_open()){ ?>
			
				<a href="<?php the_permalink(); ?>#comments"><?php comments_number(__('No Comment', 'galopin'), __('One Comment', 'galopin'), __('% Comments', 'galopin')); ?></a>
				
				<?php echo ' | ';
				
			}
		}
		
		edit_post_link(__('Edit', 'galopin'));
	?>
		
	</span>

<?php endif; ?>