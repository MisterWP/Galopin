<?php if(has_tag() && is_single()){ ?>
	
	<span class="post-footer-meta" itemscope="keywords">
	
		<?php echo get_the_tag_list('',' | ',''); ?>
	
	</span>

<?php } ?>

<?php if(etendard_is_paginated_post()){ ?>
	<nav>
	
	<?php wp_link_pages(array(
		'before'=>'<div class="page-links"><span class="page-links-title">'.__('Pages:', 'galopin').'</span>', 
		'after'=>'</div>'
	)); ?>
	
	</nav>
<?php } ?>