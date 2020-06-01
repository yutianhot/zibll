<?php

/**
 * @package   Options_Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2010-2014 WP Theming
 */

class Options_Framework_Admin
{

	/**
	 * Page hook for the options screen
	 *
	 * @since 1.7.0
	 * @type string
	 */
	protected $options_screen = null;

	/**
	 * Hook in the scripts and styles
	 *
	 * @since 1.7.0
	 */
	public function init()
	{

		// Gets options to load
		$options = &Options_Framework::_optionsframework_options();

		// Checks if options are available
		if ($options) {

			// Add the options page and menu item.
			add_action('admin_menu', array($this, 'add_custom_options_admin_bar'));
			if (function_exists('framework_option_args') && isset(framework_option_args()['show_toolbar'])) {
				add_action('admin_bar_menu', array($this, 'add_custom_options_toolbar'), 999);
			}
			// Add the required scripts and styles
			add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
			add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

			// Settings need to be registered after admin_init
			add_action('admin_init', array($this, 'settings_init'));
		}
	}

	function add_custom_options_toolbar($wp_admin_bar)
	{
		$menu = $this->menu_settings();
		$args = array(
			'id' => 'options_toolbar',
			'title' => $menu['menu_title'],
			'href' => admin_url('admin.php?page=' . $menu['menu_slug']),
			'meta' => array(
				'title' => $menu['menu_title'],
			)
		);
		$wp_admin_bar->add_node($args);
		$args = array();
		array_push($args, array(
			'id'		=>	'widgets',
			'title'		=>	'小工具设置',
			'href'		=>	admin_url('widgets.php'),
			'parent'	=>	'options_toolbar'
		));
		array_push($args, array(
			'id'     	=> 'menus',
			'title'		=>	'菜单设置',
			'href'		=>	admin_url('nav-menus.php'),
			'parent' 	=> 'options_toolbar'
		));
		sort($args);
		foreach ($args as $each_arg) {
			$wp_admin_bar->add_node($each_arg);
		}
	}
	/**
	 * Registers the settings
	 *
	 * @since 1.7.0
	 */
	function settings_init()
	{

		// Get the option name
		$options_framework = new Options_Framework;
		$name = $options_framework->get_option_name();

		// Registers the settings fields and callback
		register_setting('optionsframework', $name, array($this, 'validate_options'));

		$theme_data = wp_get_theme();
		$_version = $theme_data['Version'];

		if (get_option($name . '_version') !== false) {
			if (version_compare(get_option($name . '_version'), $_version) == -1) {
				add_action('admin_notices', array($this, 'notice_update'));
			}
		} else {
			add_action('admin_notices', array($this, 'notice_install_new'));
		}

		// Displays notice after options save
		add_action('optionsframework_after_validate', array($this, 'save_options_notice'));
	}

	static function menu_settings()
	{
		$options_framework = new Options_Framework;
		$name = $options_framework->get_option_name();
		$page_title = $name . '主题设置';
		$menu_title = $name . '主题设置';
		if (function_exists('framework_option_args')) {
			if (isset(framework_option_args()['page_title'])) {
				$page_title = framework_option_args()['page_title'];
			}
			if (isset(framework_option_args()['menu_title'])) {
				$menu_title = framework_option_args()['menu_title'];
			}
		}

		$menu = array(
			'mode' => 'submenu',
			'page_title' => __($page_title, 'theme-textdomain'),
			'menu_title' => __($menu_title, 'theme-textdomain'),
			'capability' => 'edit_theme_options',
			'menu_slug' => 'framework_' . $name,
			'parent_slug' => 'themes.php',
			'icon_url' => 'dashicons-admin-generic',
			'position' => '61'

		);

		return apply_filters('optionsframework_menu', $menu);
	}

	/**
	 * Add a subpage called "Theme Options" to the appearance menu.
	 *
	 * @since 1.7.0
	 */

	function add_custom_options_admin_bar()
	{
		$menu = $this->menu_settings();
		$this->options_screen = add_menu_page(
			$menu['menu_title'],
			$menu['menu_title'],
			$menu['capability'],
			$menu['menu_slug'],
			array($this, 'options_page')
		);
	}

