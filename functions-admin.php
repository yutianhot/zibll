<?php
//给文章分类添加封面图像
$dir = get_bloginfo('template_directory');
if (!defined('Z_PLUGIN_URL')) define('Z_PLUGIN_URL', untrailingslashit(plugins_url('', __FILE__)));

add_action('admin_init', 'z_init');
function z_init()
{
    $z_taxonomies = get_taxonomies();
    if (is_array($z_taxonomies)) {
        foreach ($z_taxonomies as $z_taxonomy) {
            add_action($z_taxonomy . '_add_form_fields', 'z_add_texonomy_field');
            add_action($z_taxonomy . '_edit_form_fields', 'z_edit_texonomy_field');
            add_filter('manage_edit-' . $z_taxonomy . '_columns', 'z_taxonomy_columns');
            add_filter('manage_' . $z_taxonomy . '_custom_column', 'z_taxonomy_column', 10, 3);
        }
    }
}

function z_add_style()
{
    echo '<style type="text/css" media="screen">
		th.column-thumb {width:60px;}
		.form-field img.taxonomy-image,.taxonomy-image{width:95%;max-width:500px;max-height:300px;}
		.inline-edit-row fieldset .thumb label span.title {display:inline-block;}
		.column-thumb span {display:inline-block;}
		.inline-edit-row fieldset .thumb img,.column-thumb img {width:55px;height:28px;}
	</style>';
}

// 添加分类时候的添加图像
function z_add_texonomy_field()
{
    if (get_bloginfo('version') >= 3.5)
        wp_enqueue_media();
    else {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');
    }
    echo '<div class="form-field">
		<label for="taxonomy_image">' . __('封面图像', 'zci') . '</label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="" />
        <br/>
        <p>设置封面图，建议尺寸为1000x400,如果分类页未开启侧边栏，请选择更大的尺寸，需要在主题设置-分类、标签页：开启分类、标签封面显示功能</p>
		<button class="z_upload_image_button button">' . __('上传/添加图像', 'zci') . '</button>
	</div>' . z_script();
}

