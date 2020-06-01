console.log('Zibll Widget');

jQuery(document).ready(function () {
    var value_id, img_ids = [],
        img_ss = '';

    jQuery('body').on('click', '.add_lists_button', function (event) {
        _s_i = parseInt(jQuery(this).attr('data-count'));
        _s_m = jQuery(this).attr('data-name');

        _s_html = '<div class="widget_ui_slider_g">\
        <div class="panel"><h4 class="panel-title">栏目' + (_s_i + 1)+'</h4><div class="panel-conter">\
        <label>栏目' + (_s_i + 1) + '-标题（必填）：\
        <input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][title]" value="">\
        </label>\
        <label>栏目' + (_s_i + 1) + '-分类限制：\
        <input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][cat]" value="">\
        </label>\
        <label>栏目' + (_s_i + 1) + '-专题限制：\
        <input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][topics]" value="">\
        </label>\
        <label>栏目' + (_s_i + 1) + '-排序方式：\
            <select style="width:100%;" name="' + _s_m + '[' + _s_i + '][orderby]">\
            <option value="comment_count">评论数</option>\
            <option value="views" selected="selected">浏览量</option>\
            <option value="like">点赞数</option>\
            <option value="favorite">收藏数</option>\
            <option value="date">发布时间</option>\
            <option value="modified">更新时间</option>\
            <option value="rand">随机排序</option>\
        </select>\
        </label></div></div></div>';
        jQuery(this).attr('data-count', (_s_i + 1)).before(_s_html);
    });
    jQuery('body').on('click', '.rem_lists_button', function (event) {
        jQuery(this).prev().prev('.widget_ui_slider_g').remove();
        add_b = jQuery(this).siblings('.add_button');
        _s_i = parseInt(add_b.attr('data-count'));
        add_b.attr('data-count', (_s_i - 1))
    });

    var ashu_upload_frame;
    var value_id, img_ids = [],
        img_ss = '';
    jQuery('body').on('click', '.delimg_upload_button', function(event) {
        jQuery(this).siblings('div').html('');
        jQuery(this).siblings('label').find('input').val('');
    });
    jQuery('body').on('click', '.add_slider_button', function(event) {
        _s_i = parseInt(jQuery(this).attr('data-count'));
        _s_i1 = _s_i+1;
        _s_m = jQuery(this).attr('data-name');
        _s_html = '<div class="widget_ui_slider_g">\
        <div class="panel"><h4 class="panel-title">幻灯片' + _s_i1+'</h4><div class="panel-conter">\
        <label>幻灯片' + _s_i1 + '-标题:<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][title]" value=""></label>\
        <label>幻灯片' + _s_i1 + '-简介<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][dec]" value=""></label>\
        <label>幻灯片' + _s_i1 + '-链接<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][href]" value=""></label>\
        <div class=""><label>幻灯片' + _s_i1 + '-图片<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][link]" value=""></label>\
        <button type="button" class="button ashu_upload_button">选择图片</button>\
        <button type="button" class="button delimg_upload_button">移除图片</button>\
        <div class="widget_ui_slider_box"></div></div></div></div></div>';

        jQuery(this).attr('data-count', _s_i1).before(_s_html);
    });
    jQuery('body').on('click', '.add_links_button', function(event) {
        _s_i = parseInt(jQuery(this).attr('data-count'));
        _s_i1 = _s_i+1;
        _s_m = jQuery(this).attr('data-name');
        _s_html = '<div class="widget_ui_slider_g">\
        <div class="panel"><h4 class="panel-title">链接' + _s_i1+'</h4><div class="panel-conter">\
        <label>链接' + _s_i1 + '-名称（必填）:<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][title]" value=""></label>\
        <label>链接' + _s_i1 + '-简介<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][dec]" value=""></label>\
        <label>链接' + _s_i1 + '-链接（必填）<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][href]" value=""></label>\
        <div class=""><label>链接' + _s_i1 + '-图片<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][link]" value=""></label>\
        <button type="button" class="button ashu_upload_button">选择图片</button>\
        <button type="button" class="button delimg_upload_button">移除图片</button>\
        <div class="widget_ui_slider_box"></div></div></div></div></div>';

        jQuery(this).attr('data-count', _s_i1).before(_s_html);
    });
    jQuery('body').on('click', '.add_notice_button', function(event) {
        _s_i = parseInt(jQuery(this).attr('data-count'));
        _s_i1 = _s_i+1;
        _s_m = jQuery(this).attr('data-name');
        _s_html = '<div class="widget_ui_slider_g">\
        <div class="panel"><h4 class="panel-title">消息' + _s_i1+'</h4><div class="panel-conter">\
        <label>消息' + _s_i1 + '-内容（必填）:<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][title]" value=""></label>\
        <label>消息' + _s_i1 + '-图标<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][icon]" value=""></label>\
        <label>消息' + _s_i1 + '-链接<input style="width:100%;" type="text" name="' + _s_m + '[' + _s_i + '][href]" value=""></label>\
        </div></div></div>';

        jQuery(this).attr('data-count', _s_i1).before(_s_html);
    });
    jQuery('body').on('click', '.ashu_upload_button', function(event) {
        data_name = jQuery(this).attr('data-name');
        _his = jQuery(this);
        event.preventDefault();
        if (ashu_upload_frame) {
            ashu_upload_frame.open();
            return;
        }
        ashu_upload_frame = wp.media({
            title: '选择图片插入到幻灯片',
            button: {
                text: '确认',
            },
            multiple: true
        });
        ashu_upload_frame.on('select', function() {
            attachment = ashu_upload_frame.state().get('selection').first().toJSON();

            img_ss = '<img src="' + attachment.url + '">';
            _his.siblings('div').html(img_ss);
            _his.siblings('label').find('input').val(attachment.url);
        });

        ashu_upload_frame.open();
    });
    jQuery('body').on('click', '.cat-help-button', function(event) {
        jQuery(this).siblings('.cat-help-con').slideToggle();
    });
    jQuery('body').on('click', '.panel-title', function(event) {
        jQuery(this).siblings('.panel-conter').slideToggle();
    });
});
