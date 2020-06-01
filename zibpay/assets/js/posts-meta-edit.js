jQuery(document).ready(function () {
    var $ = jQuery;
    if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
        $(document).on('click', '.zibpay-add-media', function (e) {
            e.preventDefault();
            var button = $(this);
            var id = $('[name="posts_zibpay[pay_download]"]');
            wp.media.editor.send.attachment = function (props, attachment) {
                //console.log(attachment)
                if ($.trim(id.val()) != '') {
                    id.val(id.val() + '\n' + attachment.url);
                } else {
                    id.val(attachment.url);
                }
            };
            wp.media.editor.open(button);
            return false;
        });
    }

    $(".zibpay-add-file").click(function () {
        _this = $(this);
        if(_this.attr('disabled')) return;
        _this.attr('disabled', true);
        $("body").append('<form style="display:none" id="zibpayFileForm" action="' + zibpay.ajax_url + '" enctype="multipart/form-data" method="post"><input type="hidden" name="action" value="zibpay_file_upload"><input type="file" id="zibpayFile" name="zibpayFile"></form>');
        $("#zibpayFile").trigger('click');
        $("#zibpayFile").change(function () {
            $("#zibpayFileForm").ajaxSubmit({
                //dataType:  'json',
                beforeSend: function () {},
                uploadProgress: function (event, position, total, percentComplete) {

                    if(percentComplete < 100 ){
                        progress = '正在上传：'+percentComplete + '%';
                    }else{
                        progress = '上传完成，正在处理';
                    }
                    $('#file-progress').text(progress);
                },
                success: function (data) {
                    // console.log(data);
                    $('#zibpayFileForm').remove();
                    var id = $('[name="posts_zibpay[pay_download]"]');

                    var olddata = id.val();
                    if ($.trim(olddata)) {
                        id.val(olddata + '\n' + data);
                    } else {
                        id.val(data);
                    }
                    $('#file-progress').text('处理完成');
                    _this.attr('disabled', false);
                },
                error: function (xhr) {
                    $('#zibpayFileForm').remove();
                    alert('上传失败！');
                    $('#file-progress').text('上传失败');
                    _this.attr('disabled', false);
                }
            });

        });
    });

    $('[name="posts_zibpay[pay_type]"]').change(function () {

        pay_edit_checked_show($(this));

    })
    pay_edit_checked_show();
    function pay_edit_checked_show(_this) {
        _this = _this || $('[name="posts_zibpay[pay_type]"]:checked');
        _val = _this.val();
        if (_val != 'no') {
            $('.pay-hide').slideDown();
        } else {
            $('.pay-hide').slideUp();
        }
        if (_val == 2 || _val == 3) {
            $('.pay-download').slideDown();
        } else {
            $('.pay-download').slideUp();
        }
    }

});