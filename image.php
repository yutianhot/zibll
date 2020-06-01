<?php get_header(); ?>

<div class="container image-container">
	<?php while (have_posts()) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class('image-attachment'); ?>>
			<header class="image-header theme-box">
				<div class="text-center">
					<h3 class="image-title"><?php the_title(); ?></h3>

					<footer class="image-meta muted-2-color">
						<?php
						$metadata = wp_get_attachment_metadata();
						?>
						<?php echo get_the_date(); ?>
						&nbsp; 发布在 &nbsp;
						<a href="<?php echo get_permalink($post->post_parent) ?>"><?php echo get_the_title($post->post_parent) ?></a> &nbsp;
						<a target="_blank" href="<?php echo wp_get_attachment_url() ?>">原图(<?php echo $metadata['width'] . 'x' . $metadata['height'] ?>)</a> &nbsp;
						<?php edit_post_link('[编辑]', '<span class="image-edit-link">', '</span>'); ?>
					</footer>

				</div>
			</header>

			<div class="image-content text-center">
				<?php
				$attachments = array_values(get_children(array('post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID')));
				foreach ($attachments as $k => $attachment) :
					if ($attachment->ID == $post->ID)
						break;
				endforeach;
				$k++;
				if (count($attachments) > 1) :
					if (isset($attachments[$k])) :
						$next_attachment_url = get_attachment_link($attachments[$k]->ID);
					else :
						$next_attachment_url = get_attachment_link($attachments[0]->ID);
					endif;
				else :
					$next_attachment_url = wp_get_attachment_url();
				endif;
				?>
				<a href="<?php echo esc_url($next_attachment_url); ?>" title="<?php the_title_attribute(); ?>" rel="attachment"><?php echo wp_get_attachment_image($post->ID, false); ?></a>

				<nav class="box-body" role="navigation">
					<?php previous_image_link(false, '上一张'); ?>
					<?php next_image_link(false, '下一张'); ?>
				</nav>

				<?php if (!empty($post->post_excerpt)) : ?>
					<div class="image-caption">
						<?php the_excerpt(); ?>
					</div>
				<?php endif; ?>

				<div class="image-description">
					<?php //the_content(); 
					?>
					<?php wp_link_pages(array('before' => '<div class="page-links">' . __('Pages:', 'twentytwelve'), 'after' => '</div>')); ?>
				</div>



			</div>

		</article>


	<?php endwhile; ?>
</div>

<?php get_footer(); ?>