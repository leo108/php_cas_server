/**
 * Created by leo108 on 16/9/20.
 */
$(document).ready(function () {
    var $pwdDialog = $('#change-pwd-dialog');
    $('#btn_logout').click(function () {
        bootbox.confirm(Laravel.trans('message.confirm_logout'), function (ret) {
            if (ret) {
                location.href = Laravel.router('logout');
            }
        });
    });

    $('#btn_change_pwd').click(function () {
        $pwdDialog.modal();
    });

    $('#btn-save-pwd').click(function () {
        $pwdDialog.find('div.form-group').removeClass('has-error');
        var map = {
            'old': 'old-pwd',
            'new1': 'new-pwd',
            'new2': 'new-pwd2'
        };
        var val = {};

        for (var x in map) {
            var $input = $('#' + map[x]);
            val[x] = $input.val();
            if ($input.val() == '') {
                $input.closest('div.form-group').addClass('has-error');
            }
        }

        if (val['new1'] != val['new2']) {
            $('#new-pwd2').closest('div.form-group').addClass('has-error');
        }

        if ($pwdDialog.find('.has-error').length > 0) {
            return;
        }

        var req = {
            'old': val['old'],
            'new': val['new1'],
        };

        $.post(Laravel.router('change_pwd'), req, function (ret) {
            alert(ret.msg);
            $pwdDialog.modal('hide');
        }, 'json');
    });
});