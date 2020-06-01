(function() {
    tinymce.PluginManager.add('zibll_tinymce', function(editor, url) {
        editor.addButton('zibll_tinymce', {
            text: '快捷样式',
            icon: false,
            type: 'menubutton',
            menu: [{
                    text: '标题-主题色',
                    onclick: function() {
                        editor.selection.setContent('<div class="qe_bt_zts"><h1>' + editor.selection.getContent() + '&nbsp;</h1></div>');
                    }
                }, {
                    text: '标题-蓝色',
                    onclick: function() {
                        editor.selection.setContent('<div class="qe_bt_lan"><h1>' + editor.selection.getContent() + '&nbsp;</h1></div>');
                    }
                }, {
                    text: '标题-灰色',
                    onclick: function() {
                        editor.selection.setContent('<div class="qe_bt_hui"><h1>' + editor.selection.getContent() + '&nbsp;</h1></div>');
                    }
                },  {
                    text: '标题-红色',
                    onclick: function() {
                        editor.selection.setContent('<div class="qe_bt_c-red"><h1>' + editor.selection.getContent() + '&nbsp;</h1></div>');
                    }
                }, {
                    text: '红色文字框',
                    onclick: function() {
                        editor.selection.setContent('<div class="qe_wzk_c-red"><i class="fa fa-quote-left"></i>' + editor.selection.getContent() + '&nbsp;</div>');
                    }
                }, {
                    text: '灰色文字框',
                    onclick: function() {
                        editor.selection.setContent('<div class="qe_wzk_hui"><i class="fa fa-quote-left"></i>' + editor.selection.getContent() + '&nbsp;</div>');
                    }
                }, {
                    text: '蓝色文字框',
                    onclick: function() {
                        editor.selection.setContent('<div class="qe_wzk_lan"><i class="fa fa-quote-left"></i>' + editor.selection.getContent() + '&nbsp;</div>');
                    }
                }, {
                    text: '绿色文字框',
                    onclick: function() {
                        editor.selection.setContent('<div class="qe_wzk_lv"><i class="fa fa-quote-left"></i>' + editor.selection.getContent() + '&nbsp;</div>');
                    }
                }, {
                    text: '圆形按钮',
                    onclick: function() {
                        editor.insertContent('<div class="qe_yxan_1"><a class="qe_yxan_1a" href="#">空心按钮</a></div><div class="qe_yxan_2"><a class="qe_yxan_2a" href="#">空心按钮</a></div><div class="qe_yxan_3"><a class="qe_yxan_3a" href="#">空心按钮</a></div><div class="qe_yxan_4"><a class="qe_yxan_4a" href="#">空心按钮</a></div><div class="qe_yxan_5"><a class="qe_yxan_5a" href="#">空心按钮</a></div>');
                    }
                }, {
                    text: '方形按钮',
                    onclick: function() {
                        editor.insertContent('<div class="qe_fxan b1"><a href="#">方形按钮</a></div><div class="qe_fxan b2"><a href="#">方形按钮</a></div><div class="qe_fxan b3"><a href="#">方形按钮</a></div><div class="qe_fxan b4"><a href="#">方形按钮</a></div><div class="qe_fxan b5"><a href="#">方形按钮</a></div>');
                    }
                }, {
                    text: '隐藏内容（回复可见）',
                    onclick: function() {
                        editor.insertContent('[reply]\n' + editor.selection.getContent() + '&nbsp;[/reply]');
                    }
                }
            ]
        });
    });
})();