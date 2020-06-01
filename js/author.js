tbfine(function () {
    return {
        init: function () {
            var ajax_url = _win.uri + '/action/user.php';
            function save_userdata(data, c, _this) {
                //console.log(data);
                notyf("正在处理请稍等...", "load", "", "user_ajax"),
                    _this.attr('disabled', true),
                    c = c || "处理完成";
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: data,
                    dataType: "json",
                    success: function (n) {
                       // console.log(n);
                        ys = (n.ys ? n.ys : (n.error ? 'danger' : ""));
                        notyf(n.msg || c, ys, '', 'user_ajax');
                        _this.attr('disabled', false);
                        n.error || _this.parents('form').find("input:password").val("");
                    }
                });
            }
            //社交帐号解绑
            _win.bd.on("click", '.oauth-untying', function (e) {
                var r= confirm( "确认要解除帐号绑定吗？" );
                if (r==true){
                    data = {};
                    _this = $(this);
                    data['action'] = 'oauth.untying';
                    data['user_id'] = _this.attr('user-id');
                    data['type'] = _this.attr('untying-type');
                    save_userdata(data, '已解除绑定', $(this))
                }
            })


            //支付订单AJAX翻页
            _win.bd.on("click", '.order-ajax-next', function (e) {
                    /*  AJAX获取包装函数   (必须)
                        _this 传入点击按钮的自己   需要有href，下一页的链接(必须)
                        con ：需要插入的父元素选择器   (必须)
                        jcon ：获取内容的父元素选择器   (必须)
                        item ：获取的列表选择器   (必须)
                        loader ：加载动画的内容 （非必须，有默认值）
                        pag ：获取的分页内容选择器 （必须）
                        // 如果需要将下一页链接从新插入到新的按钮，则填写下面2个
                        next ：获取分页内容中的下一页 选择器
                        trigger ：将获取的下一页链接从新插入到的新的 按钮中-的class值
                        replace ：替换列表内容而不是追加
                        nomore ：全部加载完成之后的文案
                        data : 需要传入的数据，默认为空白
                    */
                r = '';
                _this = $(this);
                con = '.order-ajaxpager';
                jcon = '.order-ajaxpager';
                item = '.order-ajax-item';
                loader = '<div class="zib-widget pay-box"> <p class="placeholder t1"></p> <h4 class="item-excerpt placeholder k1"></h4><i class="placeholder s1"></i><i class="placeholder s1 ml10"></i></div>';
                pag = '.order-ajax-pag';
                next = '.order-ajax-next';
                trigger = '';
                replace = '';
                nomore = '';
                return post_ajax(_this, con, jcon, item, loader, pag, next, trigger, replace, nomore),!1;
            })

            //绑定上传进度
            var jqureAjaxXhrOnProgress = function (fun) {
                jqureAjaxXhrOnProgress.onprogress = fun;
                //使用闭包实现监听绑
                return function () {
                    //通过$.ajaxSettings.xhr();获得XMLHttpRequest对象
                    var xhr = $.ajaxSettings.xhr();
                    //判断监听函数是否为函数
                    if (typeof jqureAjaxXhrOnProgress.onprogress !== 'function')
                        return xhr;
                    //如果有监听函数并且xhr对象支持绑定时就把监听函数绑定上去
                    if (jqureAjaxXhrOnProgress.onprogress && xhr.upload) {
                        xhr.upload.onprogress = jqureAjaxXhrOnProgress.onprogress;
                    }
                    return xhr;
                }
            }

            function transferImgToBase64(e, n) {
                if ("undefined" == typeof FileReader) return notyf("您的浏览器不支持图片上传，请升级您的浏览器", "danger");
                var r = new FileReader();
                r.readAsDataURL(e[0]), r.onload = function (e) {
                    n && n(e.target.result);
                };
            }

            _win.bd.on("change", '[action="image_upload"]', function (e) {
                var _this = $(this);
                type = _this.attr('action');
                max = Number(_win.up_max_size);
                if (type) switch (type) {
                    case "image_upload":
                        var r = this.files || e.dataTransfer.files,
                            form = _this.parents('form');
                            pre = _this.attr('data-preview') || '.preview';
                        if (-1 == r[0].type.indexOf("image")) return void notyf("选择的文件不是图像文件！", "danger");
                        if (max && (r[0].size > max * 1024000)) return void notyf("所选图片大小不能超过" + max + "M，请重新选择", "danger");
                        transferImgToBase64(r, function (e) {
                            form.find(pre).html('<img class="fit-cover" src="' + e + '">');
                        });
                }
            });
            var up_djyz = false;
            _win.bd.on("click", '[name="submit"],[action]', function (e) {
                var _this = $(this);
                type = _this.attr('action');
                var form = _this.parents('form');
                var inputs = form.serializeObject();
                switch (type) {
                    case 'data.set':
                        var form = _this.parents('form');
                        var inputs = form.serializeObject();

                        save_userdata(inputs, '保存成功', _this);

                        break;
                    case 'info.upload':
                        if (up_djyz) {
                            notyf('正在处理中，请勿重复提交', 'warning', '2000');
                            return
                        }
                        if (e.preventDefault) e.preventDefault();
                        else e.returnValue = false;

                        var formData = new FormData(),
                            form = _this.parents('form'),
                            in_up = form.find('input[name="image_upload"]');

                        in_up.each(function () {
                            tag = $(this).attr('data-tag') || 'file';
                            fileObject = this.files[0];
                            if (fileObject) {
                                formData.append(tag, fileObject);
                            }
                        });

                        form.find('input').each(function () {
                            n = $(this).attr('name'), v = $(this).val();
                            if (n) {
                                formData.append(n, v);
                            }
                        });

                        notyf("正在处理请稍等...", "load", "", "user_ajax");
                        _this.attr('disabled', true), in_up.attr('disabled', true);
                        up_djyz = true;

                        $.ajax({
                            url: ajax_url,
                            type: 'POST',
                            data: formData,
                            // 告诉jQuery不要去处理发送的数据
                            processData: false,
                            cache: false,
                            // 告诉jQuery不要去设置Content-Type请求头
                            contentType: false,
                            dataType: 'json',
                            xhr: jqureAjaxXhrOnProgress(function (e) {
                                var percent = Math.round(e.loaded / e.total * 100);
                                form.find('.progress').css('opacity', 1).find('.progress-bar').css('width', percent + '%');
                            }),
                            success: function (n) {
                               // console.log(n);
                                form.find('.progress-bar').css('width', '100%').delay(2500, function () {
                                    $(this).css('width', '0%').parent('.progress').css('opacity', 0)
                                })
                                ys = (n.ys ? n.ys : (n.error ? 'danger' : ""));
                                notyf(n.msg || '修改成功，请刷新页面查看', ys, '', 'user_ajax');
                                return up_djyz = !1, _this.attr('disabled', false), in_up.attr('disabled', false).val(''),
                                    form.find('label').css('cursor', '');
                                location.reload();
                            }
                        });
                        break;
                }
            })























        }
    }
})