// 编辑分类时候的添加图像
function z_edit_texonomy_field($taxonomy)
{
    if (get_bloginfo('version') >= 3.5)
        wp_enqueue_media();
    else {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');
    }
    if (zib_get_taxonomy_img_url($taxonomy->term_id, NULL, TRUE) == Z_IMAGE_PLACEHOLDER)
        $image_text = "";
    else
        $image_text = zib_get_taxonomy_img_url($taxonomy->term_id, NULL, TRUE);
    echo '<tr class="form-field">
		<th scope="row" valign="top"><label for="taxonomy_image">' . __('图像', 'zci') . '</label></th>
		<td><img class="taxonomy-image" src="' . zib_get_taxonomy_img_url($taxonomy->term_id, NULL, TRUE) . '"/><br/><input type="text" name="taxonomy_image" id="taxonomy_image" value="' . $image_text . '" /><br />
        <p>设置封面图，建议尺寸为1000x400,如果分类页未开启侧边栏，请选择更大的尺寸，需要在主题设置-分类、标签页：开启分类、标签封面显示功能</p>
        <button class="z_upload_image_button button">' . __('上传/添加图像', 'zci') . '</button>
		<button class="z_remove_image_button button">' . __('删除图像', 'zci') . '</button>
		</td>
	</tr>' . z_script();
}
// 上传按钮的js函数
function z_script()
{
    return '<script type="text/javascript">
	    jQuery(document).ready(function($) {
			var wordpress_ver = "' . get_bloginfo("version") . '", upload_button;
			$(".z_upload_image_button").click(function(event) {
				upload_button = $(this);
				var frame;
				if (wordpress_ver >= "3.5") {
					event.preventDefault();
					if (frame) {
						frame.open();
						return;
					}
					frame = wp.media();
					frame.on( "select", function() {
						// Grab the selected attachment.
						var attachment = frame.state().get("selection").first();
						frame.close();
						if (upload_button.parent().prev().children().hasClass("tax_list")) {
							upload_button.parent().prev().children().val(attachment.attributes.url);
							upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
						}
						else
							$("#taxonomy_image").val(attachment.attributes.url);
					});
					frame.open();
				}
				else {
					tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
					return false;
				}
			});
			
			$(".z_remove_image_button").click(function() {
				$("#taxonomy_image").val("");
				$(this).parent().siblings(".title").children("img").attr("src","' . Z_IMAGE_PLACEHOLDER . '");
				$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				return false;
			});
			
			if (wordpress_ver < "3.5") {
				window.send_to_editor = function(html) {
					imgurl = $("img",html).attr("src");
					if (upload_button.parent().prev().children().hasClass("tax_list")) {
						upload_button.parent().prev().children().val(imgurl);
						upload_button.parent().prev().prev().children().attr("src", imgurl);
					}
					else
						$("#taxonomy_image").val(imgurl);
					tb_remove();
				}
			}
			
			$(".editinline").live("click", function(){  
			    var tax_id = $(this).parents("tr").attr("id").substr(4);
			    var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");
				if (thumb != "' . Z_IMAGE_PLACEHOLDER . '") {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val(thumb);
				} else {
					$(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
				}
				$(".inline-edit-col .title img").attr("src",thumb);
			    return false;  
			});  
	    });
	</script>';
}

// 保存函数
add_action('edit_term', 'z_save_taxonomy_image');
add_action('create_term', 'z_save_taxonomy_image');
function z_save_taxonomy_image($term_id)
{
    if (isset($_POST['taxonomy_image']))
        update_option('_taxonomy_image_' . $term_id, $_POST['taxonomy_image']);
}

// 根据图片链接获取图片ID
function z_get_attachment_id_by_url($image_src)
{
    global $wpdb;
    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid = '$image_src'";
    $id = $wpdb->get_var($query);
    return (!empty($id)) ? $id : NULL;
}

function z_quick_edit_custom_box($column_name, $screen, $name)
{
    if ($column_name == 'thumb')
        echo '<fieldset>
		<div class="thumb inline-edit-col">
			<label>
				<span class="title"><img src="" alt="Thumbnail"/></span>
				<span class="input-text-wrap"><input type="text" name="taxonomy_image" value="" class="tax_list" /></span>
                <span class="input-text-wrap">
                <p>设置封面图，建议尺寸为1000x400,如果分类页未开启侧边栏，请选择更大的尺寸，需要在主题设置-分类、标签页：开启分类、标签封面显示功能</p>
					<button class="z_upload_image_button button">' . __('上传/添加图像', 'zci') . '</button>
					<button class="z_remove_image_button button">' . __('删除图像', 'zci') . '</button>
				</span>
			</label>
		</div>
	</fieldset>';
}

function z_taxonomy_columns($columns)
{
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['thumb'] = __('图像', 'zci');

    unset($columns['cb']);

    return array_merge($new_columns, $columns);
}

function z_taxonomy_column($columns, $column, $id)
{
    if ($column == 'thumb')
        $columns = '<span><img src="' . zib_get_taxonomy_img_url($id, NULL, TRUE) . '" alt="' . __('Thumbnail', 'zci') . '" class="wp-post-image" /></span>';
    return $columns;
}

// change 'insert into post' to 'use this image'
function z_change_insert_button_text($safe_text, $text)
{
    return str_replace("Insert into Post", "Use this image", $text);
}

// style the image in category list
add_action('admin_head', 'z_add_style');

if (strpos($_SERVER['SCRIPT_NAME'], 'edit-tags.php')) {
    add_action('quick_edit_custom_box', 'z_quick_edit_custom_box', 10, 3);
    add_filter("attribute_escape", "z_change_insert_button_text", 10, 2);
}


// 上传文件自动重命名
if (_pz('newfilename') && !function_exists('_new_filename')) :
    function _new_filename($filename)
    {
        $time = date("YmdHis");
        $info = pathinfo($filename);
        $ext = empty($info['extension']) ? '' : '.' . $info['extension'];
        $name = basename($filename, $ext);
        return substr(md5($name), 0, 12) . $ext;
    }
    add_filter('sanitize_file_name', '_new_filename', 10);

endif;

// editor style
add_editor_style(get_locale_stylesheet_uri() . '/css/editor-style.css');

// 后台Ctrl+Enter提交评论回复
add_action('admin_footer', '_admin_comment_ctrlenter');
function _admin_comment_ctrlenter()
{
    echo '<script type="text/javascript">
        jQuery(document).ready(function($){
            $("textarea").keypress(function(e){
                if(e.ctrlKey&&e.which==13||e.which==10){
                    $("#replybtn").click();
                }
            });
        });
    </script>';
};


// 禁用WP Editor Google字体css
function zib_remove_gutenberg_styles($translation, $text, $context, $domain)
{
    if($context != 'Google Font Name and Variants' || $text != 'Noto Serif:400,400i,700,700i') {
        return $translation;
    }
    return 'off';
}
add_filter( 'gettext_with_context', 'zib_remove_gutenberg_styles',10, 4);
// 古腾堡编辑器扩展
function zibll_block()
{
    wp_register_script(
        'zibll_block',
        get_stylesheet_directory_uri() . '/js/gutenberg-extend.js',
        array('wp-blocks', 'wp-element', 'wp-rich-text')
    );

    wp_register_style(
        'zibll_block',
        get_stylesheet_directory_uri() . '/css/editor-style.css',
        array('wp-edit-blocks')
    );

    wp_register_style(
        'font_awesome',
        get_stylesheet_directory_uri() . '/css/font-awesome.min.css',
        array('wp-edit-blocks')
    );

    register_block_type('zibll/block', array(
        'editor_script' => 'zibll_block',
        'editor_style'  => ['zibll_block', 'font_awesome'],
    ));
}

if (function_exists('register_block_type')) {
    add_action('init', 'zibll_block');
    add_filter('block_categories', function ($categories, $post) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug' => 'zibll_block_cat',
                    'title' => __('Zibll主题模块', 'zibll-blocks'),
                ),
            )
        );
    }, 10, 2);
}



