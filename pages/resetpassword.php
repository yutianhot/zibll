<?php

/**
 * Template name: Zibll-找回密码
 * Description:   找回密码页面
 */

$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'lostpassword';

if (isset($_REQUEST['key']))
	$action = 'resetpass';

if (!in_array($action, array('lostpassword', 'resetpass', 'success'), true))
	$action = 'lostpassword';


$classactive1 = '';
$classactive2 = '';
$classactive3 = '';


switch ($action) {
	case 'lostpassword':
		$errors = new WP_Error();

		if ($http_post) {
			$errors = retrieve_password();
		}

		if (isset($_REQUEST['error'])) {
			if ('invalidkey' == $_REQUEST['error'])
				$errors->add('invalidkey', __('抱歉，页面链接已失效！'));
			elseif ('expiredkey' == $_REQUEST['error'])
				$errors->add('expiredkey', __('抱歉，页面链接已失效！请重试'));
		}

		$classactive1 = ' class="active"';

		break;

	case 'resetpass':
		$user = check_password_reset_key($_REQUEST['key'], $_REQUEST['login']);

		if (is_wp_error($user)) {
			if ($user->get_error_code() === 'expired_key') {
				wp_redirect(zib_get_permalink(_pz('user_rp')) . '?action=lostpassword&error=expiredkey');
			} else {
				wp_redirect(zib_get_permalink(_pz('user_rp')) . '?action=lostpassword&error=invalidkey');
			}
			exit;
		}

		$errors = new WP_Error();

		if (isset($_POST['pass1']) && $_POST['pass1'] != $_POST['pass2'])
			$errors->add('password_reset_mismatch', __('两次输入密码不一致！'));

		if (isset($_POST['pass1']) && strlen($_POST['pass1']) < 6) {
			$errors->add('password_reset_mismatch2', '密码至少6位，由数字字母组成');
		}

		do_action('validate_password_reset', $errors, $user);

		if ((!$errors->get_error_code()) && isset($_POST['pass1']) && !empty($_POST['pass1'])) {
			reset_password($user, $_POST['pass1']);
			wp_redirect(zib_get_permalink(_pz('user_rp')) . '?action=success');
			exit;
		}
		$classactive2 = ' class="active"';
		break;
	case 'success':
		$classactive3 = ' class="active"';

		break;
}


get_header();

?>
<main class="container">
	<div class="box-body theme-box radius8 main-bg main-shadow">
		<div class="box-body">
			<div class="text-center theme-box">
				<ul class="resetpasssteps list-inline">
					<li<?php echo $classactive1 ?>><a>1.获取密码重置邮件</a></li>
						<li<?php echo $classactive2 ?>><a><i class="fa fa-angle-right mr10" aria-hidden="true"></i>2.设置新密码</a></li>
							<li<?php echo $classactive3 ?>><a><i class="fa fa-angle-right mr10" aria-hidden="true"></i>3.成功修改密码</a></li>
				</ul>
			</div>
			<div class="content resetpass">
				<?php
				if ($classactive1) {
					if ($errors !== true) {
				?>
						<form action="<?php echo esc_url(zib_get_permalink(_pz('user_rp')) . '?action=lostpassword'); ?>" method="post">
							<?php errormsg($errors); ?>
							<p class="box-body">
							<h4>填写用户名或邮箱：</h4>
							<input type="text" name="user_login" class="form-control input-lg" placeholder="用户名或邮箱" autofocus></p>
							<p class="box-body"><input type="submit" value="获取密码重置邮件" class="btn btn-block but c-blue padding-lg"></p>
						</form>
				<?php
					} else {
						echo '<h3><span class="text-success">已向注册邮箱发送邮件！</span></h3>';
						echo '<p>请查收邮件并点击重置密码链接</p>';
					}
				} ?>

				<?php if ($classactive2) { ?>
					<form action="" method="post">
						<?php errormsg($errors); ?>
						<div class="box-body">
						<h3>设置新密码：</h3>
						<div class="relative">
							<input type="password" name="pass1" class="form-control input-lg" placeholder="输入新密码" autofocus>
							<div class="abs-right passw muted-color"><i class="fa-fw fa fa-eye"></i></div>
							</div>
						</div>
						<div class="box-body">
						<h5>重复新密码：</h5>
							<div class="relative">
							<input type="password" name="pass2" class="form-control input-lg" placeholder="重复新密码">
							<div class="abs-right passw muted-color"><i class="fa-fw fa fa-eye"></i></div>
							</div>
						</div>
						<p class="box-body"><input type="submit" value="确认提交" class="btn btn-block but c-blue padding-lg"></p>
					</form>
				<?php } ?>

				<?php if ($classactive3) { ?>
					<form>
						<h3 class="text-center theme-box"><span class="text-success"><span class="glyphicon glyphicon-ok-circle"></span> 恭喜，您的密码已重置！</span></h3>
						<p> &nbsp; </p>
						<p class="text-center box-body"><a class="btn btn-block but c-green padding-lg" href="<?php echo get_bloginfo('url') ?>">返回首页</a></p>
					</form>
				<?php } ?>
			</div>
		</div>
	</div>
