<section>
	<div class="f404">
		<img src="<?php echo get_stylesheet_directory_uri() ?>/img/404.png">
		<p class="muted-color box-body separator">未找到相关内容</p>
		<div class="theme-box box-body">
			<div class="relative line-form search-input">
				<form method="get" action="<?php echo esc_url(home_url('/'));?>">
					<input type="text" name="s" class="line-form-input" tabindex="1" placeholder="搜索本站内容">
					<div class="abs-right muted-color">
						<button type="submit" class="null"><i class="fa-fw fa fa-search"></i></button>
					</div>
					<i class="line-form-line"></i>
				</form>
			</div>
		</div>
	</div>
</section>

