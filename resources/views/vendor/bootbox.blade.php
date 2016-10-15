<script>
    (function () {
        var locate = {
            'OK': '@lang('common.ok')',
            'CANCEL': '@lang('common.cancel')',
            'CONFIRM': '@lang('common.confirm')'
        };
        bootbox.addLocale('_laravel', locate);
        bootbox.setLocale('_laravel');
    })();
</script>