	function notice_install_new()
	{
		$menu = $this->menu_settings();
		$options_framework = new Options_Framework;
		$name = $options_framework->get_option_name();
		$con = '<div class="updated">
				<h2>欢迎使用' . $name . '主题：v' . wp_get_theme()['Version'] . '</h2>
				<p>初次使用，建议您先
					<a href="' . admin_url('nav-menus.php') . '">设置菜单</a> 以及
					<a href="' . admin_url('widgets.php') . '">添加小工具</a> ，然后在看看丰富的
					<a href="' . admin_url('admin.php?page=' . $menu['menu_slug']) . '">主题设置</a> 吧！
				</p>
			</div>';

		if (function_exists('framework_option_args') && isset(framework_option_args()['notice_install_new'])) {
			$con = framework_option_args()['notice_install_new'];
		}
		echo  $con;
	}

	function notice_update()
	{
		$menu = $this->menu_settings();
		$options_framework = new Options_Framework;
		$name = $options_framework->get_option_name();
		$con = '<div class="updated">
				<h2>恭喜您！' . $name . '主题已更新</h2>
				<p>当前主题版本：v' . wp_get_theme()['Version'] . '，快来试试
					<a href="' . admin_url('admin.php?page=' . $menu['menu_slug']) . '">新功能</a>吧！
				</p>
			</div>';

		if (function_exists('framework_option_args') && isset(framework_option_args()['notice_update'])) {
			$con = framework_option_args()['notice_update'];
		}
		echo  $con;
	}

	function enqueue_admin_styles($hook)
	{

		if ($this->options_screen != $hook)
			return;

		wp_enqueue_style('optionsframework', OPTIONS_FRAMEWORK_DIRECTORY . 'css/optionsframework.css', array(),  Options_Framework::VERSION);
		wp_enqueue_style('wp-color-picker');
	}

	/**
	 * Loads the required javascript
	 *
	 * @since 1.7.0
	 */
	function enqueue_admin_scripts($hook)
	{

		if ($this->options_screen != $hook)
			return;

		// Enqueue custom option panel JS
		wp_enqueue_script('slider-number', OPTIONS_FRAMEWORK_DIRECTORY . 'js/ion.rangeSlider.min.js', array('jquery', 'wp-color-picker'), Options_Framework::VERSION);
		wp_enqueue_script(
			'options-custom',
			OPTIONS_FRAMEWORK_DIRECTORY . 'js/options-custom.js',
			array('jquery', 'wp-color-picker'),
			Options_Framework::VERSION
		);

		// Inline scripts from options-interface.php
		add_action('admin_head', array($this, 'of_admin_head'));
		if (function_exists('framework_option_args') && !empty(framework_option_args()['debug'])) {
			add_action('admin_head',  array($this, 'optionsframework_custom_scripts'));
		}
	}

	function of_admin_head()
	{
		do_action('optionsframework_custom_scripts');
	}

	function optionsframework_custom_scripts()
	{
		$option_name = get_option_framework_name();
		$get_option = get_option($option_name);
		echo '<script type="text/javascript">
		jQuery(document).ready(function() {
			console.log(' . json_encode($get_option) . ');
		});
		</script>';
	}


	/**
	 * Builds out the options panel.
	 *
	 * If we were using the Settings API as it was intended we would use
	 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
	 * we'll call our own custom optionsframework_fields.  See options-interface.php
	 * for specifics on how each individual field is generated.
	 *
	 * Nonces are provided using the settings_fields()
	 *
	 * @since 1.7.0
	 */
	function options_page()
	{ ?>

		<div class="wrap optionsframework">
			<h2></h2>
			<div id="optionsframework-wrap">

				<?php $menu = $this->menu_settings(); ?>
				<h2 class="nav-page-title"><?php echo esc_html($menu['page_title']); ?><span class="after"><?php $options_framework = new Options_Framework;
																												$name = $options_framework->get_option_name();
																												echo strtoupper($name); ?></span>
				</h2>

				<nav class="nav-tab-wrapper">
					<?php echo Options_Framework_Interface::optionsframework_tabs(); ?>
				</nav>

				<?php settings_errors('options-framework'); ?>

				<div id="optionsframework-metabox" class="metabox-holder">
					<div id="optionsframework" class="postbox">
						<form action="options.php" method="post">
							<?php settings_fields('optionsframework'); ?>
							<?php Options_Framework_Interface::optionsframework_fields(); /* Settings */ ?>
							<div id="optionsframework-submit">
								<?php $input = $this->options_page_submit();
								echo apply_filters('of_optionsframework_page_submit', $input); ?>
								<a href="javascript:;" class="button-nav"></a>
								<div class="clear"></div>
							</div>
						</form>
					</div> <!-- / #container -->
				</div>
				<?php do_action('optionsframework_after'); ?>
			</div> <!-- / .wrap -->
		</div> <!-- / .wrap -->

<?php
	}