/*
 * SEO
 * ====================================================
*/

class __Tax_Cat
{

    function __construct()
    {
        add_action('category_add_form_fields', array($this, 'add_tax_field'));
        add_action('category_edit_form_fields', array($this, 'edit_tax_field'));
        add_action('topics_add_form_fields', array($this, 'add_tax_field'));
        add_action('topics_edit_form_fields', array($this, 'edit_tax_field'));

        add_action('edit_term',  array($this, 'save_tax_meta'), 10, 2);
        add_action('create_term', array($this, 'save_tax_meta'), 10, 2);

    }

    public function add_tax_field()
    {
        echo '
        <div class="form-field">
            <label for="term_meta[title]">SEO 标题</label>
            <input type="text" name="term_meta[title]" id="term_meta[title]" />
        </div>
        <div class="form-field">
            <label for="term_meta[keywords]">SEO 关键字keywords）（用英文逗号分开）</label>
            <input type="text" name="term_meta[keywords]" id="term_meta[keywords]" />
        </div>
        <div class="form-field">
            <label for="term_meta[keywords]">SEO 描述（description）</label>
            <textarea name="term_meta[description]" id="term_meta[description]" rows="4" cols="40"></textarea>
        </div>
        ';
    }

    public function edit_tax_field($term)
    {

        $term_id = $term->term_id;
        $term_meta = get_option("_taxonomy_meta_$term_id");

        $meta_title = isset($term_meta['title']) ? $term_meta['title'] : '';
        $meta_keywords = isset($term_meta['keywords']) ? $term_meta['keywords'] : '';
        $meta_description = isset($term_meta['description']) ? $term_meta['description'] : '';

        echo '
      <tr class="form-field">
        <th scope="row">
            <label for="term_meta[title]">SEO 标题</label>
            <td>
                <input type="text" name="term_meta[title]" id="term_meta[title]" value="' . $meta_title . '" />
            </td>
        </th>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="term_meta[keywords]">SEO 关键字（keywords）</label>
            <td>
                <input type="text" name="term_meta[keywords]" id="term_meta[keywords]" value="' . $meta_keywords . '" />
            </td>
        </th>
    </tr>
    <tr class="form-field">
        <th scope="row">
            <label for="term_meta[description]">SEO 描述（description）</label>
            <td>
                <textarea name="term_meta[description]" id="term_meta[description]" rows="4">' . $meta_description . '</textarea>
            </td>
        </th>
    </tr>
    ';
    }

    public function save_tax_meta($term_id)
    {

        if (isset($_POST['term_meta'])) {

            $term_meta = array();

            $term_meta['title'] = isset($_POST['term_meta']['title']) ? esc_sql($_POST['term_meta']['title']) : '';
            $term_meta['keywords'] = isset($_POST['term_meta']['keywords']) ? esc_sql($_POST['term_meta']['keywords']) : '';
            $term_meta['description'] = isset($_POST['term_meta']['description']) ? esc_sql($_POST['term_meta']['description']) : '';

            update_option("_taxonomy_meta_$term_id", $term_meta);
        }
    }
}
if (_pz('post_keywords_description_s')) {
    $tax_cat = new __Tax_Cat();
}




$postmeta_keywords_description = array(

    array(
        "name" => "title",
        "std" => "",
        "description" => "",
        "title" => __('SEO标题（title）', 'zib_language') . '：'
    ),
    array(
        "name" => "keywords",
        "std" => "",
        "description" => "",
        "title" => __('SEO关键字（keywords）（用英文逗号分开）', 'zib_language') . '：'
    ),
    array(
        "name" => "description",
        "std" => "",
        "description" => "",
        "title" => __('SEO描述（description）', 'zib_language') . '：'
    )
);

