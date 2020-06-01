<?php

/**
 * 子比主题支付系统
 * 下载功能相关
 */

function zibpay_get_edit_posts_meta_download()
{

    $con = '<div>';
    $con .= '<a href="javascript:;" class="zibpay-add-media button">上传媒体文件</a> <a href="javascript:;" class="zibpay-add-file button button-primary">上传本地文件</a> <span id="file-progress"></span>';
    $con .= '<script>
        zibpay = {
            ajax_url: "' . admin_url('admin-ajax.php') . '"
        }
</script>';
    $con .= '</div>';
    return $con;
}

function zibpay_get_edit_posts_meta_doc()
{
    $con = '<div>';
    $con .= '<li><qc style="color:#fb2121;background:undefined">付费阅读</qc>功能需要配合<qc style="color:#fb2121;background:undefined">短代码</qc>或者古腾堡<qc style="color:#fb2121;background:undefined">付费阅读块</qc>使用 </li><li>古腾堡编辑器：添加块-zibll主题模块-付费阅读块 </li><li>经典编辑器：插入短代码： [payshow] 隐藏内容 [/payshow] </li><li><a href="https://www.zibll.com/580.html" target="_blank" rel="noreferrer noopener" aria-label="查看详细教程（在新窗口打开）">查看详细教程</a></li>';
    $con .= '</div>';
    return $con;
}

function zibpay_get_edit_posts_meta_download_help()
{
    $con = '<div>';

    $con .= '<div>一行代表一个按钮，每一行用竖线 <qc style="color:#fb2121;">|</qc> 分割内容（顺序不能错）</div>';
    $con .= '<div>链接支持本地相对地址、资源地址、或任意跳转地址</div>';
    $con .= '<div>每一行的格式：</div>';
    $con .= '<div><qc style="color:#fb2121;">链接</qc> | <qc style="color:#fb2121;">按钮显示的文字</qc> | <qc style="color:#fb2121;">更多内容</qc></div>';
    $con .= '<div>颜色代码请填写 1-5 的数字，分别为：红、橙、蓝、绿、紫</div>';
    $con .= '<div>可自动识别常用网盘并匹配颜色和按钮文字</div>';
    $con .= '<div>例如：</div>';
    $con .= '<div><qc style="color:#216efb;">wp-content/uploads/zibpaydown/a2d6e86fdf47.jpg|本地下载|这里是简介|1</qc></div>';
    $con .= '<div><qc style="color:#6d14fa;">https://pan.baidu.com/download|百度网盘|提取密码：XXXX|2</qc></div>';
    $con .= '<div><qc style="color:#216efb;">https://www.lanzou.com|蓝奏云|解压密码：0000</qc></div>';
    $con .= '<div><qc style="color:#6d14fa;">https://docs.qq.com/doc/DQUlVeWtDdUdad3B2| |3</qc></div>';

    $con .= '</div>';
    return $con;
}

