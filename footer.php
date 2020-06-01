<div class="notyn"></div>
<footer class="footer">
	<?php if (function_exists('dynamic_sidebar')) {
		dynamic_sidebar('all_footer');
	} ?>
	<div class="container-fluid container-footer">
	<?php do_action( 'zib_footer_conter');?>
	</div>
</footer>
<?php
zib_float_right();
zib_system_notice();
wp_footer();
 ?>
</body>
</html>