if (_pz('post_keywords_description_s')) {
    add_action('admin_menu', '_postmeta_keywords_description_create');
    add_action('save_post', '_postmeta_keywords_description_save');
}

function _postmeta_keywords_description()
{
    global $post, $postmeta_keywords_description;
    foreach ($postmeta_keywords_description as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if ($meta_box_value == "")
            $meta_box_value = $meta_box['std'];
        echo '<p>' . $meta_box['title'] . '</p>';
        if ($meta_box['name'] == 'description') {
            echo '<p><textarea style="width:98%" name="' . $meta_box['name'] . '">' . $meta_box_value . '</textarea></p>';
        } else {
            echo '<p><input type="text" style="width:98%" value="' . $meta_box_value . '" name="' . $meta_box['name'] . '"></p>';
        }
    }

    echo '<input type="hidden" name="post_newmetaboxes_noncename" id="post_newmetaboxes_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
}

function _postmeta_keywords_description_create()
{
    global $theme_name;
    if (function_exists('add_meta_box')) {
        add_meta_box('postmeta_keywords_description_boxes', __('SEO设置', 'zib_language'), '_postmeta_keywords_description', 'post', 'normal', 'high');
        add_meta_box('postmeta_keywords_description_boxes', __('SEO设置', 'zib_language'), '_postmeta_keywords_description', 'page', 'normal', 'high');
    }
}

function _postmeta_keywords_description_save($post_id)
{
    global $postmeta_keywords_description;

    if (!wp_verify_nonce(isset($_POST['post_newmetaboxes_noncename']) ? $_POST['post_newmetaboxes_noncename'] : '', plugin_basename(__FILE__)))
        return;

    if (!current_user_can('edit_posts', $post_id))
        return;

    foreach ($postmeta_keywords_description as $meta_box) {
        $data = isset($_POST[$meta_box['name']]) ? $_POST[$meta_box['name']] : '';
        if (get_post_meta($post_id, $meta_box['name']) == "")
            add_post_meta($post_id, $meta_box['name'], $data, true);
        elseif ($data != get_post_meta($post_id, $meta_box['name'], true))
            update_post_meta($post_id, $meta_box['name'], $data);
        elseif ($data == "")
            delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
    }
}

//文章扩展
$postmeta_subtitle = array(
    array(
        "name" => "subtitle",
        'type' => "",
        "std" => "",
        "title" => __('副标题', 'zib_language') . '：'
    ),
    array(
        "name" => "like",
        'type' => "",
        "std" => "",
        "title" => __('点赞次数', 'zib_language') . '：'
    ),
    array(
        "name" => "views",
        'type' => "",
        "std" => "",
        "title" => __('阅读次数', 'zib_language') . '：'
    ),
    array(
        "name" => "no_article-navs",
        "std" => false,
        'type' => "checkbox",
        "title" => __('不显示目录树', 'zib_language') . '：'
    ),
    array(
        "name" => "article_maxheight_xz",
        "std" => false,
        'type' => "checkbox",
        "title" => __('限制内容最大高度', 'zib_language') . '：'
    )
);

add_action('admin_menu', 'hui_postmeta_subtitle_create');
add_action('save_post', 'hui_postmeta_subtitle_save');

function hui_postmeta_subtitle()
{
    global $post, $postmeta_subtitle;
    foreach ($postmeta_subtitle as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if ($meta_box_value == "")
            $meta_box_value = $meta_box['std'];
        if ($meta_box['name'] == 'subtitle') {
            echo '<p>' . (isset($meta_box['title']) ? $meta_box['title'] : '') . '</p>';
            echo '<p><input type="text" style="width:98%" value="' . $meta_box_value . '" name="' . $meta_box['name'] . '"></p>';
        } else if ($meta_box['type'] == 'checkbox') {
            echo '<p><label> ' . (isset($meta_box['title']) ? $meta_box['title'] : '') . '<input ' . ($meta_box_value ? 'checked' : '') . ' style="margin-left:10px" type="checkbox" value="1" name="' . $meta_box['name'] . '"></label></p>';
        } else {
            echo '<p>' . (isset($meta_box['title']) ? $meta_box['title'] : '');
            echo '<input type="number" style="width:80px;margin-left:30px;" value="' . $meta_box_value . '" name="' . $meta_box['name'] . '"></p>';
        }
    }

    echo '<input type="hidden" name="post_newmetaboxes_noncename" id="post_newmetaboxes_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
}

function hui_postmeta_subtitle_create()
{
    global $theme_name;
    if (function_exists('add_meta_box')) {
        add_meta_box('postmeta_subtitle_boxes', __('文章扩展', 'zib_language'), 'hui_postmeta_subtitle', 'post', 'side', 'high');
    }
}