function zibpay_get_post_down_buts($pay_mate,$post_id = '')
{
    if(empty($pay_mate['pay_download'])) return '<div class="muted-2-color text-center">暂无可下载资源</div>';

    $down = zibpay_get_post_down_array($post_id);
    $con = '';
    $down_but = '';
    foreach($down as $key => $down_v){
        $down_link = get_template_directory_uri() . '/zibpay/download.php?post_id='.$down_v['post_id'].'&amp;down_id='.$key.'&amp;key='.substr(md5(date("YmdHis")), 0, 10);
        $down_name = $down_v['name'] ? $down_v['name'] :'本地下载';
        $down_more = $down_v['more'] ? '<sapn class="but">'.$down_v['more'].'</span>':'';
        $icon = '<i class="fa fa-download mr10" aria-hidden="true"></i>';
        $class_obj = array('b-theme','b-red','b-yellow','b-blue','b-green','b-purple');

        $class = !empty($class_obj[$down_v['class']]) ? $class_obj[$down_v['class']] : 'b-theme';

        if(stripos($down_v['link'],'baidu')){
            $class.= ' baidu';
            $down_name = $down_v['name'] ? $down_v['name'] :'百度网盘';
            $icon = zib_svg('pan_baidu');
        }
        if(stripos($down_v['link'],'weiyun')||stripos($down_v['link'],'qq')){
            $class.= ' weiyun';
            $down_name = $down_v['name'] ? $down_v['name'] :'腾讯微云';
            $icon = zib_svg('weiyun','0 0 1400 1024');
        }
        if(stripos($down_v['link'],'lanzou')){
            $down_name = $down_v['name'] ? $down_v['name'] :'蓝奏云';
            $class.= ' lanzou';
            $icon = zib_svg('lanzou');
        }
        if(stripos($down_v['link'],'onedrive')||stripos($down_v['link'],'sharepoint')){
            $down_name = $down_v['name'] ? $down_v['name'] :'OneDrive';
            $class.= ' onedrive';
            $icon = zib_svg('onedrive');
        }
        if(stripos($down_v['link'],'.189.')){
            $down_name = $down_v['name'] ? $down_v['name'] :'天翼云';
            $class.= ' tianyi';
            $icon = zib_svg('tianyi');
        }
        if(stripos($down_v['link'],'ctfile')){
            $down_name = $down_v['name'] ? $down_v['name'] :'城通网盘';
            $class.= ' ctfile';
            $icon = zib_svg('ctfile','0 0 1260 1024');
        }

        $down_but .= '<div class="but-download"><a target="_blank" href="'.$down_link.'" class="mr10 but '.$class.'">'.$icon.$down_name.'</a>'.$down_more.'</div>';
    }
    if(!$down_but) return '<div class="muted-2-color text-center">暂无可下载资源</div>';

    $con .= '<div>';
    $con .= $down_but;
    $con .= '</div>';

    return $con;
}

/**对链接进行数组处理 */
function zibpay_get_post_down_array($post_id='')
{
    if(!$post_id){
        global $post;
	    $post_id = $post->ID;
    }
    $pay_mate =  get_post_meta($post_id, 'posts_zibpay', true);

    $down = explode("\r\n", $pay_mate['pay_download']);
    $down_obj = array();
    $ii = 1;
    foreach($down as $down_v){
        //如果没有链接则跳出
        $down_v = explode("|", $down_v);
        if(empty($down_v[0])) continue;
        $down_obj[$ii] = array(
            'link' => $down_v[0],
            'post_id' => $post_id,
            'name' => !empty($down_v[1]) ? $down_v[1] : '',
            'more' => !empty($down_v[2]) ? $down_v[2] : '',
            'class' => !empty($down_v[3]) ? $down_v[3] : '',
        );
        $ii ++;
    }
    return $down_obj;
}
/**引入编辑资源文件 */
function zibpay_edit_posts_meta_enqueue()
{
    wp_enqueue_style( 'zibpay_edit', get_template_directory_uri() . '/zibpay/assets/css/posts-meta-edit.css');
    wp_enqueue_script('jquery_form', get_template_directory_uri() . '/zibpay/assets/js/jquery.form.js', array('jquery'));
    wp_enqueue_script('zibpay_edit', get_template_directory_uri() . '/zibpay/assets/js/posts-meta-edit.js', array('jquery', 'jquery_form'));
}

add_action('add_meta_boxes', 'zibpay_edit_posts_meta_enqueue');

/**挂钩AJAX上传文件函数 */
function zibpay_edit_posts_file_upload()
{
    //echo json_encode($_FILES);
    if (is_uploaded_file($_FILES['zibpayFile']['tmp_name']) && is_user_logged_in() && current_user_can('publish_posts')) {
        $vname = $_FILES['zibpayFile']['name'];
        if ($vname != "") {
            $filename = substr(md5(date("YmdHis")), 0, 10) . mt_rand(11, 99) . strrchr($vname, '.');
            //上传路径
            $upfile = WP_CONTENT_DIR.'/uploads/zibpaydown/';
            if (!file_exists($upfile)) {
                mkdir($upfile, 0777, true);
            }
            $file_path = WP_CONTENT_DIR.'/uploads/zibpaydown/' . $filename;
            if (move_uploaded_file($_FILES['zibpayFile']['tmp_name'], $file_path)) {
                echo home_url() . '/wp-content/uploads/zibpaydown/' . $filename;
                exit;
            }
        }
    }
}

add_action('wp_ajax_zibpay_file_upload', 'zibpay_edit_posts_file_upload');
