(function ($) {
    $(document).ready(function () {
        console.log("子比主题：增强编辑器");
        var b = wp.blocks,
            c = wp.components,
            e = wp.element,
            ed = wp.blockEditor ? wp.blockEditor : wp.editor,
            rE = wp.richText.registerFormatType,

            el = e.createElement,
            rB = b.registerBlockType,

            createBlock = b.createBlock,
            InnerBlocks = ed.InnerBlocks,
            RichTextToolbarButton = ed.RichTextToolbarButton,
            Component = wp.element.Component,
            getRectangleFromRange = wp.dom.getRectangleFromRange,
            Popover = c.Popover,
            RichText = ed.RichText,
            PlainText = ed.PlainText,
            MediaUpload = b.MediaUpload,
            Fragment = e.Fragment,
            InspectorControls = ed.InspectorControls,
            PanelBody = c.PanelBody,
            ClipboardButton = c.ClipboardButton,
            TextControl = c.TextControl,
            RadioControl = c.RadioControl,
            Toolbar = c.Toolbar,
            SelectControl = c.SelectControl,
            ToggleControl = c.ToggleControl,
            CheckboxControl = CheckboxControl,
            RangeControl = c.RangeControl,
            DropdownMenu = c.DropdownMenu,
            BlockControls = ed.BlockControls,
            AlignmentToolbar = ed.AlignmentToolbar;

        var colors = [{
                color: '#fb2121'
            },
            {
                color: '#ef0c7e'
            },
            {
                color: '#F3AC07'
            },
            {
                color: '#8CA803'
            },
            {
                color: '#64BD05'
            },
            {
                color: '#11C33F'
            },
            {
                color: '#08B89A'
            },
            {
                color: '#09ACE2'
            },
            {
                color: '#1F91F3'
            },
            {
                color: '#3B6ED5'
            },
            {
                color: '#664FFA'
            },
            {
                color: '#A845F7'
            },
            {
                color: '#333'
            },
            {
                color: '#666'
            },
            {
                color: '#999'
            },
            {
                color: '#f8f8f8'
            },
        ];
        //---------------------------------------------
        rB('zibllblock/feature', {
            title: 'Zibll:亮点',
            icon: {
                src: 'awards',
                foreground: '#f85253'
            },
            description: '包含图标和简介的亮点介绍，建议4个一组',
            category: 'zibll_block_cat',
            attributes: {
                title: {
                    type: "array",
                    source: 'children',
                    selector: ".feature-title",
                },
                icon: {
                    source: 'attribute',
                    selector: '.feature',
                    attribute: 'data-icon'
                },
                note: {
                    type: "array",
                    source: 'children',
                    selector: ".feature-note",
                },
                theme: {
                    source: 'attribute',
                    selector: '.feature',
                    attribute: 'class'
                },
                color: {
                    source: 'attribute',
                    selector: '.feature',
                    attribute: 'data-color'
                },
            },
            edit: function (props) {
                var at = props.attributes,
                    isS = props.isSelected,
                    sa = props.setAttributes,
                    bt = at.title,
                    jj = at.note,
                    ic = at.icon || 'fa-flag',
                    icc = at.color || 'feature-icon no1',
                    zt = at.theme || 'panel panel-default';

                function changecolor(e) {
                    var type = e.target.className;
                    sa({
                        color: type
                    });
                }

                function changetheme(e) {
                    var type = e.target.className;
                    sa({
                        theme: type
                    });
                }

                let Coc = el(c.ColorPalette, {
                    value: icc || '#444',
                    colors: colors,
                    className: "q-ColorPalette",
                    onChange: function (e) {
                        sa({
                            color: e
                        })
                    }
                });

                rti = el(
                        TextControl, {
                            tagName: 'div',
                            onChange: function (e) {
                                sa({
                                    icon: e
                                })
                            },
                            value: ic,
                            placeholder: '请输入图标class代码...'
                        }),

                    rt = el(
                        RichText, {
                            tagName: 'div',
                            onChange: function (e) {
                                sa({
                                    title: e
                                })
                            },
                            value: bt,
                            placeholder: '请输入亮点标题...'
                        }),
                    rtj = el(
                        RichText, {
                            tagName: 'div',
                            onChange: function (e) {
                                sa({
                                    note: e
                                })
                            },
                            value: jj,
                            placeholder: '请输入亮点简介...'
                        }),

                    ztan = el('div', {
                        className: 'g_extend anz panel_b'
                    }, [
                        el('button', {
                            className: 'feature-icon no1',
                            onClick: changecolor
                        }),
                        el('button', {
                            className: 'feature-icon no2',
                            onClick: changecolor
                        }),
                        el('button', {
                            className: 'feature-icon no3',
                            onClick: changecolor
                        }),
                        el('button', {
                            className: 'feature-icon no4',
                            onClick: changecolor
                        }),
                        el('button', {
                            className: 'feature-icon no5',
                            onClick: changecolor
                        }),
                    ]);

                return el('div', {}, el('div', {
                            className: "feature"
                        },
                        el('div', {
                            className: "feature-icon"
                        }, el('i', {
                            style: {
                                color: icc
                            },
                            className: "fa " + ic
                        })),

                        [isS && el('div', {
                            className: "feature-iconbj"
                        }, el('div', {
                            className: "feature-icont"
                        }, '请输入FA图标class代码：'), rti)], [isS && Coc],
                        el('div', {
                            className: "feature-title"
                        }, rt),
                        el('div', {
                            className: "feature-note"
                        }, rtj)
                    ),

                    el(InspectorControls, null,
                        el('div', {
                                className: 'modal-ss'
                            },
                            el('h5', {}, '图标使用说明'),
                            el('p', {}, '图标使用Font Awesome图标库v4.7版本，你可以搜索Font Awesome或者在以下网站找到全部图标代码'),
                            el('a', {
                                href: 'http://fontawesome.dashgame.com',
                                target: 'blank'
                            }, 'Font Awesome图标库'),
                        ),
                        el(PanelBody, {
                                icon: "admin-generic",
                                title: "设置"
                            },
                            el('div', {
                                className: "feature-icont"
                            }, 'FA图标class代码：'), rti,
                            el('div', {
                                className: "feature-icont"
                            }, '选择图标颜色：'), Coc,
                        )
                    )

                )
            },
            save: function (props) {
                var at = props.attributes,
                    bt = at.title,
                    jj = at.note,
                    icc = at.color || 'feature-icon no1',
                    ic = at.icon || 'fa-flag',
                    zt = at.theme || 'feature feature-default';

                return el('div', {
                        className: zt,
                        'data-icon': ic,
                        'data-color': icc
                    },
                    el('div', {
                        className: "feature-icon"
                    }, el('i', {
                        style: {
                            color: icc
                        },
                        className: "fa " + ic
                    })),
                    el('div', {
                        className: 'feature-title'
                    }, bt),
                    el('div', {
                        className: 'feature-note'
                    }, jj),
                );
            },
        });

        //---------------------------------------------
        rE('zibllblock/set-color', {
            title: '设定颜色',
            tagName: 'qc',
            className: null,
            attributes: {
                style: 'style',
                block: 'inline-block'
            },
            edit: class extend_Edit extends Component {
                constructor() {
                    super(...arguments);
                    this.show_modal = this.show_modal.bind(this);
                    this.close_modal = this.close_modal.bind(this);
                    this.words_selected = this.words_selected.bind(this);
                    this.set_popover_rect = this.set_popover_rect.bind(this);
                    this.remove_Format = this.remove_Format.bind(this);
                    this.onChange_cb = this.onChange_cb.bind(this);
                    this.set_color = this.set_color.bind(this);
                    this.set_color2 = this.set_color2.bind(this);
                    this.xsba = this.xsba.bind(this);
                    this.xsba_f = this.xsba_f.bind(this);
                    this.set_background = this.set_background.bind(this);
                    this.set_background2 = this.set_background2.bind(this);
                    this.state = {
                        modal_visibility: false
                    };
                }
                words_selected() {
                    const {
                        value
                    } = this.props;
                    return value.start !== value.end;
                }
                set_popover_rect() {
                    const selection = window.getSelection();
                    const range = selection.rangeCount > 0 ? selection.getRangeAt(0) : null;
                    var rectangle = getRectangleFromRange(range);
                    this.setState({
                        popover_rect: rectangle
                    });
                }
                show_modal() {
                    this.setState({
                        modal_visibility: true
                    });
                    this.set_popover_rect();
                }
                close_modal() {
                    this.setState({
                        modal_visibility: false
                    });
                }
                xsba() {
                    this.setState({
                        xsba: true
                    });
                }
                xsba_f() {
                    this.setState({
                        xsba: false
                    });
                }
                remove_Format(e) {
                    this.setState({
                        color: '',
                        background: ''
                    });
                    this.props.onChange(wp.richText.toggleFormat(
                        this.props.value, {
                            type: 'zibllblock/set-color',
                        }))
                }
                onChange_cb() {
                    this.props.onChange(wp.richText.applyFormat(
                        this.props.value, {
                            type: 'zibllblock/set-color',
                            attributes: {
                                style: "color:" + this.state.color + ";background:" + this.state.background,
                            }
                        }))
                }
                set_color(e) {
                    this.setState({
                        color: 'rgb(' + e.rgb.r + ',' + e.rgb.g + ',' + e.rgb.b + ',' + e.rgb.a + ')'
                    });
                    this.onChange_cb();
                }
                set_color2(e) {
                    this.setState({
                        color: e
                    });
                    this.props.onChange(wp.richText.applyFormat(
                        this.props.value, {
                            type: 'zibllblock/set-color',
                            attributes: {
                                style: "color:" + e + ";background:" + this.state.background,
                            }
                        }))
                }

                set_background2(e) {
                    this.setState({
                        background: e
                    });
                    this.props.onChange(wp.richText.applyFormat(
                        this.props.value, {
                            type: 'zibllblock/set-color',
                            attributes: {
                                style: "color:" + this.state.color + ";background:" + e,
                            }
                        }))
                }
                set_background(e) {
                    this.setState({
                        background: 'rgb(' + e.rgb.r + ',' + e.rgb.g + ',' + e.rgb.b + ',' + e.rgb.a + ')'
                    });
                    this.onChange_cb();
                }
                render() {
                    let {
                        isActive
                    } = this.props;
                    var props = this.props;
                    let Co = el(c.ColorPicker, {
                        color: this.state.color || '#444',
                        onChangeComplete: this.set_color
                    });
                    let Coc = el(c.ColorPalette, {
                        value: this.state.color || '#444',
                        colors: colors,
                        clearable: false,
                        className: "q-ColorPalette",
                        disableCustomColors: true,
                        onChange: this.set_color2
                    });
                    let Bob = el(c.ColorPalette, {
                        value: this.state.background || '#fff',
                        colors: colors,
                        clearable: false,
                        className: "q-ColorPalette",
                        disableCustomColors: true,
                        onChange: this.set_background2
                    });
                    let Cob = el(c.ColorPicker, {
                            color: this.state.background || '#fff',
                            onChangeComplete: this.set_background
                        }),
                        cz = el('button', {
                            className: 'remove-Format',
                            onClick: this.remove_Format
                        }, el('span', {
                            className: 'dashicons dashicons-image-rotate',
                        }));
                    return el(Fragment, null, el(RichTextToolbarButton, {
                        icon: "art",
                        title: "自定义颜色",
                        onClick: this.show_modal,
                        isActive: isActive,
                        isDisabled: !this.words_selected()
                    }), this.state.modal_visibility && el(Popover, {
                            anchorRect: this.state.popover_rect,
                            position: "bottom center",
                            className: "color_popover",
                            onFocusOutside: this.close_modal
                        },
                        el(c.ButtonGroup, {},
                            el('button', {
                                className: "btn btn-default " + (!this.state.xsba && "isS"),
                                onClick: this.xsba_f,
                            }, "颜色"),
                            el('button', {
                                className: "btn btn-default " + (this.state.xsba && "isS"),
                                onClick: this.xsba,
                            }, "背景色")),

                        cz,
                        !this.state.xsba && el("div", {

                        }, el("p", {}, "请选择文字颜色"), Coc, Co),
                        this.state.xsba && el("div", {

                        }, el("p", {}, "请选择背景颜色"), Bob, Cob)

                    ));
                }
            }
        });

        //---------------------------------------------
        rB('zibllblock/modal', {
            title: 'Zibll:模态框',
            icon: {
                src: 'feedback',
                foreground: '#f85253'
            },
            description: '一个弹出框、模态框，默认不会显示，通过按钮让它弹出',
            category: 'zibll_block_cat',
            attributes: {
                biaoti: {
                    type: "array",
                    source: 'children',
                    selector: ".modal-title",
                },
                kuandu: {
                    type: "attribute",
                    selector: ".modal-dialog",
                    attribute: "data-kd",
                },
                btn1: {
                    type: "array",
                    source: 'children',
                    selector: "button.btn1",
                },
                btn2: {
                    type: "array",
                    source: 'children',
                    selector: "button.btn2",
                },
                id: {
                    source: 'attribute',
                    selector: '.modal',
                    attribute: 'id'
                },
                btn1kg: {
                    type: "attribute",
                    attribute: "data-bkg1",
                    default: true
                },
                btn2kg: {
                    type: "attribute",
                    attribute: "data-bkg2",
                    default: true
                },
                btntm1: {
                    type: "attribute",
                    selector: "button.btn1",
                    attribute: 'class'
                },
                btntm2: {
                    type: "attribute",
                    selector: "button.btn2",
                    attribute: 'class'
                },
                oncopy: {
                    source: 'string'
                }
            },
            edit: function (props) {
                var at = props.attributes,
                    bt = at.biaoti,
                    btn1 = at.btn1,
                    btn2 = at.btn2,
                    btntm1 = at.btntm1 || 'btn1 but',
                    btntm2 = at.btntm2 || 'btn2 but c-blue',
                    bkg1 = at.btn1kg,
                    bkg2 = at.btn2kg,
                    isS = props.isSelected,
                    onC = at.oncopy,
                    kd = at.kuandu,
                    sa = props.setAttributes;

                var sjs = parseInt((Math.random() + 1) * Math.pow(10, 4));

                if (!at.id) {
                    sa({
                        id: "modal_" + sjs
                    })
                }

                function sabt1(e) {
                    var type = e.target.className;
                    sa({
                        btntm1: 'btn1 ' + type
                    });
                }

                function sabt2(e) {
                    var type = e.target.className;
                    sa({
                        btntm2: 'btn2 ' + type
                    });
                }
                var xzk = el(InnerBlocks, {}, ''),
                    rt = el(
                        RichText, {
                            tagName: 'div',
                            onChange: function (e) {
                                sa({
                                    biaoti: e
                                })
                            },
                            value: bt,
                            placeholder: '请输入标题...'
                        }),
                    b1rt = el(
                        RichText, {
                            tagName: 'div',
                            onChange: function (e) {
                                sa({
                                    btn1: e
                                })
                            },
                            value: btn1,
                            placeholder: '按钮1'
                        }),
                    b2rt = el(
                        RichText, {
                            tagName: 'div',
                            onChange: function (e) {
                                sa({
                                    btn2: e
                                })
                            },
                            value: btn2,
                            placeholder: '按钮2'
                        }),
                    ztan1 = el('span', {
                        className: 'modal-bu'
                    }, [
                        el('button', {
                            className: 'but',
                            onClick: sabt1
                        }),
                        el('button', {
                            className: 'but c-blue',
                            onClick: sabt1
                        }),
                        el('button', {
                            className: 'but c-red',
                            onClick: sabt1
                        }),
                        el('button', {
                            className: 'but c-yellow',
                            onClick: sabt1
                        }),
                        el('button', {
                            className: 'but c-green',
                            onClick: sabt1
                        }),
                        el('button', {
                            className: 'but c-purple',
                            onClick: sabt1
                        }),
                        el('button', {
                            className: 'but b-blue',
                            onClick: sabt1
                        }),
                        el('button', {
                            className: 'but b-red',
                            onClick: sabt1
                        }),
                        el('button', {
                            className: 'but b-yellow',
                            onClick: sabt1
                        }),
                        el('button', {
                            className: 'but b-green',
                            onClick: sabt1
                        }),
                        el('button', {
                            className: 'but b-purple',
                            onClick: sabt1
                        }),
                    ]),
                    ztan2 = el('span', {
                        className: 'modal-bu'
                    }, [
                        el('button', {
                            className: 'but',
                            onClick: sabt2
                        }),
                        el('button', {
                            className: 'but c-blue',
                            onClick: sabt2
                        }),
                        el('button', {
                            className: 'but c-red',
                            onClick: sabt2
                        }),
                        el('button', {
                            className: 'but c-yellow',
                            onClick: sabt2
                        }),
                        el('button', {
                            className: 'but c-green',
                            onClick: sabt2
                        }),
                        el('button', {
                            className: 'but c-purple',
                            onClick: sabt2
                        }),
                        el('button', {
                            className: 'but b-blue',
                            onClick: sabt2
                        }),
                        el('button', {
                            className: 'but b-red',
                            onClick: sabt2
                        }),
                        el('button', {
                            className: 'but b-yellow',
                            onClick: sabt2
                        }),
                        el('button', {
                            className: 'but b-green',
                            onClick: sabt2
                        }),
                        el('button', {
                            className: 'but b-purple',
                            onClick: sabt2
                        }),
                    ]);
                return el('div', {}, el('div', {
                            className: 'modal',
                        },
                        el('div', {
                            className: "modal-title"
                        }, rt, el('button', {
                            className: "close"
                        }, '×')),
                        el('div', {
                            className: "modal-body"
                        }, xzk),
                        [(bkg1 || bkg2) && el('div', {
                            className: "modal-footer"
                        }, [bkg1 && el('span', {
                            className: btntm1
                        }, b1rt)], [bkg2 && el('span', {
                            className: btntm2
                        }, b2rt)])]
                    ),

                    el(InspectorControls, {},

                        el('div', {
                                className: 'modal-ss'
                            },
                            el('h5', {}, '使用教程'),
                            el('p', {}, '模态框在页面中默认不会显示，需要一个触发按钮，将以下代码复制后填入任意链接的url中即可触发此模态框的弹出'),
                            el('p', {
                                className: 'modal-code'
                            }, "javascript:($('#" + at.id + "').modal('show'));"),
                            el('div', {
                                    className: 'Copy'
                                },
                                el(ClipboardButton, {
                                    text: "javascript:($('#" + at.id + "').modal('show'));",
                                    className: 'Copy btn btn-primary',
                                    onCopy: function (e) {
                                        sa({
                                            oncopy: true
                                        })
                                    },
                                    onFinishCopy: function (e) {
                                        sa({
                                            oncopy: false
                                        })
                                    },
                                }, onC ? "代码已复制" : "点击复制代码"))

                        ),
                        el(PanelBody, {
                                title: "模态框设置"
                            },
                            el(SelectControl, {
                                label: "宽度选择",
                                value: kd,
                                onChange: function (e) {
                                    sa({
                                        kuandu: e
                                    })
                                },
                                options: [{
                                    label: '默认中等宽度',
                                    value: ''
                                }, {
                                    label: '更小宽度',
                                    value: 'modal-sm'
                                }, {
                                    label: '更大宽度',
                                    value: 'modal-lg'
                                }],
                            }),

                            el('p', {}, ' '),
                            el(ToggleControl, {
                                label: '开启按钮1',
                                checked: bkg1,
                                onChange: function (e) {
                                    sa({
                                        btn1kg: e
                                    })
                                },
                            }), [bkg1 && ztan1],

                            el(ToggleControl, {
                                label: '开启按钮2',
                                checked: bkg2,
                                onChange: function (e) {
                                    sa({
                                        btn2kg: e
                                    })
                                },
                            }), [bkg2 && ztan2],

                        )
                    )
                );
            },
            save: function (props) {
                var con = InnerBlocks.Content,
                    at = props.attributes,
                    btn1 = at.btn1,
                    btn2 = at.btn2,
                    btntm1 = at.btntm1 || 'btn1 but',
                    btntm2 = at.btntm2 || 'btn2 but c-blue',
                    bkg1 = at.btn1kg,
                    bkg2 = at.btn2kg,
                    kd = at.kuandu,
                    id = at.id,
                    bt = at.biaoti;

                bth = el('div', {
                    className: "modal-header"
                }, el('strong', {
                    className: "modal-title",
                }, bt), el('button', {
                    className: "close",
                    "data-dismiss": "modal",
                }, el('div', {
                    'data-class': "ic-close",
                    'data-svg': "close",
                    'data-viewbox': "0 0 1024 1024"
                }, '')));
                coh = el('div', {
                    className: "modal-body"
                }, el(InnerBlocks.Content));

                foo = [((bkg1 && btn1) || (bkg2 && btn2)) && el('div', {
                        className: "modal-footer"
                    },
                    [(bkg1 && btn1) && el('button', {
                        className: btntm1,
                    }, btn1)],
                    [(bkg2 && btn2) && el('button', {
                        className: btntm2,
                    }, btn2)]

                )];

                return el('div', {}, el('div', {
                    className: 'modal fade',
                    id: id,
                    "aria-hidden": "true",
                    "data-bkg1": bkg1,
                    "aria-bkg2": bkg2,
                    "role": "dialog",
                    "tabindex": "-1",
                }, el('div', {
                    className: 'modal-dialog ' + kd,
                    "data-kd": kd,
                }, el('div', {
                        className: 'modal-content',
                    },

                    bth, coh, foo))));
            },
        });
        //---------------------------------------------
        rB('zibllblock/collapse', {
            title: 'Zibll:折叠框',
            icon: {
                src: 'sort',
                foreground: '#f85253'
            },
            description: '手风琴折叠框，可以插入任意内容，点击标题可切换内容显示和隐藏',
            category: 'zibll_block_cat',
            attributes: {
                biaoti: {
                    type: "array",
                    source: 'children',
                    selector: ".biaoti",
                },
                isshow: {
                    type: "attribute",
                    selector: '.panel',
                    attribute: "data-isshow",
                    default: true
                },
                theme: {
                    source: 'attribute',
                    selector: '.panel',
                    attribute: 'class'
                },
                id: {
                    source: 'attribute',
                    selector: '.collapse',
                    attribute: 'id'
                },
                ffs: {
                    source: 'string',
                }
            },
            edit: function (props) {
                var at = props.attributes,
                    bt = at.biaoti,
                    zt = at.theme || 'panel panel-default',
                    kg = at.isshow,
                    isS = props.isSelected,
                    ffs = at.ffs || 'ffshow',
                    sa = props.setAttributes;

                var sjs = parseInt((Math.random() + 1) * Math.pow(10, 4));

                if (!at.id) {
                    sa({
                        id: "collapse_" + sjs
                    })
                }

                function ffshow(e) {
                    if (ffs == 'ffshow') {
                        sa({
                            ffs: 'ffhide'
                        });
                    } else {
                        sa({
                            ffs: 'ffshow'
                        });
                    }
                }

                function changeType(e) {
                    var type = e.target.className;
                    sa({
                        theme: 'panel ' + type
                    });
                }
                var xzk = el(InnerBlocks, {}, ''),
                    rt = el(
                        RichText, {
                            tagName: 'div',
                            onChange: function (e) {
                                sa({
                                    biaoti: e
                                })
                            },
                            value: bt,
                            isSelected: props.isSelected,
                            placeholder: '请输入折叠框标题...'
                        }),
                    ztan = el('span', {
                        className: 'g_extend anz panel_b'
                    }, [
                        el('button', {
                            className: 'panel-default',
                            onClick: changeType
                        }),
                        el('button', {
                            className: 'panel-info',
                            onClick: changeType
                        }),
                        el('button', {
                            className: 'panel-success',
                            onClick: changeType
                        }),
                        el('button', {
                            className: 'panel-warning',
                            onClick: changeType
                        }), el('button', {
                            className: 'panel-danger',
                            onClick: changeType
                        }),
                    ]),
                    shkg = el(ToggleControl, {
                        label: '默认状态',
                        checked: kg,
                        onChange: function (e) {
                            sa({
                                isshow: e
                            })
                        }
                    });
                return el('div', {}, el('div', {
                        className: zt,
                    }, el('div', {
                        className: "panel-heading"
                    }, rt), el('span', {
                        className: ffs + " isshow dashicons dashicons-arrow-down-alt2",
                        onClick: ffshow
                    }), el('div', {
                        className: ffs + " panel-body"
                    }, xzk)),

                    el(InspectorControls, null,
                        el(PanelBody, {
                            icon: "admin-generic",
                            title: "设置"
                        }), el('div', {}, '主题样式'), el('p', {}, shkg),
                        el('i', {
                            className: '.components-base-control__help'
                        }, kg ? '默认为展开状态' : '默认为折叠状态'))
                );
            },
            save: function (props) {
                var con = InnerBlocks.Content,
                    at = props.attributes,
                    zt = at.theme || 'panel',
                    kg = at.isshow,
                    id = at.id,
                    bt = at.biaoti;

                bth = el('div', {
                    className: "panel-heading " + (kg ? '' : 'collapsed'),
                    href: "#" + id,
                    "data-toggle": "collapse",
                    "aria-controls": "collapseExample",
                }, el('i', {
                    className: "fa fa-plus"
                }), el('strong', {
                    className: "biaoti ",
                }, bt))
                coh = el('div', {
                    className: "collapse " + (kg ? 'in' : ''),
                    id: id,
                }, el('div', {
                    className: "panel-body"
                }, el(InnerBlocks.Content)));

                return el('div', {}, el('div', {
                    className: zt,
                    "data-theme": zt,
                    "data-isshow": kg,
                }, bth, coh));
            },
        });
        //-------------------------------------------------------------
        rB('zibllblock/enlighter', {
            title: 'Zibll:高亮代码',
            icon: {
                src: 'editor-code',
                foreground: '#f85253'
            },
            category: 'zibll_block_cat',
            description: '输入代码，将自动高亮显示',
            keywords: ["code", "sourcecode", "代码"],
            attributes: {
                content: {
                    type: "string",
                    selector: "code.gl",
                    source: "text"
                },
                c_language: {
                    type: "attribute",
                    attribute: "data-enlighter-language",
                    default: "generic"
                },
                theme: {
                    type: "attribute",
                    attribute: "data-enlighter-theme",
                    default: ""
                },
                highlight: {
                    type: "attribute",
                    attribute: "data-enlighter-highlight",
                    default: ""
                },
                linenumbers: {
                    type: "attribute",
                    attribute: "data-enlighter-linenumbers",
                    default: ""
                },
                lineoffset: {
                    type: "attribute",
                    attribute: "data-enlighter-lineoffset",
                    default: ""
                },
                title: {
                    type: "attribute",
                    attribute: "data-enlighter-title",
                    default: ""
                },
                group: {
                    type: "attribute",
                    attribute: "data-enlighter-group",
                    default: ""
                }
            },
            transforms: {
                from: [{
                    type: "raw",
                    priority: 4,
                    isMatch: function (e) {
                        return "PRE" === e.nodeName && 1 === e.children.length && "CODE" === e.firstChild.nodeName
                    },
                    transform: function (e) {
                        return createBlock("zibllblock/enlighter", {
                            content: e.textContent
                        })
                    }
                }, {
                    type: "raw",
                    priority: 4,
                    isMatch: function (e) {
                        return "PRE" === e.nodeName && "EnlighterJSRAW" === e.className
                    },
                    transform: function (e) {
                        return createBlock("zibllblock/enlighter", {
                            content: e.textContent,
                            language: e.dataset.enlighterLanguage || "",
                            theme: e.dataset.enlighterTheme || "",
                            highlight: e.dataset.enlighterHighlight || "",
                            linenumbers: e.dataset.enlighterLinenumbers || "",
                            lineoffset: e.dataset.enlighterLineoffset || "",
                            title: e.dataset.enlighterTitle || "",
                            group: e.dataset.enlighterGroup || ""
                        })
                    }
                }, {
                    type: "block",
                    blocks: ["core/code", "core/preformatted", "core/paragraph"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("zibllblock/enlighter", {
                            content: t
                        })
                    }
                }],
                to: [{
                    type: "block",
                    blocks: ["core/code"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/code", {
                            content: t
                        })
                    }
                }, {
                    type: "block",
                    blocks: ["core/preformatted"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/preformatted", {
                            content: t
                        })
                    }
                }]
            },
            edit: function (props) {
                var content = props.attributes.content,
                    typeClass = props.attributes.typeClass || 'qe_bt_zts',
                    isSelected = props.isSelected;

                var t, n, l = props.attributes,
                    r = props.setAttributes;


                var sm = el(Toolbar, null, el(DropdownMenu, {
                        className: "enlighter-dropdownmenu",
                        icon: "editor-paste-text",
                        label: "设置代码语言",
                        controls: [{
                            title: 'yaml',
                            value: 'yaml',
                            onClick: function () {
                                return r({
                                    c_language: 'yaml'
                                })
                            }
                        }, {
                            title: 'xml/html',
                            value: 'xml',
                            onClick: function () {
                                return r({
                                    c_language: 'xml'
                                })
                            }
                        }, {
                            title: 'visualbasic',
                            value: 'visualbasic',
                            onClick: function () {
                                return r({
                                    c_language: 'visualbasic'
                                })
                            }
                        }, {
                            title: 'vhdl',
                            value: 'vhdl',
                            onClick: function () {
                                return r({
                                    c_language: 'vhdl'
                                })
                            }
                        }, {
                            title: 'typescript',
                            value: 'typescript',
                            onClick: function () {
                                return r({
                                    c_language: 'typescript'
                                })
                            }
                        }, {
                            title: 'swift',
                            value: 'swift',
                            onClick: function () {
                                return r({
                                    c_language: 'swift'
                                })
                            }
                        }, {
                            title: 'squirrel',
                            value: 'squirrel',
                            onClick: function () {
                                return r({
                                    c_language: 'squirrel'
                                })
                            }
                        }, {
                            title: 'sql',
                            value: 'sql',
                            onClick: function () {
                                return r({
                                    c_language: 'sql'
                                })
                            }
                        }, {
                            title: 'shell',
                            value: 'shell',
                            onClick: function () {
                                return r({
                                    c_language: 'shell'
                                })
                            }
                        }, {
                            title: 'scss/sass',
                            value: 'scss',
                            onClick: function () {
                                return r({
                                    c_language: 'scss'
                                })
                            }
                        }, {
                            title: 'rust',
                            value: 'rust',
                            onClick: function () {
                                return r({
                                    c_language: 'rust'
                                })
                            }
                        }, {
                            title: 'ruby',
                            value: 'ruby',
                            onClick: function () {
                                return r({
                                    c_language: 'ruby'
                                })
                            }
                        }, {
                            title: 'raw',
                            value: 'raw',
                            onClick: function () {
                                return r({
                                    c_language: 'raw'
                                })
                            }
                        }, {
                            title: 'python',
                            value: 'python',
                            onClick: function () {
                                return r({
                                    c_language: 'python'
                                })
                            }
                        }, {
                            title: 'prolog',
                            value: 'prolog',
                            onClick: function () {
                                return r({
                                    c_language: 'prolog'
                                })
                            }
                        }, {
                            title: 'powershell',
                            value: 'powershell',
                            onClick: function () {
                                return r({
                                    c_language: 'powershell'
                                })
                            }
                        }, {
                            title: 'php',
                            value: 'php',
                            onClick: function () {
                                return r({
                                    c_language: 'php'
                                })
                            }
                        }, {
                            title: 'nsis',
                            value: 'nsis',
                            onClick: function () {
                                return r({
                                    c_language: 'nsis'
                                })
                            }
                        }, {
                            title: 'matlab',
                            value: 'matlab',
                            onClick: function () {
                                return r({
                                    c_language: 'matlab'
                                })
                            }
                        }, {
                            title: 'markdown',
                            value: 'markdown',
                            onClick: function () {
                                return r({
                                    c_language: 'markdown'
                                })
                            }
                        }, {
                            title: 'lua',
                            value: 'lua',
                            onClick: function () {
                                return r({
                                    c_language: 'lua'
                                })
                            }
                        }, {
                            title: 'less',
                            value: 'less',
                            onClick: function () {
                                return r({
                                    c_language: 'less'
                                })
                            }
                        }, {
                            title: 'kotlin',
                            value: 'kotlin',
                            onClick: function () {
                                return r({
                                    c_language: 'kotlin'
                                })
                            }
                        }, {
                            title: 'json',
                            value: 'json',
                            onClick: function () {
                                return r({
                                    c_language: 'json'
                                })
                            }
                        }, {
                            title: 'javascript',
                            value: 'javascript',
                            onClick: function () {
                                return r({
                                    c_language: 'javascript'
                                })
                            }
                        }, {
                            title: 'java',
                            value: 'java',
                            onClick: function () {
                                return r({
                                    c_language: 'java'
                                })
                            }
                        }, {
                            title: 'ini/conf',
                            value: 'ini',
                            onClick: function () {
                                return r({
                                    c_language: 'ini'
                                })
                            }
                        }, {
                            title: 'groovy',
                            value: 'groovy',
                            onClick: function () {
                                return r({
                                    c_language: 'groovy'
                                })
                            }
                        }, {
                            title: 'go/golang',
                            value: 'go',
                            onClick: function () {
                                return r({
                                    c_language: 'go'
                                })
                            }
                        }, {
                            title: 'docker',
                            value: 'dockerfile',
                            onClick: function () {
                                return r({
                                    c_language: 'dockerfile'
                                })
                            }
                        }, {
                            title: 'diff',
                            value: 'diff',
                            onClick: function () {
                                return r({
                                    c_language: 'diff'
                                })
                            }
                        }, {
                            title: 'cordpro',
                            value: 'cordpro',
                            onClick: function () {
                                return r({
                                    c_language: 'cordpro'
                                })
                            }
                        }, {
                            title: 'cython',
                            value: 'cython',
                            onClick: function () {
                                return r({
                                    c_language: 'cython'
                                })
                            }
                        }, {
                            title: 'css',
                            value: 'css',
                            onClick: function () {
                                return r({
                                    c_language: 'css'
                                })
                            }
                        }, {
                            title: 'csharp',
                            value: 'csharp',
                            onClick: function () {
                                return r({
                                    c_language: 'csharp'
                                })
                            }
                        }, {
                            title: 'Cpp/C++/C',
                            value: 'cpp',
                            onClick: function () {
                                return r({
                                    c_language: 'cpp'
                                })
                            }
                        }, {
                            title: 'avrassembly',
                            value: 'avrassembly',
                            onClick: function () {
                                return r({
                                    c_language: 'avrassembly'
                                })
                            }
                        }, {
                            title: 'assembly',
                            value: 'assembly',
                            onClick: function () {
                                return r({
                                    c_language: 'assembly'
                                })
                            }
                        }, {
                            title: '自动识别',
                            value: 'generic',
                            onClick: function () {
                                return r({
                                    c_language: 'generic'
                                })
                            }
                        }]
                    })),
                    sp = el(PlainText, {
                        value: l.content,
                        onChange: function (e) {
                            return r({
                                content: e
                            })
                        },
                        placeholder: "请输入代码...",
                        "aria-label": "Code"
                    })
                ss = el(SelectControl, {
                        label: "代码语言",
                        value: l.c_language,
                        options: [{
                            label: 'yaml',
                            value: 'yaml'
                        }, {
                            label: 'xml/html',
                            value: 'xml'
                        }, {
                            label: 'visualbasic',
                            value: 'visualbasic'
                        }, {
                            label: 'vhdl',
                            value: 'vhdl'
                        }, {
                            label: 'typescript',
                            value: 'typescript'
                        }, {
                            label: 'swift',
                            value: 'swift'
                        }, {
                            label: 'squirrel',
                            value: 'squirrel'
                        }, {
                            label: 'sql',
                            value: 'sql'
                        }, {
                            label: 'shell',
                            value: 'shell'
                        }, {
                            label: 'scss/sass',
                            value: 'scss'
                        }, {
                            label: 'rust',
                            value: 'rust'
                        }, {
                            label: 'ruby',
                            value: 'ruby'
                        }, {
                            label: 'raw',
                            value: 'raw'
                        }, {
                            label: 'python',
                            value: 'python'
                        }, {
                            label: 'prolog',
                            value: 'prolog'
                        }, {
                            label: 'powershell',
                            value: 'powershell'
                        }, {
                            label: 'php',
                            value: 'php'
                        }, {
                            label: 'nsis',
                            value: 'nsis'
                        }, {
                            label: 'matlab',
                            value: 'matlab'
                        }, {
                            label: 'markdown',
                            value: 'markdown'
                        }, {
                            label: 'lua',
                            value: 'lua'
                        }, {
                            label: 'less',
                            value: 'less'
                        }, {
                            label: 'kotlin',
                            value: 'kotlin'
                        }, {
                            label: 'json',
                            value: 'json'
                        }, {
                            label: 'javascript',
                            value: 'javascript'
                        }, {
                            label: 'java',
                            value: 'java'
                        }, {
                            label: 'ini/conf',
                            value: 'ini'
                        }, {
                            label: 'groovy',
                            value: 'groovy'
                        }, {
                            label: 'go/golang',
                            value: 'go'
                        }, {
                            label: 'docker',
                            value: 'dockerfile'
                        }, {
                            label: 'diff',
                            value: 'diff'
                        }, {
                            label: 'cordpro',
                            value: 'cordpro'
                        }, {
                            label: 'cython',
                            value: 'cython'
                        }, {
                            label: 'css',
                            value: 'css'
                        }, {
                            label: 'csharp',
                            value: 'csharp'
                        }, {
                            label: 'Cpp/C++/C',
                            value: 'cpp'
                        }, {
                            label: 'avrassembly',
                            value: 'avrassembly'
                        }, {
                            label: 'assembly',
                            value: 'assembly'
                        }, {
                            label: '自动识别',
                            value: 'generic'
                        }],
                        onChange: function (e) {
                            return r({
                                c_language: e
                            })
                        }
                    }),
                    sz = el(InspectorControls, null,
                        el(PanelBody, {
                                icon: "admin-appearance",
                                title: "代码设置"
                            }, ss,
                            el(SelectControl, {
                                label: "选择主题",
                                value: l.theme,
                                options: [{
                                    label: 'enlighter',
                                    value: 'enlighter'
                                }, {
                                    label: 'classic',
                                    value: 'classic'
                                }, {
                                    label: 'beyond',
                                    value: 'beyond'
                                }, {
                                    label: 'mowtwo',
                                    value: 'mowtwo'
                                }, {
                                    label: 'eclipse',
                                    value: 'eclipse'
                                }, {
                                    label: 'droide',
                                    value: 'droide'
                                }, {
                                    label: 'minimal',
                                    value: 'minimal'
                                }, {
                                    label: 'atomic',
                                    value: 'atomic'
                                }, {
                                    label: 'dracula',
                                    value: 'dracula'
                                }, {
                                    label: 'bootstrap4',
                                    value: 'bootstrap4'
                                }, {
                                    label: 'rowhammer',
                                    value: 'rowhammer'
                                }, {
                                    label: 'godzilla',
                                    value: 'godzilla'
                                }, {
                                    label: '跟随主题设置',
                                    value: ''
                                }],
                                onChange: function (e) {
                                    return r({
                                        theme: e
                                    })
                                }
                            }), el(RadioControl, {
                                label: "显示行号",
                                selected: l.linenumbers,
                                options: [{
                                    label: "跟随主题设置",
                                    value: ""
                                }, {
                                    label: "显示",
                                    value: "true"
                                }, {
                                    label: "隐藏",
                                    value: "false"
                                }],
                                onChange: function (e) {
                                    return r({
                                        linenumbers: e
                                    })
                                }
                            }), el(TextControl, {
                                label: "起始行号",
                                value: l.lineoffset,
                                onChange: function (e) {
                                    return r({
                                        lineoffset: e
                                    })
                                },
                                placeholder: "输入行号。例：12"
                            }), el(TextControl, {
                                label: "高亮行号",
                                value: l.highlight,
                                onChange: function (e) {
                                    return r({
                                        highlight: e
                                    })
                                },
                                placeholder: "格式：1,2,20-22"
                            })), el(PanelBody, {
                                title: "代码组",
                                initialOpen: !1,
                                icon: "excerpt-view"
                            },
                            el("p", null, "如果需要加入代码组，请填写下面设置，相同组ID的代码将合并为代码组显示"),
                            el(TextControl, {
                                label: "代码标题",
                                value: l.title,
                                onChange: function (e) {
                                    return r({
                                        title: e
                                    })
                                },
                                placeholder: "加入组之后显示的标题"
                            }), el(TextControl, {
                                label: "自定义组id",
                                value: l.group,
                                onChange: function (e) {
                                    return r({
                                        group: e
                                    })
                                },
                                placeholder: "自定义组的id"
                            })));

                return el("div", null, el(Fragment, null, el(BlockControls, null, sm)),
                    el("div", {
                            className: "enlighter-block-wrapper"
                        },
                        el("div", {
                                className: "enlighter-header"
                            },
                            el("div", {
                                className: "enlighter-title"
                            })), el('pre', {
                                tagName: 'pre',
                                className: "enlighter-pre",
                            },
                            el("div", {
                                className: "enlighter-label"
                            }, "语言：", l.c_language, " · 主题：", l.theme ? l.theme : "跟随主题"), sp
                        ),
                        el("div", {
                            className: "enlighter-footer"
                        }), sz
                    ))

            },
            save: function (e) {
                var t = e.attributes,
                    tt = el("code", {
                            className: "gl",
                            "data-enlighter-language": t.c_language,
                            "data-enlighter-theme": t.theme,
                            "data-enlighter-highlight": t.highlight,
                            "data-enlighter-linenumbers": t.linenumbers,
                            "data-enlighter-lineoffset": t.lineoffset,
                            "data-enlighter-title": t.title,
                            "data-enlighter-group": t.group
                        },
                        t.content);
                return el("pre", {}, tt)
            }
        });
        //-------------------------------------------
        rB('zibllblock/biaoti', {
            title: 'Zibll:标题',
            icon: {
                src: 'minus',
                foreground: '#f85253'
            },
            category: 'zibll_block_cat',
            description: "和主题样式匹配的文章标题，可自定义颜色",
            className: false,
            attributes: {
                content: {
                    type: 'array',
                    source: 'children',
                    selector: 'h1',
                },
                typeClass: {
                    source: 'attribute',
                    selector: '.title-theme',
                    attribute: 'class',
                },
                color: {
                    source: 'attribute',
                    selector: 'h1',
                    attribute: 'data-color',
                }
            },
            transforms: {
                from: [{
                    type: "block",
                    blocks: ["core/heading", "core/preformatted", "core/paragraph"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("zibllblock/biaoti", {
                            content: t
                        })
                    }
                }, ],
                to: [{
                    type: "block",
                    blocks: ["core/heading"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/heading", {
                            content: t
                        })
                    }
                }, {
                    type: "block",
                    blocks: ["core/paragraph"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/paragraph", {
                            content: t
                        })
                    }
                }, {
                    type: "block",
                    blocks: ["core/preformatted"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/preformatted", {
                            content: t
                        })
                    }
                }]
            },
            edit: function (props) {
                var content = props.attributes.content,
                    typeClass = content.typeClass || 'title-theme',
                    isSelected = props.isSelected;
                color = props.attributes.color;
                sty = color && '--theme-color:' + color;

                function onChangeContent(newContent) {
                    props.setAttributes({
                        content: newContent
                    });
                }

                function changeType(event) {
                    var type = event.target.className;
                    props.setAttributes({
                        typeClass: 'title-theme ' + type
                    });
                }

                function changecolor(c) {
                    props.setAttributes({
                        color: c
                    });
                }

                var richText = el(
                    RichText, {
                        tagName: 'div',
                        onChange: onChangeContent,
                        value: content,
                        isSelected: props.isSelected,
                        placeholder: '请输入标题...'
                    });

                var outerHtml = el('div', {
                    className: typeClass,
                    'data-color': color,
                    style: {
                        color: color,
                        'border-left-color': color
                    }
                }, el('h1', {}, richText));
                var selector = el('div', {
                    className: 'g_extend anz'
                }, [
                    el('button', {
                        className: 'qe_bt_zts',
                        onClick: changeType
                    }, '主题色'),
                    el('button', {
                        className: 'qe_bt_lan',
                        onClick: changeType
                    }, '蓝色'),
                    el('button', {
                        className: 'qe_bt_hui',
                        onClick: changeType
                    }, '灰色'),
                    el('button', {
                        className: 'qe_bt_c-red',
                        onClick: changeType
                    }, '红色'),
                ]);
                var Co = el(c.ColorPalette, {
                    value: color,
                    colors: colors,
                    className: "q-ColorPalette",
                    onChange: changecolor
                });

                return el('div', {}, outerHtml,
                    el(InspectorControls, null,
                        el(PanelBody, {
                            title: "自定义颜色"
                        }),
                        el('p', {}, '默认颜色为主题高亮颜色，如需要自定义颜色，请在下方选择颜色'), el('p', {}, Co)));

            },

            save: function (props) {
                var content = props.attributes.content,
                    typeClass = props.attributes.typeClass || 'title-theme',
                    color = props.attributes.color;
                sty = color && '--theme-color:' + color;

                var outerHtml = el('h1', {
                    'data-color': color,
                    className: typeClass,
                    style: sty
                }, content);

                return outerHtml;
            }
        });
        //---------------------------------------------
        rB('zibllblock/hide-ks', {
            title: 'Zibll:隐藏内容-开始点',
            icon: {
                src: 'hidden',
                foreground: '#f85253'
            },
            description: '隐藏文章部分内容，非管理员必须要评论文章后才能查看！',
            category: 'zibll_block_cat',
            attributes: {
                typetheme: {
                    source: 'attribute',
                    selector: '.hide_n',
                    attribute: 'class',
                }
            },
            edit: function (props) {
                var isSelected = props.isSelected;
                return el('div', {
                    className: 'g_extend hide'
                }, '【 隐藏内容 起点 】', [isSelected && el('p', null, '以下内容将会被隐藏，评论后可见')], [isSelected && '这是起点，请注意添加：隐藏内容 - 终点！']);
            },
            save: function (props) {
                return '[reply]';
            },
        });

        rB('zibllblock/hide-js', {
            title: 'Zibll:隐藏内容-结束点',
            icon: {
                src: 'hidden',
                foreground: '#f85253'
            },
            description: '这是隐藏内容的结束点',
            category: 'zibll_block_cat',
            attributes: {
                typetheme: {
                    source: 'attribute',
                    selector: '.hide_n',
                    attribute: 'class',
                }
            },
            edit: function (props) {
                var isSelected = props.isSelected;
                return el('div', {
                    className: 'g_extend hide js'
                }, [isSelected && '这是结束点，请检查是否添加：隐藏内容 - 起点！'], [isSelected && el('p', null, '以上内容将会被隐藏，评论后可见')], '【 隐藏内容 终点 】');
            },
            save: function (props) {
                return '[/reply]';
            },
        });

        rB('zibllblock/payshow-ks', {
            title: 'Zibll:付费阅读-起点',
            icon: {
                src: 'cart',
                foreground: '#f85253'
            },
            description: '这是付费阅读的起点',
            category: 'zibll_block_cat',
            edit: function (props) {
                var isSelected = props.isSelected;
                return el('div', {
                    className: 'g_extend hide'
                }, [isSelected && '这是起点，请检查是否添加：付费阅读 - 起点！'], [isSelected && el('p', null, '以下内容将会被隐藏，付费后可见。此功能需要配合文章付费模式设置为 付费阅读')], '【 付费阅读 起点 】');
            },
            save: function (props) {
                return '[payshow]';
            },
        });

        rB('zibllblock/payshow-js', {
            title: 'Zibll:付费阅读-结束点',
            icon: {
                src: 'cart',
                foreground: '#f85253'
            },
            description: '这是付费阅读的结束点',
            category: 'zibll_block_cat',
            edit: function (props) {
                var isSelected = props.isSelected;
                return el('div', {
                    className: 'g_extend hide js'
                }, [isSelected && '这是结束点，请检查是否添加：付费阅读 - 起点！'], [isSelected && el('p', null, '以上内容将会被隐藏，付费后可见。此功能需要配合文章付费模式设置为 付费阅读')], '【 付费阅读 终点 】');
            },
            save: function (props) {
                return '[/payshow]';
            },
        });

        rB('zibllblock/postsbox', {
            title: 'Zibll:文章',
            icon: {
                src: 'slides',
                foreground: '#f85253'
            },attributes: {
                post_id: {
                    source: 'attribute',
                    selector: 'div',
                    attribute: 'data-pid',
                }
            },
            description: '显示一篇文章',
            category: 'zibll_block_cat',
            edit: function (props) {
                var isSelected = props.isSelected,
                content = props.attributes.post_id;

                function onChangeContent(e) {
                    props.setAttributes({
                        post_id: e
                    });
                }
                var  rti = el(
                    TextControl, {
                        tagName: 'div',
                        onChange: onChangeContent,
                        value: content,
                        type:'number',
                        placeholder: '请输入文章ID',
                        label: '请输入文章ID'
                    });
                return el('div', {
                    className: 'postsbox'
                },el('div', {className: 'postsbox-doc'}, '显示一篇文章模块'),
                rti);
            },
            save: function (props) {
                var post_id = props.attributes.post_id;
                return el('div', {'data-pid':post_id}, '[postsbox post_id="'+post_id+'"]');

            },
        });

        rB('zibllblock/quote', {
            title: 'Zibll:引言',
            icon: {
                src: 'format-quote',
                foreground: '#f85253'
            },
            description: '几种不同的引言框',
            category: 'zibll_block_cat',
            attributes: {
                content: {
                    type: 'array',
                    source: 'children',
                    selector: '.quote_q p',
                },
                typeClass: {
                    source: 'attribute',
                    selector: '.quote_q',
                    attribute: 'class',
                },
                color: {
                    source: 'attribute',
                    selector: '.quote_q',
                    attribute: 'data-color',
                }
            },
            transforms: {
                from: [{
                    type: "block",
                    blocks: ["zibllblock/alert", "core/quote", "core/preformatted", "core/paragraph"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("zibllblock/quote", {
                            content: t
                        })
                    }
                }, ],
                to: [{
                    type: "block",
                    blocks: ["core/quote"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/quote", {
                            content: t
                        })
                    }
                }, {
                    type: "block",
                    blocks: ["zibllblock/alert"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("zibllblock/alert", {
                            content: t
                        })
                    }
                }, {
                    type: "block",
                    blocks: ["core/paragraph"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/paragraph", {
                            content: t
                        })
                    }
                }, {
                    type: "block",
                    blocks: ["core/preformatted"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/preformatted", {
                            content: t
                        })
                    }
                }]
            },
            edit: function (props) {
                var content = props.attributes.content,
                    typeClass = props.attributes.typeClass || 'quote_q',
                    isSelected = props.isSelected;
                color = props.attributes.color;
                sty = color ? color : '';

                function changecolor(e) {
                    props.setAttributes({
                        color: e
                    });
                }

                function onChangeContent(e) {
                    props.setAttributes({
                        content: e
                    });
                }

                function changeType(e) {
                    var type = e.target.className;
                    props.setAttributes({
                        typeClass: 'quote_q ' + type
                    });
                }

                var richText = el(
                    RichText, {
                        tagName: 'div',
                        isSelected: props.isSelected,
                        onChange: onChangeContent,
                        value: content,
                        placeholder: '请输入内容...'
                    });
                var outerHtml = el('div', {
                    className: typeClass,
                    style: {
                        '--quote-color': sty
                    }
                },el('i', {className: "fa fa-quote-left"}), richText);


                var Co = el(c.ColorPalette, {
                    value: color || '#555',
                    colors: colors,
                    className: "q-ColorPalette",
                    onChange: changecolor
                });

                return el('div', {}, outerHtml,el('div', {},
                    el(InspectorControls, null,
                        el(PanelBody, {
                            title: "自定义颜色"
                        }),
                        el('p', {}, '默认为主题颜色，如果需自定义请在下方选择颜色（引言默认透明度为70%，请不要选择较浅的颜色，并请注意深色主题的显示效果）'),
                         el('p', {}, Co))));
            },
            save: function (props) {
                var content = props.attributes.content,
                    typeClass = props.attributes.typeClass || 'quote_q';
                color = props.attributes.color;
                sty = color && '--quote-color:' + color;

                var outerHtml = el('div', {
                    className: typeClass,
                    'data-color': color,
                    style: sty
                }, el('i', {
                    className: 'fa fa-quote-left'
                }), el('p', {}, content));

                return el('div', {}, outerHtml);

            },
        });
        //-------------------------------------------------------------
        rB('zibllblock/alert', {
            title: 'Zibll:提醒框',
            icon: {
                src: 'info',
                foreground: '#f85253'
            },
            description: '几种不同的提醒框，可选择关闭按钮',
            category: 'zibll_block_cat',
            attributes: {
                content: {
                    type: 'array',
                    source: 'children',
                    selector: 'div.alert',
                },
                typeClass: {
                    source: 'attribute',
                    selector: '.alert',
                    attribute: 'class',
                },
                isChecked: {
                    type: "attribute",
                    attribute: "data-isclose"
                }
            },
            transforms: {
                from: [{
                    type: "block",
                    blocks: ["zibllblock/quote", "core/quote", "core/preformatted", "core/paragraph"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("zibllblock/alert", {
                            content: t
                        })
                    }
                }, ],
                to: [{
                    type: "block",
                    blocks: ["core/quote"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/quote", {
                            content: t
                        })
                    }
                }, {
                    type: "block",
                    blocks: ["zibllblock/quote"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("zibllblock/quote", {
                            content: t
                        })
                    }
                }, {
                    type: "block",
                    blocks: ["core/paragraph"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/paragraph", {
                            content: t
                        })
                    }
                }, {
                    type: "block",
                    blocks: ["core/preformatted"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("core/preformatted", {
                            content: t
                        })
                    }
                }]
            },
            edit: function (props) {
                var content = props.attributes.content,
                    typeClass = props.attributes.typeClass || 'alert jb-blue',
                    isChecked = props.attributes.isChecked,
                    isSelected = props.isSelected;

                function onChangeContent(e) {
                    props.setAttributes({
                        content: e
                    });
                }

                function onisChecked(e) {
                    props.setAttributes({
                        isChecked: e
                    });
                }

                function changeType(e) {
                    var type = e.target.className;
                    props.setAttributes({
                        typeClass: 'alert ' + type
                    });
                }
                var richText = el(
                    RichText, {
                        tagName: 'div',
                        isSelected: props.isSelected,
                        onChange: onChangeContent,
                        value: content,
                        placeholder: '请输入内容...'
                    });

                var outerHtml = el('div', {
                    className: typeClass
                }, richText);

                var selector = el('span', {
                        className: 'g_extend anz alert_b'
                    }, [
                        el('button', {
                            className: 'jb-blue',
                            onClick: changeType
                        }),
                        el('button', {
                            className: 'jb-green',
                            onClick: changeType
                        }),
                        el('button', {
                            className: 'jb-yellow',
                            onClick: changeType
                        }),
                        el('button', {
                            className: 'jb-red',
                            onClick: changeType
                        }),
                    ]),
                    closebutton = el('div', {
                        className: 'close_an',
                    }, el(ToggleControl, {
                        label: '提醒框可关闭',
                        checked: isChecked,
                        onChange: onisChecked
                    }));

                return el('div', {}, [outerHtml, isChecked && el('button', {
                        className: 'close'
                    }, '×'), isSelected && selector, isSelected && closebutton],
                    el(InspectorControls, null,
                        el(PanelBody, {
                            icon: "admin-appearance",
                            title: "提醒框设置"
                        }), el('div', {}, '提醒框类型'), el('p', {}, selector), el('div', {}, '关闭按钮'), closebutton))

            },
            save: function (props) {
                var content = props.attributes.content,
                    isChecked = props.attributes.isChecked,
                    typeClass = props.attributes.typeClass || 'alert jb-blue';

                var outerHtml = el('div', {
                    className: typeClass,
                    "data-isclose": isChecked,
                    "role": 'alert'
                }, [isChecked && el('button', {
                    'type': 'button',
                    className: 'close',
                    'data-dismiss': 'alert',
                    'aria-label': 'Close'
                }, el('div', {
                    'data-class': "ic-close",
                    'data-svg': "close",
                    'data-viewbox': "0 0 1024 1024"
                }))], content);
                return el('div', {
                    className: 'alert-dismissible fade in'
                }, outerHtml);
            },
        });
        //-------------------------------------------------------------
        rB('zibllblock/buttons', {
            title: 'Zibll:按钮组',
            description: '多种样式的按钮',
            icon: {
                src: 'marker',
                foreground: '#f85253'
            },
            category: 'zibll_block_cat',
            attributes: {
                alignment: {
                    type: 'string',
                },
                quantity: {
                    type: "attribute",
                    attribute: "data-quantity",
                    default: 1
                },
                radius: {
                    type: "attribute",
                    attribute: "data-radius",
                    default: true
                },
                content1: {
                    type: 'array',
                    source: 'children',
                    selector: 'span.an_1',
                },
                typeClass1: {
                    source: 'attribute',
                    selector: '.an_1',
                    attribute: 'class',
                },
                content2: {
                    type: 'array',
                    source: 'children',
                    selector: 'span.an_2',
                },
                typeClass2: {
                    source: 'attribute',
                    selector: '.an_2',
                    attribute: 'class',
                },
                content3: {
                    type: 'array',
                    source: 'children',
                    selector: 'span.an_3',
                },
                typeClass3: {
                    source: 'attribute',
                    selector: '.an_3',
                    attribute: 'class',
                },
                content4: {
                    type: 'array',
                    source: 'children',
                    selector: 'span.an_4',
                },
                typeClass4: {
                    source: 'attribute',
                    selector: '.an_4',
                    attribute: 'class',
                },
                content5: {
                    type: 'array',
                    source: 'children',
                    selector: 'span.an_5',
                },
                typeClass5: {
                    source: 'attribute',
                    selector: '.an_5',
                    attribute: 'class',
                }
            },
            transforms: {
                from: [{
                    type: "block",
                    blocks: ["core/paragraph"],
                    transform: function (e) {
                        var t = e.content;
                        return createBlock("zibllblock/buttons", {
                            content1: t
                        })
                    }
                }, ],
                to: [{
                    type: "block",
                    blocks: ["core/paragraph"],
                    transform: function (e) {
                        var t = e.content1;
                        return createBlock("core/paragraph", {
                            content: t
                        })
                    }
                }]
            },
            edit: function (props) {
                var at = props.attributes,
                    sa = props.setAttributes,
                    alignment = at.alignment,
                    isS = props.isSelected,
                    sl = at.quantity,
                    rd = at.radius,
                    c = [];

                for (let i = 0; i <= 5; i++) {
                    c['c' + i] = at['content' + i],
                        c['cs' + i] = at['typeClass' + i] || 'but b-blue',
                        c['rt' + i] = el(
                            RichText, {
                                tagName: 'div',
                                onChange: function (e) {
                                    sa({
                                        ['content' + i]: e
                                    })
                                },
                                value: c['c' + i],
                                isSelected: props.isS,
                                placeholder: '按钮-' + i
                            }),
                        c['crt' + i] = el('div', {
                            className: c['cs' + i],
                        }, c['rt' + i]),
                        c['bk' + i] = el('button', {
                            className: 'anz sz',
                            onClick: function (e) {
                                $('.anz.an.x' + i).slideToggle(200)
                            }
                        }, el('span', {
                            className: 'dashicons dashicons-admin-appearance'
                        })),
                        c['btt' + i] = el('div', {
                                className: 'g_extend anz an x' + i
                            },
                            el('button', {
                                className: 'but b-red',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but b-yellow',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but b-blue',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but b-green',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but b-purple',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but hollow c-red',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but hollow c-yellow',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but hollow c-blue',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but hollow c-green',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but hollow c-purple',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but jb-red',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but jb-yellow',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]: 'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but jb-blue',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]: 'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but jb-green',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]: 'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                            el('button', {
                                className: 'but jb-purple',
                                onClick: function (e) {
                                    sa({
                                        ['typeClass' + i]:'an_' + i + ' ' + e.target.className
                                    })
                                }
                            }, ''),
                        );
                }

                var gjl = el(Toolbar, {}, el(DropdownMenu, {
                        icon: "plus-alt",
                        className: 'zibllblock-buttons-sl',
                        label: "按钮数量",
                        controls: [{
                                title: '1个',
                                value: 1,
                                onClick: function (e) {
                                    sa({
                                        quantity: 1
                                    })
                                }
                            }, {
                                title: '2个',
                                onClick: function (e) {
                                    sa({
                                        quantity: 2
                                    })
                                }
                            }, {
                                title: '3个',
                                value: 3,
                                onClick: function (e) {
                                    sa({
                                        quantity: 3
                                    })
                                }
                            }, {
                                title: '4个',
                                value: 4,
                                onClick: function (e) {
                                    sa({
                                        quantity: 4
                                    })
                                }
                            }, {
                                title: '5个',
                                value: 5,
                                onClick: function (e) {
                                    sa({
                                        quantity: 5
                                    })
                                }
                            }

                        ]
                    })),
                    dqk = el(Fragment, null, el(BlockControls, null, gjl, el(AlignmentToolbar, {
                        value: alignment,
                        onChange: function (e) {
                            sa({
                                alignment: e
                            })
                        }
                    })));

                return el('div', {
                        style: {
                            textAlign: alignment
                        },
                        className: 'aniuzu ' + (rd  && 'radius')
                    }, dqk,
                    [c.crt1, isS && c.bk1, isS && c.btt1],
                    [sl >= 2 && [c.crt2, isS && c.bk2, isS && c.btt2]],
                    [sl >= 3 && [c.crt3, isS && c.bk3, isS && c.btt3]],
                    [sl >= 4 && [c.crt4, isS && c.bk4, isS && c.btt4]],
                    [sl >= 5 && [c.crt5, isS && c.bk5, isS && c.btt5]],
                    el(InspectorControls, null,
                        el(PanelBody, {
                            icon: "admin-generic",
                            title: "按钮设置"
                        }, el(RangeControl, {
                            label: "按钮数量",
                            value: sl,
                            onChange: function (e) {
                                sa({
                                    quantity: e
                                })
                            },
                            min: "1",
                            max: "5"
                        }), el(ToggleControl, {
                            className: 'close_an',
                            label: '按钮圆角',
                            checked: rd,
                            onChange: function (e) {
                                sa({
                                    radius: e
                                })
                            }
                        }))
                    ));
            },
            save: function (props) {
                var at = props.attributes,
                    sa = props.setAttributes,
                    alignment = at.alignment,
                    isSelected = props.isSelected,
                    sl = at.quantity,
                    rd = at.radius,
                    c = [];

                for (let i = 0; i <= 5; i++) {
                    c[i] = at['content' + i],
                        c['s' + i] = at['typeClass' + i] || 'an_' + i + ' but b-blue',
                        c['jg' + i] = el('span', {
                            className: c['s' + i]
                        }, c[i]);
                }
                return outerHtml = el('div', {
                        "data-quantity": sl,
                        "data-radius": rd,
                        style: {
                            textAlign: alignment
                        },
                        className: rd && 'radius'
                    },
                    [sl > 0 && c.jg1], [sl > 1 && c.jg2], [sl > 2 && c.jg3], [sl > 3 && c.jg4], [sl > 4 && c.jg5]
                );
            },
        });
        //-------------------------------------------------------------
        rB('zibllblock/carousel', {
            title: 'Zibll:幻灯片',
            description: '选择图片生成幻灯片',
            icon: {
                src: 'images-alt2',
                foreground: '#f85253'
            },
            category: 'zibll_block_cat',
            attributes: {
                center: {
                    type: 'string',
                    selector: 'div',
                    source: 'attribute',
                    attribute: 'data-cen',
                    default: true
                },
                interval: {
                    type: 'string',
                    selector: '.carousel',
                    source: 'attribute',
                    attribute: 'data-interval',
                    default: 4000
                },
                limitedwidth: {
                    type: 'string',
                    selector: 'div',
                    source: 'attribute',
                    attribute: 'data-liw',
                    default: true
                },
                maxwidth: {
                    type: 'string',
                    source: 'attribute',
                    selector: 'div',
                    attribute: 'data-mw',
                    default: 600
                },
                effect: {
                    type: 'string',
                    selector: '.carousel',
                    source: 'attribute',
                    attribute: 'data-effect',
                    default: ''
                },
                jyloop: {
                    type: 'string',
                    selector: '.carousel',
                    source: 'attribute',
                    attribute: 'data-jyloop',
                },
                id: {
                    type: 'string',
                    selector: '.carousel',
                    source: 'attribute',
                    attribute: 'id',
                },
                proportion: {
                    type: 'string',
                    selector: '.carousel',
                    source: 'attribute',
                    attribute: 'proportion',
                    default: '0.6'
                }
            },
            edit: function (props) {
                var at = props.attributes,
                    isS = props.isSelected,
                    liw = at.limitedwidth,
                    int = at.interval,
                    cn = at.center,
                    mw = at.maxwidth,
                    eff = at.effect,
                    lop = at.jyloop,
                    sa = props.setAttributes,
                    c = {},

                    noticeUI = props.noticeUI;

                var sjs = parseInt((Math.random() + 1) * Math.pow(10, 4));

                if (!at.id) {
                    sa({
                        id: sjs
                    })
                }
                const TEMPLATE = [
                    ['core/gallery', {
                        linkTo: 'media',
                        columns: '8'
                    }]
                ];

                var xzk = el(InnerBlocks, {
                        allowedBlocks: ['core/gallery'],
                        templateLock: '',
                        template: TEMPLATE
                    }, ''),
                    inhg = el(RangeControl, {
                        label: "切换时间（秒）",
                        value: int / 1000,
                        onChange: function (e) {
                            sa({
                                interval: e * 1000
                            })
                        },
                        min: "1",
                        max: "20"
                    }),
                    jzxh = el(ToggleControl, {
                        label: '禁用循环',
                        checked: lop,
                        onChange: function (e) {
                            sa({
                                jyloop: e
                            })
                        }
                    }),
                    wdxz = el(ToggleControl, {
                        label: '限制最大宽度',
                        checked: liw,
                        onChange: function (e) {
                            sa({
                                limitedwidth: e
                            })
                        }
                    }),
                    jza = el(ToggleControl, {
                        label: '居中显示',
                        checked: cn,
                        onChange: function (e) {
                            sa({
                                center: e
                            })
                        }
                    }),
                    mwhg = el(RangeControl, {
                        label: "最大宽度",
                        value: mw,
                        onChange: function (e) {
                            sa({
                                maxwidth: e
                            })
                        },
                        min: "200",
                        max: "1500"
                    }),
                    eeff = el(SelectControl, {
                        label: "切换动画",
                        value: eff,
                        onChange: function (e) {
                            sa({
                                effect: e
                            })
                        },
                        options: [{
                            label: '滑动',
                            value: ''
                        }, {
                            label: '淡出淡入',
                            value: 'fade'
                        }, {
                            label: '3D方块',
                            value: 'cube'
                        }, {
                            label: '3D滑入',
                            value: 'coverflow'
                        }, {
                            label: '3D翻转',
                            value: 'flip'
                        }],
                    });

                return el('div', {
                        className: 'carousel iss'
                    }, el('div', {
                            className: 'leab'
                        }, 'Zibll:幻灯片',
                        el('span', {
                            className: 'dashicons dashicons-admin-generic'
                        }),
                    ),
                    xzk,
                    el(InspectorControls, null,
                        el(PanelBody, {
                                title: "幻灯片设置"
                            }, eeff,
                            el(SelectControl, {
                                label: "保持长宽比例",
                                value: at.proportion,
                                options: [{
                                    label: '禁用',
                                    value: ''
                                }, {
                                    label: '横版-3:1',
                                    value: '0.333'
                                }, {
                                    label: '横版-5:2',
                                    value: '0.4'
                                }, {
                                    label: '横版-2:1',
                                    value: '0.5'
                                }, {
                                    label: '横版-5:3',
                                    value: '0.6'
                                }, {
                                    label: '横版-4:3',
                                    value: '0.75'
                                }, {
                                    label: '横版-5:4',
                                    value: '0.75'
                                }, {
                                    label: '横版-8:7',
                                    value: '0.875'
                                }, {
                                    label: '正方形-1:1',
                                    value: '1'
                                }, {
                                    label: '竖版-7:8',
                                    value: '1.142'
                                }, {
                                    label: '竖版-4:5',
                                    value: '1.25'
                                }, {
                                    label: '竖版-3:4',
                                    value: '1.333'
                                }, {
                                    label: '竖版-3:5',
                                    value: '1.666'
                                }, {
                                    label: '竖版-1:2',
                                    value: '2'
                                }, {
                                    label: '竖版-2:5',
                                    value: '2.5'
                                }, {
                                    label: '竖版-1:3',
                                    value: '3'
                                }],
                                onChange: function (e) {
                                    sa({
                                        proportion: e
                                    })
                                }
                            }), inhg, jzxh, wdxz,
                            liw && [mwhg, jza], el("p", null, "如果幻灯片内的图片尺寸不一致，建议开启限制最大宽度，再结合长宽比例能显示更好的效果")
                        )));

            },

            save: function (props) {
                var at = props.attributes,
                    liw = at.limitedwidth ? 'true' : '',
                    cn = at.center ? 'true' : '',
                    mw = at.maxwidth,
                    int = at.interval,
                    eff = at.effect,
                    lop = at.jyloop ? 'true' : '',
                    mar = liw && cn && '10px auto' || '',
                    mww = liw && mw + 'px' || '',
                    id = at.id;

                var dhl = el('div', {
                        className: 'swiper-button-next'
                    }),
                    dhr = el('div', {
                        className: 'swiper-button-prev'
                    }),
                    zsq = el('div', {
                        className: 'swiper-pagination'
                    });

                return el('div', {
                        "data-mw": mw,
                        "data-liw": liw,
                        "data-cen": cn,
                        className: 'wp-block-carousel'
                    },
                    el('div', {
                            className: 'carousel slide',
                            'data-effect': eff,
                            'data-jyloop': lop,
                            'data-interval': int,
                            'id': id,
                            'proportion': at.proportion,
                            style: {
                                'max-width': mww,
                                'margin': mar
                            }
                        },
                        el(InnerBlocks.Content), dhl, dhr, zsq));
            }
        });

        //-------------------------------------------------------------
        //-------------------------------------------------------------

    })
})(jQuery);