function hui_postmeta_subtitle_save($post_id)
{
    global $postmeta_subtitle;

    if (!wp_verify_nonce(isset($_POST['post_newmetaboxes_noncename']) ? $_POST['post_newmetaboxes_noncename'] : '', plugin_basename(__FILE__)))
        return;

    if (!current_user_can('edit_posts', $post_id))
        return;

    foreach ($postmeta_subtitle as $meta_box) {
        $data = isset($_POST[$meta_box['name']]) ? $_POST[$meta_box['name']] : '';
        if (get_post_meta($post_id, $meta_box['name']) == "")
            add_post_meta($post_id, $meta_box['name'], $data, true);
        elseif ($data != get_post_meta($post_id, $meta_box['name'], true))
            update_post_meta($post_id, $meta_box['name'], $data);
        elseif ($data == "")
            delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
    }
}

//熊掌号原创文章
$_on = '';
if ( _pz('xzh_post_on') && _pz('xzh_post_token')) {
    $_on = true;
}
$postmeta_xzh = array(
    array(
        "title" => "推送到熊掌号",
        "name" => "xzh_post_ison",
        "std" => $_on,
        "disabled" => $_on,
    )
);
if (_pz('xzh_appid') && _pz('xzh_post_token')) {
    add_action('admin_menu', 'hui_postmeta_xzh_create');
    add_action('save_post', 'hui_postmeta_xzh_save');
}

function hui_postmeta_xzh()
{
    global $post, $postmeta_xzh;
    foreach ($postmeta_xzh as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if ($meta_box_value == "")
            $meta_box_value = $meta_box['std'];
        echo '<span style="margin:15px 20px 15px 0; display:inline-block;"><label><input ' . ($meta_box_value ? 'checked' : '') . ' type="checkbox" value="1" name="' . $meta_box['name'] . '"> ' . (isset($meta_box['title']) ? $meta_box['title'] : '') . '</label></span>';
    }
    $tui = get_post_meta($post->ID, 'xzh_tui_back', true);
    if ($tui) {
        echo '<p><strong>推送结果：</strong>' . $tui . '</p>';
    } else {
        echo '<p>发布、更新文章并刷新页面后可查看推送结果</p>';
    }
    echo '<input type="hidden" name="post_newmetaboxes_noncename" id="post_newmetaboxes_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
}

function hui_postmeta_xzh_create()
{
    global $theme_name;
    if (function_exists('add_meta_box')) {
        add_meta_box('postmeta_xzh_boxes', __('百度熊掌号资源提交', 'zib_language'), 'hui_postmeta_xzh', 'post', 'normal', 'high');
        add_meta_box('postmeta_xzh_boxes', __('百度熊掌号资源提交', 'zib_language'), 'hui_postmeta_xzh', 'page', 'normal', 'high');
    }
}

function hui_postmeta_xzh_save($post_id)
{
    global $postmeta_xzh;

    if (!wp_verify_nonce(isset($_POST['post_newmetaboxes_noncename']) ? $_POST['post_newmetaboxes_noncename'] : '', plugin_basename(__FILE__)))
        return;

    if (!current_user_can('edit_posts', $post_id))
        return;

    foreach ($postmeta_xzh as $meta_box) {
        $data = isset($_POST[$meta_box['name']]) ? $_POST[$meta_box['name']] : '';
        if($data){
        	    tb_xzh_post_to_baidu();
        }
    }
}

// 熊掌号实时推送
function tb_xzh_post_to_baidu()
{
    global $post;
    if ( _pz('xzh_post_on') && _pz('xzh_post_token')) {
        $plink = get_permalink($post->ID);
        $ok = get_post_meta($post->ID, 'xzh_tui_back', true);
        $site = home_url();
        if ('publish' == $post->post_status && $plink && strstr($ok, '成功') == false) {
            $urls = array();
            $urls[] = $plink;
            $api = 'http://data.zz.baidu.com/urls?site=' . $site . '&token=' . _pz('xzh_post_token');
            $ch = curl_init();
            $options =  array(
                CURLOPT_URL => $api,
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => implode("\n", $urls),
                CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
            );
            curl_setopt_array($ch, $options);
            $result = curl_exec($ch);
            $result_text = $result;

            $result = json_decode($result);

            if(!empty($result->success)){
                $result_text = '推送成功：' . $result_text;
            }else{
                $result_text = '推送失败：' . $result_text;
            }
            update_post_meta($post->ID, 'xzh_tui_back',$result_text );
			return;

        }
    }
}
