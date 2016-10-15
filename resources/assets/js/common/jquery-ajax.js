/**
 * Created by leo108 on 16/8/29.
 */

(function (factory) {
    factory(jQuery);
}(function ($, undefined) {
    $.each(["post", "get", "put", "delete"], function (i, method) {
        $[method] = function (url, data, callback, type) {

            // Shift arguments if data argument was omitted
            if ($.isFunction(data)) {
                type = type || callback;
                callback = data;
                data = undefined;
            }

            // The url can be an options object (which then must have .url)
            return $.ajax($.extend({
                url: url,
                type: method,
                dataType: type,
                data: data,
                success: callback,
                headers: {
                    'X-CSRF-TOKEN': Laravel.csrfToken
                }
            }, $.isPlainObject(url) && url));
        };
    });
}));
