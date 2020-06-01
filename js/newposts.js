tbfine(function () {
    return {
        init: function () {
            function save_userdata(data, c, _this) {
               //  console.log(data);
                var ajax_url = _win.uri + '/action/new_posts.php';
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
                        notyf(n.msg || c, ys,'','user_ajax');
                        _this.attr('disabled', false);
                        n.error || _this.parents('form').find("input:password").val("");
                        n.url && $('.newposts-title .view-btn').html('<a target="_blank" href="'+n.url+'" class="but c-blue">预览文章</a>')
                        n.time && $('.modified-time time').html('最后保存：'+ n.time);
                        n.ok && $(".form-control").val("");
                        n.singin && $('.signin-loader').click();
                        if(n.open_url){
                            window.location.href=n.open_url;
                            window.location.reload;
                        }
                    }
                });
            }

            _win.bd.on("click", '[name="submit"],[action]', function (e) {
                var _this = $(this);
                type = _this.attr('action');
                var form = _this.parents('form');
                var inputs = form.serializeObject();

                switch (type) {
                    case 'posts.draft':
                    case 'posts.save':
                        var form = _this.parents('form');
                        var inputs = form.serializeObject();
                        inputs.action = type;
                        inputs.post_content = tinyMCE.activeEditor.getContent();

                        save_userdata(inputs, '保存成功', _this);

                    break;
                }
            })
        }
    }
})