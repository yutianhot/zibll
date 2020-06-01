    function grin_code(tag, code) {
    	code = $('#grin_code').val();
    	if (code.length < 2) {
    		notyf('请输入代码', 'warning');
    		return;
    	}
    	code = $('.code-text').text(code).html();
    	grin(code, 'code');
    	popover_hide(".popover-focus");

    }

    function grin_image(tag, code) {
		url = $('#grin_image').val();
		if (url.length < 5) {
    		notyf('请输入正确的图片地址', 'warning');
    		return;
		}
    		_img = '[img=' + url + ']\n';
    		grin(_img, 'kb');
    }

    function grin(tag, code) {
    	var myField = document.getElementById('comment');
    	if (code == 'code') {
    		tag = '<pre><code class="gl">' + tag + '</code></pre>';
    	} else if (code == 'kb') {
    		tag = tag;
    	} else {
    		tag = '[g=' + tag + ']';
    	}

    	if (document.selection) {
    		myField.focus();
    		sel = document.selection.createRange();
    		sel.text = tag;
    		myField.focus();
    	} else if (myField.selectionStart || myField.selectionStart == '0') {
    		var startPos = myField.selectionStart;
    		var endPos = myField.selectionEnd;
    		var cursorPos = endPos;
    		myField.value = myField.value.substring(0, startPos) +
    			tag +
    			myField.value.substring(endPos, myField.value.length);
    		cursorPos += tag.length;
    		myField.focus();
    		myField.selectionStart = cursorPos;
    		myField.selectionEnd = cursorPos;
    	} else {
    		myField.value += tag;
    		myField.focus();
    	}
    	popover_hide(".popover-focus");
    };
    tbfine(function () {
    	return {
    		ajax: function () {},
    		init: function () {
    			_win.bd.on('click', '.comment-reply-link', function () {
    				return addComment.moveForm("div-comment-" + $(this).attr("data-commentid"), $(this).attr("data-commentid"), "respond", $(this).attr("data-postid")),
    					scrollTo($(this), -100),
    					!1;
    			});
    			$('.comment-user-change').on('click', function () {
    				$('#comment-user-info').fadeToggle(300).find('#author').focus();
    			})
    			$("#comment-user-info .sign-mask").on('click', function () {
    				$('#comment-user-info').fadeOut(300)
    			})

    			$a = ['aoman', 'baiyan', 'bishi', 'bizui', 'cahan', 'ciya', 'dabing', 'daku', 'deyi', 'doge', 'fadai', 'fanu', 'fendou', 'ganga', 'guzhang', 'haixiu', 'hanxiao', 'zuohengheng', 'zhuakuang', 'zhouma', 'zhemo', 'zhayanjian', 'zaijian', 'yun', 'youhengheng', 'yiwen', 'yinxian', 'xu', 'xieyanxiao', 'xiaoku', 'xiaojiujie', 'xia', 'wunai', 'wozuimei', 'weixiao', 'weiqu', 'tuosai', 'tu', 'touxiao', 'tiaopi', 'shui', 'se', 'saorao', 'qiudale', 'se', 'qinqin', 'qiaoda', 'piezui'];
    			$b = ['penxue', 'nanguo', 'liulei', 'liuhan', 'lenghan', 'leiben', 'kun', 'kuaikule', 'ku', 'koubi', 'kelian', 'keai', 'jingya', 'jingxi', 'jingkong', 'jie', 'huaixiao', 'haqian', 'aini', 'OK', 'qiang', 'quantou', 'shengli', 'woshou', 'gouyin', 'baoquan', 'aixin', 'bangbangtang', 'xiaoyanger', 'xigua', 'hexie', 'pijiu', 'lanqiu', 'juhua', 'hecai', 'haobang', 'caidao', 'baojin', 'chi', 'dan', 'kulou', 'shuai', 'shouqiang', 'yangtuo', 'youling'];

    			var _s1 = '',
    				_s2 = '';
    			for ($i = 0; $i < $a.length; $i++) {
    				_s1 += '<a class="smilie-a" href="javascript:grin(\'' + $a[$i] + '\')" ><img src="' + _win.uri + '/img/smilies/' + $a[$i] + '.gif" /></a>';
    			}
    			for ($i = 0; $i < $b.length; $i++) {
    				_s2 += '<a class="smilie-a" href="javascript:grin(\'' + $b[$i] + '\')" ><img src="' + _win.uri + '/img/smilies/' + $b[$i] + '.gif" /></a>';
    			}
    			tab = '<div class="smilie-tab pull-right"><a href="#smilie-tab-1" class="but mr10" data-toggle="tab"><i class="fa fa-chevron-left" aria-hidden="true"></i></a><a class="but" href="#smilie-tab-2" data-toggle="tab"><i class="fa fa-chevron-right"></i></a></div>';
    			nr = '<div class="tab-content" style="min-height:328px"><div class="tab-pane fade in active" id="smilie-tab-1">' + _s1 + '</div><div class="tab-pane fade" id="smilie-tab-2">' + _s2 + '</div></div>';

    			$(".comt-smilie").popover({
    				placement: 'auto top',
    				html: true,
    				content: nr + tab
    			});
    			c_i = '\
				<p>请填写图片链接：</p>\
				<div class="popover-comt-image">\
				<p><textarea rows="3" tabindex="1" id="grin_image" class="form-control input-textarea" placeholder="http://..."></textarea></p>\
					<div class="popover-button text-right">\
						<a type="button" class="but c-blue" href="javascript:grin_image()"></i>确认</a>\
					</div>\
				</div>';
    			$(".comt-image").popover({
    				placement: 'auto top',
    				html: true,
    				content: c_i
    			});

    			c_i = '\
				<p>请输入代码：</p>\
				<div style=" min-width: 240px;">\
				<p><textarea rows="6" tabindex="1" id="grin_code" class="form-control input-textarea" style="height:181px;" placeholder="在此处粘贴代码"></textarea></p>\
					<div class="code-text hide"></div>\
					<div class="popover-button text-right">\
						<a type="button" class="but c-blue" href="javascript:grin_code()"></i>确认</a>\
					</div>\
				</div>';

    			$(".comt-code").popover({
    				placement: 'auto top',
    				html: true,
    				content: c_i
    			});

    			/*
    			 * comment
    			 * ====================================================
    			 */

    			$comments = $('#comments-title');
    			$cancel = $('#cancel-comment-reply-link');
    			$author = $('#comment-user-info');
    			$submit = $('#commentform #submit');
    			$cancel = $('#cancel-comment-reply-link');

    			$com_list = $('#postcomments .commentlist');

    			$('#commentform').submit(function () {
    				var inputs = $(this).serializeObject();
    				popover_hide(".popover-focus");

    				if ($author.length && $author.attr('require_name_email')) {
    					if (inputs.author.length < 2 || inputs.email.length < 4) {
    						notyf('请输入昵称和邮箱', 'warning');
    						$author.fadeIn(300);
    						$('#author').focus();
    						return false
    					}
    					if (!is_mail(inputs.email)) {
    						notyf('邮箱格式错误', 'warning');
    						$author.fadeIn(300);
    						$('#email').focus();
    						return false
    					}
    				}

    				if (inputs.comment.length < 6) {
    					notyf('评论内容过少', 'warning');
    					return false
    				}

    				$.ajax({
    					url: _win.uri + '/action/comment.php',
    					data: $(this).serialize(),
    					type: "POST",
    					beforeSend: function () {
    						notyf("正在处理请稍等...", "load", "", "comment_ajax");
    						$submit.attr('disabled', true).fadeTo('slow', 0.5);
    						$author.fadeOut(300);
    						// console.log(inputs)
    					},
    					error: function (request) {
    						_time = 3000;
    						if (request.statusText.indexOf("imeout") != -1) {
    							request.responseText = '连接超时，请刷新页面重试';
    							_time = 20000;
    						}
    						notyf(request.responseText, "danger", "", "comment_ajax");
    						setTimeout(function () {
    								$submit.attr('disabled', false).fadeTo('slow', 1);
    							},
    							_time)
    					},
    					success: function (data) {
    						data = inputs.comment_parent != 0 ? '<ul class="children">' + data + '</ul>' : data;
    						respond = $('#respond');
    						is_comment_parent = respond.parent().parent('.comment');
    						//console.log(data);
    						if (is_comment_parent.length) {
    							is_comment_parent.after(data);
    						} else {
    							$com_list.length && $com_list.prepend(data);
    							if (!$com_list.length) {
    								respond.after('<div id="postcomments"><ol class="commentlist list-unstyled">' + data + '</ol></div>');
    							}
    						}
    						notyf('提交成功！', "", "", "comment_ajax");
    						auto_fun();
    						$cancel.click();
    						countdown();
    						$('#comment').val('');
    					}
    				});
    				return false;
    			});


    			addComment = {
    				moveForm: function (commId, parentId, respondId, postId, num) {
    					var t = this,
    						div, comm = t.I(commId),
    						respond = t.I(respondId),
    						cancel = t.I('cancel-comment-reply-link'),
    						parent = t.I('comment_parent'),
    						post = t.I('comment_post_ID');
    					num && (t.I('comment').value = comm_array[num],
    						edit = t.I('new_comm_' + num).innerHTML.match(/(comment-)(\d+)/)[2],
    						$new_sucs = $('#success_' + num),
    						$new_sucs.hide(),
    						$new_comm = $('#new_comm_' + num),
    						$new_comm.hide(),
    						$cancel.text('取消编辑'));

    					t.respondId = respondId;
    					postId = postId || false;

    					if (!t.I('wp-temp-form-div')) {
    						div = document.createElement('div');
    						div.id = 'wp-temp-form-div';
    						div.style.display = 'none';
    						respond.parentNode.insertBefore(div, respond)
    					}!comm ? (temp = t.I('wp-temp-form-div'),
    						t.I('comment_parent').value = '0',
    						temp.parentNode.insertBefore(respond, temp),
    						temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);

    					// pcsheight()
    					if (post && postId) post.value = postId;
    					parent.value = parentId;
    					cancel.style.display = '';
    					cancel.onclick = function () {
    						var t = addComment,
    							temp = t.I('wp-temp-form-div'),
    							respond = t.I(t.respondId);
    						t.I('comment_parent').value = '0';
    						if (temp && respond) {
    							temp.parentNode.insertBefore(respond, temp);
    							temp.parentNode.removeChild(temp)
    						}
    						this.style.display = 'none';
    						this.onclick = null;
    						return false
    					};
    					try {
    						t.I('comment').focus()
    					} catch (e) {}
    					return false
    				},
    				I: function (e) {
    					return document.getElementById(e)
    				}
    			};

    			function exit_prev_edit() {
    				$new_comm.show();
    				$new_sucs.show();
    				$('textarea').each(function () {
    					this.value = ''
    				});
    				edit = ''
    			}
    			var wait = 15,
    				$submit_html = $submit.html();

    			function countdown() {
    				if (wait > 0) {
    					$submit.html('<i class="loading"></i><span style="display:inline-block;width:30px;text-align:right">' + wait + '</span>');
    					wait--;
    					setTimeout(countdown, 1000)
    				} else {
    					$submit.html($submit_html).attr('disabled', false).fadeTo('slow', 1);
    					wait = 15
    				}
    			}
    		}
    	}

    })