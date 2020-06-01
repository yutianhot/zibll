tbfine(function () {
	return {
		init: function () {
			/*--图形验证--*/
			if ($("[id*='user_yz_canvas']").length) {
				var show_num1 = [],
					show_num2 = [],
					name1 = 'user_yz_canvas1',
					name2 = 'user_yz_canvas2';
				draw(name1, show_num1);
				draw(name2, show_num2);

				$("#user_yz_canvas1").on('click', function () {
					draw(name1, show_num1);
				})
				$("#user_yz_canvas2").on('click', function () {
					draw(name2, show_num2);
				})

				function draw(o, t) {
					var a = $("#" + o).attr("width"),
						r = $("#" + o).attr("height");
					var n = document.getElementById(o),
						e = n.getContext("2d");
					n.width = a, n.height = r;
					for (var l = "A,B,C,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,W,X,Y,Z,1,2,3,4,5,6,7,8,9,0", h = l.split(","), d = h.length, m = 0; m <= 3; m++) {
						var M = Math.floor(Math.random() * d),
							i = 30 * Math.random() * Math.PI / 180,
							s = h[M];
						t[m] = s.toLowerCase();
						var f = 10 + 20 * m,
							g = 20 + 8 * Math.random();
						e.font = "bold 23px 微软雅黑", e.translate(f, g), e.rotate(i), e.fillStyle = randomColor(),
							e.fillText(s, 0, 0), e.rotate(-i), e.translate(-f, -g);
					}
					for (var m = 0; m <= 5; m++) e.strokeStyle = randomColor(), e.beginPath(), e.moveTo(Math.random() * a, Math.random() * r),
						e.lineTo(Math.random() * a, Math.random() * r), e.stroke();
					for (var m = 0; m <= 30; m++) {
						e.strokeStyle = randomColor(), e.beginPath();
						var f = Math.random() * a,
							g = Math.random() * r;
						e.moveTo(f, g), e.lineTo(f + 1, g + 1), e.stroke();
					}
				}

				function randomColor() {
					return "rgb(" + Math.floor(230 * Math.random() + 20) + "," + Math.floor(190 * Math.random() + 30) + "," + Math.floor(190 * Math.random() + 30) + ")";
				}
			}

			$('input[name=canvas_yz]').on('input porpertychange', function () {
				val = $(this).val().toLowerCase(),
					num1 = show_num1.join(""),
					num2 = show_num2.join("");
				if (val == num1 || val == num2) {
					$(this).siblings('.match-ok').addClass('show')
				} else {
					$(this).siblings('.match-ok').removeClass('show')
				}
			})

			$('.sign form').keydown(function (e) {
				var e = e || event,
					keycode = e.which || e.keyCode;
				if (keycode == 13) {
					$(this).find('.signsubmit-loader').trigger("click");
				}
			})

			$('input[name=email]').on('input porpertychange', function () {
				val = $(this).val();
				if (val.length>5) {
					$(this).parents('form').find('.signup-captch').slideDown()
				}
			})

			$(".signsubmit-loader").on("click", function () {
				if (!_win.is_signin) {
					var _this = $(this),
						n = _this.parents('form'),
						o = n.serializeObject(),
						e = "signup" == o.action;
					if (o.action) {
						if (e) {
							if ($("#user_yz_canvas1").length) {
								var r = o.canvas_yz.toLowerCase(),
									a = show_num2.join("");
								if ("" == r) return notyf("请输入图形验证码", "danger");
								if (r !== a) return notyf("图形验证码错误！请重新输入", "danger"), void draw(name2, show_num2);
							}
							if (!/^[a-zA-Z0-9_\u4e00-\u9fa5]+$/.test(o.name)) return void notyf("用户名由汉字、字母、数字及下划线组成！", "danger");
						} else {
							if ($("#user_yz_canvas1").length) {
								var r = o.canvas_yz.toLowerCase(),
									a = show_num1.join("");
								if ("" == r) return notyf("请输入图形验证码", "danger");
								if (r !== a) return notyf("图形验证码错误！请重新输入！", "danger"), void draw(name1, show_num1);
							}
						}
						_this.attr('disabled', true);
						notyf("正在处理请稍等...", "load", "", "sign-register"), $.ajax({
							type: "POST",
							url: _win.uri + "/action/sign_register.php",
							data: o,
							dataType: "json",
							success: function (n) {
								_this.attr('disabled', false);
								//console.log(n);
								n.msg && notyf(n.msg, (n.error ? 'danger' : ""), "", "sign-register"),
									n.error || (location.reload(),
										n.goto && (location.href = n.goto));
							}
						});
					}
				}
			})
		}
	}

})