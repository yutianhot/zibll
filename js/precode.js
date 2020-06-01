(function() {
    tinymce.PluginManager.add('precode', function(editor, url) {
        editor.addButton('precode', {
            title: '高亮代码', //标题自拟
            image: url + '/edit/precode.png',
            onclick: function() {
                var w = Math.min(window.innerWidth);
                var h = Math.min(window.innerHeight);
                if (w > 1000) {
                    w = w * 0.6
                } else if (w > 640 && w < 1001) {
                    w = w * 0.8
                } else if (w < 641) {
                    w = w - 10
                }
                editor.windowManager.open({
                    title: '插入高亮代码',
                    width: w,
                    height: h * 0.7,
                    body: [{
                            type: 'listbox',
                            name: 'codeyy',
                            label: '选择语言',
                            values: [{
                                    text: 'yaml',
                                    value: 'yaml'
                                }, {
                                    text: 'xml/html',
                                    value: 'xml'
                                }, {
                                    text: 'visualbasic',
                                    value: 'visualbasic'
                                }, {
                                    text: 'vhdl',
                                    value: 'vhdl'
                                }, {
                                    text: 'typescript',
                                    value: 'typescript'
                                }, {
                                    text: 'swift',
                                    value: 'swift'
                                }, {
                                    text: 'squirrel',
                                    value: 'squirrel'
                                }, {
                                    text: 'sql',
                                    value: 'sql'
                                }, {
                                    text: 'shell',
                                    value: 'shell'
                                }, {
                                    text: 'scss/sass',
                                    value: 'scss'
                                }, {
                                    text: 'rust',
                                    value: 'rust'
                                }, {
                                    text: 'ruby',
                                    value: 'ruby'
                                }, {
                                    text: 'raw',
                                    value: 'raw'
                                }, {
                                    text: 'python',
                                    value: 'python'
                                }, {
                                    text: 'prolog',
                                    value: 'prolog'
                                }, {
                                    text: 'powershell',
                                    value: 'powershell'
                                }, {
                                    text: 'php',
                                    value: 'php'
                                }, {
                                    text: 'nsis',
                                    value: 'nsis'
                                }, {
                                    text: 'matlab',
                                    value: 'matlab'
                                }, {
                                    text: 'markdown',
                                    value: 'markdown'
                                }, {
                                    text: 'lua',
                                    value: 'lua'
                                }, {
                                    text: 'less',
                                    value: 'less'
                                }, {
                                    text: 'kotlin',
                                    value: 'kotlin'
                                }, {
                                    text: 'json',
                                    value: 'json'
                                }, {
                                    text: 'javascript',
                                    value: 'javascript'
                                }, {
                                    text: 'java',
                                    value: 'java'
                                }, {
                                    text: 'ini/conf',
                                    value: 'ini'
                                }, {
                                    text: 'groovy',
                                    value: 'groovy'
                                }, {
                                    text: 'go/golang',
                                    value: 'go'
                                }, {
                                    text: 'docker',
                                    value: 'dockerfile'
                                }, {
                                    text: 'diff',
                                    value: 'diff'
                                }, {
                                    text: 'cordpro',
                                    value: 'cordpro'
                                }, {
                                    text: 'cython',
                                    value: 'cython'
                                }, {
                                    text: 'css',
                                    value: 'css'
                                }, {
                                    text: 'csharp',
                                    value: 'csharp'
                                }, {
                                    text: 'Cpp/C++/C',
                                    value: 'cpp'
                                }, {
                                    text: 'avrassembly',
                                    value: 'avrassembly'
                                }, {
                                    text: 'assembly',
                                    value: 'assembly'
                                }, {
                                    text: '通用高亮',
                                    value: 'generic'
                                }
                            ],
                            value: 'generic'
                        }, {
                            type: "listbox",
                            name: "theme",
                            label: "主题",
                            values: [{
                                    text: 'enlighter',
                                    value: 'enlighter'
                                }, {
                                    text: 'classic',
                                    value: 'classic'
                                }, {
                                    text: 'beyond',
                                    value: 'beyond'
                                }, {
                                    text: 'mowtwo',
                                    value: 'mowtwo'
                                }, {
                                    text: 'eclipse',
                                    value: 'eclipse'
                                }, {
                                    text: 'droide',
                                    value: 'droide'
                                }, {
                                    text: 'minimal',
                                    value: 'minimal'
                                }, {
                                    text: 'atomic',
                                    value: 'atomic'
                                }, {
                                    text: 'dracula',
                                    value: 'dracula'
                                }, {
                                    text: 'bootstrap4',
                                    value: 'bootstrap4'
                                }, {
                                    text: 'rowhammer',
                                    value: 'rowhammer'
                                }, {
                                    text: 'godzilla',
                                    value: 'godzilla'
                                }, {
                                    text: '跟随全局设置',
                                    value: 'qj'
                                }
                            ],
                            value: "qj",
                        },{
                            type: "textbox",
                            name: "group",
                            label: "加入代码组：",
                            multiline: !1,
                            placeholder: "输入自定义组ID，留空即不加入组",
                            value: "",
                        }, {
                            type: "textbox",
                            name: "gtitle",
                            label: "代码标题",
                            placeholder: "加入组之后，此代码显示的标题",
                            multiline: !1,
                            value: "",
                        }, {
                            type: 'textbox',
                            name: 'codenr',
                            label: '代码：',
                            value: '',
                            multiline: true,
                            minHeight: h * 0.7 - 215
                        }
                    ],
                    onsubmit: function(e) {
                        var codenr = e.data.codenr.replace(/\r\n/gmi, '\n'),
                            codenr = tinymce.html.Entities.encodeAllRaw(codenr),
                            tm = e.data.theme == 'qj' ? '' : '&nbsp;data-enlighter-theme="' + e.data.theme + '"';
                        z = e.data.group ? '&nbsp;data-enlighter-group="g_' + e.data.group + '"' : '';
                        zt = e.data.group ? e.data.gtitle ? '&nbsp;data-enlighter-title="' + e.data.gtitle + '"' : '&nbsp;data-enlighter-title="' + e.data.codeyy + '"' : '';
                        yy = e.data.codeyy == 'generic' ? '' : '&nbsp;data-enlighter-language="' + e.data.codeyy + '"';
                        editor.insertContent('<pre><code class="gl"' + yy + tm + z + zt + '>' + codenr + '</code></pre>&nbsp;');
                    }
                });
            }
        });
    });
})();