</main>
<?php

function errormsg($wp_error = '')
{
	if (empty($wp_error))
		$wp_error = new WP_Error();

	if ($wp_error->get_error_code()) {
		$errors = '';
		$messages = '';
		foreach ($wp_error->get_error_codes() as $code) {
			$severity = $wp_error->get_error_data($code);
			foreach ($wp_error->get_error_messages($code) as $error) {
				if ('message' == $severity)
					$messages .= '	' . $error . "<br />\n";
				else
					$errors .= '	' . $error . "<br />\n";
			}
		}
		if (!empty($errors)) {
			echo '<p class="errtip">' . apply_filters('login_errors', $errors) . "</p>\n";
		}
		if (!empty($messages)) {
			echo '<p class="errtip">' . apply_filters('login_messages', $messages) . "</p>\n";
		}
	}
}
function retrieve_password()
{
	global $wpdb, $wp_hasher;

	$errors = new WP_Error();

	if (empty($_POST['user_login'])) {
		$errors->add('empty_username', __('<strong>错误：</strong>请输入邮箱地址'));
	} else if (strpos($_POST['user_login'], '@')) {
		$user_data = get_user_by('email', trim($_POST['user_login']));
		if (empty($user_data))
			$errors->add('invalid_email', __('<strong>错误：</strong>未找到该邮箱地址注册的用户'));
	} else {
		$login = trim($_POST['user_login']);
		$user_data = get_user_by('login', $login);
	}

	do_action('lostpassword_post');

	if ($errors->get_error_code())
		return $errors;

	if (!$user_data) {
		$errors->add('invalidcombo', __('<strong>错误：</strong>用户名或电子邮件无效。'));
		return $errors;
	}

	$user_login = $user_data->user_login;
	$user_email = $user_data->user_email;

	do_action('retreive_password', $user_login);

	do_action('retrieve_password', $user_login);

	$allow = apply_filters('allow_password_reset', true, $user_data->ID);

	if (!$allow)
		return new WP_Error('no_password_reset', __('此用户不允许密码重置'));
	else if (is_wp_error($allow))
		return $allow;

	$key = wp_generate_password(20, false);

	do_action('retrieve_password_key', $user_login, $key);
	if (empty($wp_hasher)) {
		require_once ABSPATH . WPINC . '/class-phpass.php';
		$wp_hasher = new PasswordHash(8, true);
	}


	global $wp_version;

	if (version_compare($wp_version, '4.3.0', '>=')) {
		$hashed = time() . ':' . $wp_hasher->HashPassword($key);
	} else {
		$hashed = $wp_hasher->HashPassword($key);
	}


	$wpdb->update($wpdb->users, array('user_activation_key' => $hashed), array('user_login' => $user_login));

	$message = __('重设密码邮件：') . "\r\n\r\n";
	$message .= network_home_url('/') . "\r\n\r\n";
	$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
	$message .= __('如果不是您本人操作，请忽略此邮件！') . "\r\n\r\n";
	$message .= __('如果确认重置密码，请打开下面链接开始重设密码：') . "\r\n\r\n";

	$message .= network_site_url(zib_get_permalink(_pz('user_rp')) . "?action=resetpass&key=$key&login=" . rawurlencode($user_login), 'login');

	$message = str_replace(site_url('/') . site_url('/'), site_url('/'), $message);

	if (is_multisite())
		$blogname = $GLOBALS['current_site']->site_name;
	else
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

	$title = sprintf(__('[%s] 重置密码'), $blogname);

	$title = apply_filters('retrieve_password_title', $title);

	$message = apply_filters('retrieve_password_message', $message, $key);

	if ($message && !wp_mail($user_email, $title, $message))
		exit(__('无法发送电子邮件') . "<br />\n" . __('该站点暂时不能发送邮件，请联系站长处理') . "<br />\n");
	return true;
}
?>

<?php

get_footer();
