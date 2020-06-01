<?php

//decode by http://www.yunlu99.com/
error_reporting(E_ALL ^ E_NOTICE);
function zib_get_http_curl_url()
{
	return get_stylesheet_directory_uri() . '/framework/code/http-curl.php';
}
function zib_curl_get_authorization($_var_0)
{
        return true;
}
function zib_curl_delete_authorization()
{
	delete_option('post_autkey');
	delete_option('zibll_authorization');
}
function zib_curl_post($_var_6, $_var_7 = '')
{
	if (function_exists('curl_init')) {
		$_var_8 = curl_init();
		curl_setopt($_var_8, CURLOPT_URL, $_var_6);
		curl_setopt($_var_8, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($_var_8, CURLOPT_POST, true);
		curl_setopt($_var_8, CURLOPT_POSTFIELDS, $_var_7);
		curl_setopt($_var_8, CURLOPT_TIMEOUT, 10);
		curl_setopt($_var_8, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($_var_8, CURLOPT_SSL_VERIFYHOST, false);
		$_var_9 = curl_exec($_var_8);
		curl_close($_var_8);
		return $_var_9;
	} else {
		wp_die('缺少curl组件，请开启');
	}
}
function zib_get_replace_url($_var_10)
{
	$_var_11 = preg_replace('/^(?:https?:\\/\\/)?([^\\/]+).*$/im', '$1', $_var_10);
	return $_var_11;
}
function zib_is_authorization()
{
	return true;
}
function zib_is_local($_var_15 = '')
{
	if (!$_var_15) {
		$_var_15 = home_url();
	}
	if (stristr($_var_15, 'localhost') || stristr($_var_15, '127.') || stristr($_var_15, '192.')) {
		return true;
	}
	return false;
}
add_action('admin_notices', 'zib_authorization_notice');
function zib_authorization_notice()
{
	$_var_16 = apply_filters('zib_authorization_notice_msg', '当前主题还未授权，部分功能无法使用，请在主题设置中进行授权验证');
	$_var_17 = '<div class="notice notice-warning">
    <p style="' . 'color:#ff2f86' . '"><span class="' . 'dashicons-before dashicons-heart' . '"></span></p>
    <b>' . '欢迎使用Zibll子比主题' . '</b>
	<p>' . $_var_16 . '</p>
</div>';
	echo zib_is_authorization() ? '' : $_var_17;
}
function zib_authorization_input()
{
	$_var_18 = '<div id="authorization_form">
<div class="ok-icon"><svg class="icon" style="font-size: 1.2em;width: 1em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024"><path d="M880 502.3V317.1c0-34.9-24.4-66-60.8-77.4l-80.4-30c-37.8-14.1-73.4-32.9-105.7-55.7l-84.6-60c-19.2-15.2-47.8-15.2-67 0l-84.7 59.9c-32.3 22.8-67.8 41.6-105.7 55.7l-80.4 30c-36.4 11.4-60.8 42.5-60.8 77.4v185.2c0 123.2 63.9 239.2 172.5 313.2l158.5 108c20.2 13.7 47.9 13.7 68.1 0l158.5-108C816.1 741.6 880 625.5 880 502.3z" fill="#0DCEA7" p-id="17337"></path><path d="M150 317.1v3.8c13.4-27.6 30-53.3 49.3-76.7C169.4 258 150 286 150 317.1zM880 317.1c0-34.9-24.4-66-60.8-77.4l-43.5-16.2c57.7 60.6 95.8 140 104.2 228.1l0.1-134.5zM572.8 111.2L548.5 94c-19.2-15.2-47.8-15.2-67 0l-15.3 10.8c10-0.8 20.2-1.2 30.5-1.2 26 0.1 51.5 2.7 76.1 7.6zM496.7 873.9c-39.5 0-77.6-5.9-113.4-17l97.7 66.6c20.2 13.7 47.9 13.7 68.1 0l158.5-108c92.3-62.9 152.3-156.1 168.2-258.3C843.5 737.3 686 873.9 496.7 873.9z" fill="#0DCEA7" p-id="17338"></path><path d="M875.8 557.2c2.8-18.1 4.3-36.4 4.3-54.9v-50.8c-8.5-88.1-46.6-167.4-104.2-228.1L739 209.6c-37.8-14.1-73.4-32.9-105.7-55.7l-60.5-42.7c-24.6-4.9-50-7.5-76.1-7.5-10.3 0-20.4 0.4-30.5 1.2l-58.7 41.5c23.4-5.2 47.7-8 72.7-8 183.6 0 332.4 148.8 332.4 332.4S663.9 803 480.3 803c-170.8 0-311.5-128.9-330.2-294.7 2 121 65.6 234.5 172.4 307.2l60.8 41.4c35.9 11 74 17 113.4 17 189.3 0 346.8-136.6 379.1-316.7zM261.2 220.8l-50.4 18.8c-4 1.3-7.8 2.8-11.5 4.5-19.3 23.4-35.9 49.2-49.3 76.7v112.7c9.4-84.5 50.5-159.4 111.2-212.7z" fill="#1DD49C" p-id="17339"></path><path d="M480.3 803c183.6 0 332.4-148.8 332.4-332.4S663.9 138.3 480.3 138.3c-25 0-49.3 2.8-72.7 8l-10.7 7.6c-32.3 22.8-67.8 41.6-105.7 55.7l-30 11.2C200.5 274.1 159.4 349 150 433.6v68.8c0 2 0 4 0.1 6C168.8 674.1 309.5 803 480.3 803z m-16.4-630c154.4 0 279.6 125.2 279.6 279.6S618.3 732.2 463.9 732.2 184.3 607 184.3 452.6 309.5 173 463.9 173z" fill="#2DDB92" p-id="17340"></path><path d="M463.9 732.2c154.4 0 279.6-125.2 279.6-279.6S618.3 173 463.9 173 184.3 298.2 184.3 452.6s125.2 279.6 279.6 279.6z m-16.4-524.5c125.3 0 226.8 101.5 226.8 226.8S572.8 661.3 447.5 661.3 220.7 559.8 220.7 434.5s101.6-226.8 226.8-226.8z" fill="#3DE188" p-id="17341" data-spm-anchor-id="a313x.7781069.0.i7"></path><path d="M447.5 661.3c125.3 0 226.8-101.5 226.8-226.8S572.8 207.7 447.5 207.7 220.7 309.2 220.7 434.5s101.6 226.8 226.8 226.8z m-16.4-419c96.1 0 174 77.9 174 174s-77.9 174-174 174-174-77.9-174-174 77.9-174 174-174z" fill="#4CE77D" p-id="17342"></path><path d="M431.1 590.4c96.1 0 174-77.9 174-174s-77.9-174-174-174-174 77.9-174 174 77.9 174 174 174zM414.7 277c67 0 121.3 54.3 121.3 121.3s-54.3 121.3-121.3 121.3-121.3-54.3-121.3-121.3S347.8 277 414.7 277z" fill="#5CEE73" p-id="17343"></path><path d="M414.7 398.3m-121.3 0a121.3 121.3 0 1 0 242.6 0 121.3 121.3 0 1 0-242.6 0Z" fill="#6CF468" p-id="17344"></path><path d="M515 100.7c8.3 0 16.2 2.7 22.3 7.5l0.4 0.3 0.4 0.3 84.7 59.9c33.5 23.7 70.5 43.2 109.8 57.9l80.4 30 0.4 0.2 0.5 0.1c28.8 9.1 48.2 33.3 48.2 60.3v185.2c0 28.9-3.7 57.8-11.1 86-7.3 27.8-18.1 54.8-32.2 80.4-14.1 25.6-31.5 49.8-51.7 71.8-20.5 22.4-43.9 42.6-69.6 60.1L539 908.6c-6.8 4.6-15.3 7.2-23.9 7.2s-17.1-2.6-23.9-7.2l-158.5-108c-25.7-17.5-49.1-37.7-69.6-60.1-20.2-22-37.6-46.2-51.7-71.8-14.1-25.6-24.9-52.6-32.2-80.4-7.4-28.1-11.1-57-11.1-86V317.1c0-27 19.4-51.2 48.2-60.3l0.5-0.1 0.4-0.2 80.4-30c39.3-14.7 76.2-34.1 109.8-57.9l84.7-59.9 0.4-0.3 0.4-0.3c5.9-4.8 13.9-7.4 22.1-7.4m0-18c-11.9 0-23.9 3.8-33.5 11.4L396.8 154c-32.3 22.8-67.8 41.6-105.7 55.7l-80.4 30c-36.4 11.4-60.8 42.5-60.8 77.4v185.2c0 123.2 63.9 239.2 172.5 313.2l158.5 108c10.1 6.9 22.1 10.3 34 10.3 12 0 24-3.4 34-10.3l158.5-108c108.6-74 172.5-190 172.5-313.2V317.1c0-34.9-24.4-66-60.8-77.4l-80.4-30c-37.8-14.1-73.4-32.9-105.7-55.7l-84.5-60c-9.6-7.5-21.5-11.3-33.5-11.3z" fill="#0EC69A" p-id="17345"></path><path d="M688.8 496.7V406c0-17.1-11.6-32.3-28.9-37.9l-38.3-14.7c-18-6.9-35-16.1-50.3-27.3L531 296.8c-9.1-7.4-22.8-7.4-31.9 0l-40.3 29.3a218.45 218.45 0 0 1-50.3 27.3l-38.3 14.7c-17.3 5.6-28.9 20.8-28.9 37.9v90.7c0 60.3 30.4 117.1 82.1 153.3l75.5 52.9c9.6 6.7 22.8 6.7 32.4 0l75.5-52.9c51.6-36.2 82-93 82-153.3z" fill="#9CFFBD" p-id="17346"></path><path d="M325.6 287.5c-7.2 0-14.1-4.4-16.8-11.6-3.5-9.3 1.1-19.7 10.4-23.2 68.5-26.2 110.5-60.3 110.9-60.6 7.7-6.3 19-5.2 25.3 2.5s5.2 19-2.5 25.3c-1.9 1.5-47 38.2-120.9 66.4-2.1 0.8-4.2 1.2-6.4 1.2z" fill="#FFFFFF" p-id="17347"></path><path d="M260.2 311.7c-7.3 0-14.2-4.5-16.9-11.7-3.5-9.3 1.3-19.7 10.6-23.1l10.5-3.9c9.3-3.5 19.7 1.3 23.1 10.6 3.5 9.3-1.3 19.7-10.6 23.1l-10.5 3.9c-2.1 0.7-4.2 1.1-6.2 1.1z" fill="#FFFFFF" p-id="17348"></path></svg></div>

<p>请输入购买主题时获取的授权码：</p>
<input type="text" au_name="authorization_code" value="" class="regular-text" />
<input type="hidden" au_name="authorization" value="authorization" />
<input type="hidden" au_name="action" value="zib_authorization" />
<a id="authorization_submit" class="but c-blue">开启正版授权</a>
</div>';
	if (zib_is_local()) {
		$_var_18 = '<div class="notice notice-success" style="padding:10px;color:#666"><b>您当前正处于本地环境，暂不用授权，请忽略顶部提示框</b></div>';
	}
	if (!zib_is_authorization()) {
		$_var_18 = '<div class="authorization-ok" id="authorization_form">
    <div class="ok-icon"><svg t="1585712312243" class="icon" style="width: 1em; height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="3845" data-spm-anchor-id="a313x.7781069.0.i0"><path d="M115.456 0h793.6a51.2 51.2 0 0 1 51.2 51.2v294.4a102.4 102.4 0 0 1-102.4 102.4h-691.2a102.4 102.4 0 0 1-102.4-102.4V51.2a51.2 51.2 0 0 1 51.2-51.2z m0 0" fill="#FF6B5A" p-id="3846"></path><path d="M256 13.056h95.744v402.432H256zM671.488 13.056h95.744v402.432h-95.744z" fill="#FFFFFF" p-id="3847"></path><path d="M89.856 586.752L512 1022.72l421.632-435.2z m0 0" fill="#6DC1E2" p-id="3848"></path><path d="M89.856 586.752l235.52-253.952h372.736l235.52 253.952z m0 0" fill="#ADD9EA" p-id="3849"></path><path d="M301.824 586.752L443.136 332.8h137.216l141.312 253.952z m0 0" fill="#E1F9FF" p-id="3850"></path><path d="M301.824 586.752l209.92 435.2 209.92-435.2z m0 0" fill="#9AE6F7" p-id="3851"></path></svg></div>
    <p style=" color: #0087e8; font-size: 15px; "><svg class="icon" style="width: 1em;height: 1em;vertical-align: -.2em;fill: currentColor;overflow: hidden;font-size: 1.4em;" viewBox="0 0 1024 1024"><path d="M492.224 6.72c11.2-8.96 26.88-8.96 38.016 0l66.432 53.376c64 51.392 152.704 80.768 243.776 80.768 27.52 0 55.104-2.624 81.92-7.872a30.08 30.08 0 0 1 24.96 6.4 30.528 30.528 0 0 1 11.008 23.424V609.28c0 131.84-87.36 253.696-228.288 317.824L523.52 1021.248a30.08 30.08 0 0 1-24.96 0l-206.464-94.08C151.36 862.976 64 741.12 64 609.28V162.944a30.464 30.464 0 0 1 36.16-29.888 425.6 425.6 0 0 0 81.92 7.936c91.008 0 179.84-29.504 243.712-80.768z m19.008 62.528l-47.552 38.208c-75.52 60.8-175.616 94.144-281.6 94.144-19.2 0-38.464-1.024-57.472-3.328V609.28c0 107.84 73.92 208.512 192.768 262.72l193.856 88.384 193.92-88.384c118.912-54.208 192.64-154.88 192.64-262.72V198.272a507.072 507.072 0 0 1-57.344 3.328c-106.176 0-206.144-33.408-281.728-94.08l-47.488-38.272z m132.928 242.944c31.424 0 56.832 25.536 56.832 56.832H564.544v90.944h121.92a56.448 56.448 0 0 1-56.384 56.384H564.48v103.424h150.272a56.832 56.832 0 0 1-56.832 56.832H365.056a56.832 56.832 0 0 1-56.832-56.832h60.608v-144c0-33.92 27.52-61.44 61.44-61.44v205.312h71.68V369.024H324.8c0-31.424 25.472-56.832 56.832-56.832z" p-id="4799"></path></svg> 恭喜您! 已完成授权</p>    
    <input type="hidden" au_name="authorization" value="authorization" />
    <input type="hidden" au_name="action" value="zib_delete_authorization" />
    <a id="authorization_submit" class="but c-red">撤销授权</a>
    </div>';
	}
	echo $_var_18;
}
add_action('zib_footer_conter', 'zib_footer_con_noaut', 20, 11);
function zib_footer_con_noaut()
{
	if (!zib_is_local() && !zib_is_authorization()) {
		echo '<a class="but c-blue" data-toggle="tooltip" title="完成主题授权后，此处内容会自动消失" target="_blank" href="https://zibll.com">本站主题由Zibll主题强力驱动</a><a class="but c-red ml10" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=770349780&site=qq&menu=yes">联系作者</a>';
	}
}
add_filter('of_save_options_error', 'zib_of_save_options_error');
function zib_of_save_options_error($_var_19)
{
	if (zib_is_local() || zib_is_authorization()) {
		return $_var_19;
	}
	return '主题未授权，暂时无法保存';
}
add_filter('of_validate_options', 'zib_of_validate_options');
function zib_of_validate_options($_var_20)
{
	if (zib_is_local() || zib_is_authorization()) {
		return $_var_20;
	}
	$_var_21 = array();
	$_var_22 = get_option_framework_name();
	$_var_21 = get_option($_var_22);
	return $_var_21;
}
add_action('admin_head', 'zib_admin_head');
function zib_admin_head()
{
	if (zib_is_local() || zib_is_authorization()) {
		return;
	}
	echo '<script type="text/javascript">
   jQuery(document).ready(function($) {
    $("body").on("click", "#optionsframework-submit input,.page-title-action.hide-if-no-customize,.hide-if-no-customize a", function () {
        var r= confirm( "当前主题还未授权，暂时无法使用此功能。开始授权验证？" );
        if (r==true)
        {
            self.location.href="' . of_get_menuurl('options-group-22-tab') . '";
            $("#options-group-22-tab").click();
        }
        return false;
    })
});
    </script>';
}
add_action('of_optionsframework_page_submit', 'zib_of_optionsframework_page_submit');
function zib_of_optionsframework_page_submit($_var_23)
{
	if (zib_is_local() || zib_is_authorization()) {
		return $_var_23;
	}
	return '<input type="submit" class="button-bc" name="update" value="请先进行主题授权">';
}
add_action('admin_head', 'zib_theme_update_authorization');
function zib_theme_update_authorization()
{
	$_var_24 = new Options_Framework();
	$_var_25 = $_var_24->get_option_name();
	$_var_26 = wp_get_theme();
	$_var_27 = $_var_26['Version'];
	if (version_compare(get_option($_var_25 . '_version'), $_var_27) == -1) {
		zib_init_theme_authorization();
	}
}
function zib_init_theme_authorization()
{
	$_var_28 = get_option('post_autkey');
	$_var_28 = $_var_28 ? $_var_28 : get_option('zibll_authorization');
	if (!$_var_28) {
		return;
	}
	$_var_29 = $_var_28['authorization_time'];
	$_var_30 = date('Y-m-d H:i:s');
	$_var_31 = floor((strtotime($_var_30) - strtotime($_var_29)) / 3600);
	if ($_var_31 < 48) {
		return;
	}
	$_var_32 = array('authorization_code' => base64_decode($_var_28['authorization_code']));
	$_var_33 = zib_curl_get_authorization($_var_32);
	if (isset($_var_33['result']) && $_var_33['result'] == false) {
		zib_curl_delete_authorization();
		add_filter('zib_authorization_notice_msg', 'zib_auth_fail_again_notice_msg');
	}
}
function zib_auth_fail_again_notice_msg()
{
	return '主题授权数据出错，请重新授权';
}
function zib_is_update()
{
	$_var_34 = 0;
	$_var_35 = wp_get_theme();
	$_var_36 = get_option('zibll_new_version');
	if (!$_var_36) {
		$_var_36 = zib_curl_get_update_data();
	}
	$_var_37 = $_var_36['get_time'];
	$_var_38 = date('Y-m-d H:i:s');
	$_var_39 = floor((strtotime($_var_38) - strtotime($_var_37)) / 3600);
	if ($_var_39 > 168) {
		$_var_36 = zib_curl_get_update_data();
	}
	if (!empty($_var_36['skip'])) {
		return false;
	}
	if ($_var_36['version'] && version_compare($_var_35['Version'], $_var_36['version']) == -1) {
		return $_var_36;
	}
	return false;
}
function zib_curl_get_update_data()
{
	$_var_40 = wp_get_theme();
	$_var_41 = $_var_40['Version'];
	$_var_42 = array('version' => $_var_41, 'product_name_code' => 'zibll_theme', 'url' => zib_get_replace_url(home_url()));
	$_var_43 = 'https://api.zibll.com/api/update';
	$_var_44 = zib_curl_post($_var_43, $_var_42);
	$_var_45 = unserialize($_var_44);
	$_var_46 = array('version' => '', 'result' => false, 'skip' => false, 'update_description' => '', 'update_content' => '', 'download_url' => '', 'update_time' => '', 'get_time' => date('Y-m-d H:i:s'));
	if ($_var_45['result']) {
		$_var_46 = array('version' => $_var_45['version'], 'skip' => false, 'result' => $_var_45['result'], 'update_description' => $_var_45['update_description'], 'update_content' => $_var_45['update_content'], 'download_url' => $_var_45['download_url'], 'update_time' => $_var_45['update_time'], 'get_time' => date('Y-m-d H:i:s'));
	}
	update_option('zibll_new_version', $_var_46);
	return $_var_46;
}
function zib_skip_this_update()
{
	$_var_47 = get_option('zibll_new_version');
	if ($_var_47) {
		$_var_47['skip'] = true;
		update_option('zibll_new_version', $_var_47);
	}
}
function zib_update_input()
{
	$_var_48 = zib_is_update();
	$_var_49 = wp_get_theme();
	$_var_50 = $_var_49['Version'];
	$_var_51 = '<div class="notice notice-info" id="update-form">
	<h3>当前主题已经是最新版啦 <span class="dashicons dashicons-smiley"></span></h3>
	<p><b>当前主题版本：V' . wp_get_theme()['Version'] . ' </b></p>
	<p><a id="update-submit" href="javascript:;" class="but jb-blue"> 检测更新</a>	<span class="update-notice"></span></p>
	<input type="hidden" au_name="action" value="zib_get_update" />
</div>';
	if ($_var_48) {
		$_var_51 = '<div class="notice notice-info" id="update-form">
    <p style="' . 'color:#ff2f86' . '"><span class="dashicons-before dashicons-cloud"></span></p>
    <p>
    <b>当前主题版本：V' . $_var_50 . '，可更新到最新版本：<code style=" color: #ff1919; background: #fbeeee; font-size: 16px; ">V' . $_var_48['version'] . '</code></b>
	<input type="hidden" au_name="action" value="zib_skip_update" />
    </p>
    ' . ($_var_48['update_description'] ? '<p>' . $_var_48['update_description'] . '</p>' : '') . '
    <p><a id="update-submit" href="javascript:;" class="but jb-yellow">忽略此次更新</a></p>
</div>';
		$_var_51 .= '<div class="title-theme"><b>更新日志</b></div>';
		$_var_51 .= '<div class="box-theme">';
		$_var_51 .= $_var_48['update_content'];
		$_var_51 .= '</div>';
	}
	echo $_var_51;
}
if (zib_is_update()) {
	add_action('admin_notices', 'zib_update_notice');
}
function zib_update_notice()
{
	$_var_52 = zib_is_update();
	$_var_53 = wp_get_theme();
	$_var_54 = $_var_53['Version'];
	$_var_55 = '<div class="notice notice-info is-dismissible">
    <p style="' . 'color:#ff2f86' . '"><span class="dashicons-before dashicons-cloud"></span></p>
    <b>Zibll子比主题：</b>检测到主题更新
	<p>当前主题版本：V' . $_var_54 . '，可更新到最新版本：V' . $_var_52['version'] . ' 。  <a href="' . of_get_menuurl('options-group-20-tab') . '">查看更新日志</a></p>	
	' . ($_var_52['update_description'] ? '<p>' . $_var_52['update_description'] . '</p>' : '') . '
</div>';
	echo $_var_55;
}