	function options_page_submit()
	{
		$reset_js = 'return confirm(\'确认重置全部设置?  重置后主题的全部设置将恢复默认!\')';
		return '<input type="submit" class="button-bc" name="update" value="保存设置" />
		<input type="submit" class="button-cz" name="reset" value="重置全部" onclick="' . $reset_js . '">';
	}
	/**
	 * Validate Options.
	 *
	 * This runs after the submit/reset button has been clicked and
	 * validates the inputs.
	 *
	 * @uses $_POST['reset'] to restore default options
	 */
	function validate_options($input)
	{

		/*
		 * Restore Defaults.
		 *
		 * In the event that the user clicked the "Restore Defaults"
		 * button, the options defined in the theme's options.php
		 * file will be added to the option for the active theme.
		 */
		$options_framework = new Options_Framework;
		$name = $options_framework->get_option_name();
		$_version = wp_get_theme()['Version'];
		if (isset($_POST['reset'])) {
			add_settings_error('options-framework', 'restore_defaults', __('设置已重置.', 'theme-textdomain'), 'updated fade');
			delete_option($name . '_version');
			return $this->get_default_values();
		}

		if (isset($_POST['update'])) {
			update_option($name . '_version', $_version);
		}

		/*
		 * Update Settings
		 *
		 * This used to check for $_POST['update'], but has been updated
		 * to be compatible with the theme customizer introduced in WordPress 3.4
		 */

		$clean = array();
		$options = &Options_Framework::_optionsframework_options();
		foreach ($options as $option) {

			if (!isset($option['id'])) {
				continue;
			}

			if (!isset($option['type'])) {
				continue;
			}

			$id = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($option['id']));

			// Set checkbox to false if it wasn't sent in the $_POST
			if ('checkbox' == $option['type'] && !isset($input[$id])) {
				$input[$id] = false;
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ('multicheck' == $option['type'] && !isset($input[$id])) {
				foreach ($option['options'] as $key => $value) {
					$input[$id][$key] = false;
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if (has_filter('of_sanitize_' . $option['type'])) {
				$clean[$id] = apply_filters('of_sanitize_' . $option['type'], $input[$id], $option);
			}
		}

		// Hook to run after validation
		do_action('optionsframework_after_validate', $clean);

		return apply_filters('of_validate_options', $clean);
	}

	/**
	 * Display message when options have been saved
	 */

	function save_options_notice()
	{
		add_settings_error('options-framework', 'save_options', apply_filters('of_save_options_error', __('设置保存成功.', 'theme-textdomain')), 'updated fade');
	}

	/**
	 * Get the default values for all the theme options
	 *
	 * Get an array of all default values as set in
	 * options.php. The 'id','std' and 'type' keys need
	 * to be defined in the configuration array. In the
	 * event that these keys are not present the option
	 * will not be included in this function's output.
	 *
	 * @return array Re-keyed options configuration array.
	 *
	 */
	function get_default_values()
	{
		$output = array();
		$config = &Options_Framework::_optionsframework_options();
		foreach ((array) $config as $option) {
			if (!isset($option['id'])) {
				continue;
			}
			if (!isset($option['std'])) {
				continue;
			}
			if (!isset($option['type'])) {
				continue;
			}
			if (has_filter('of_sanitize_' . $option['type'])) {
				$output[$option['id']] = apply_filters('of_sanitize_' . $option['type'], $option['std'], $option);
			}
		}
		return $output;
	}
}
