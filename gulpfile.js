var elixir = require('laravel-elixir');
var glob = require('glob');

elixir.config.js.browserify.transformers.push({
    name: 'vueify',
    options: {}
});

var getEntries = function (globPath, replace) {
    var entries = {};

    if (typeof globPath == 'string') {
        globPath = [globPath];
    }

    for (var x in globPath) {
        glob.sync(globPath[x]).forEach(function (entry) {
            var basename = entry.replace(replace, '');
            entries[basename] = entry;
        });
    }
    return entries;
};

elixir(function (mix) {
    var browserifyBatch = function (src) {
        var base = elixir.config.assetsPath + '/js/';
        var entries = getEntries(base + src, base);
        for(var x in entries){
            var p = entries[x].replace(base, '');
            mix.browserify(p, elixir.config.publicPath + '/js/' + x);
        }
    };
    browserifyBatch('front/**/*.js');
    browserifyBatch('admin/**/*.js');
    mix
        .less('sb-admin-2.less')
        .sass('app.scss')
        .browserify('common/common.js')
        .version(['css/**/*.css', 'js/**/*.js'])
        .copy('node_modules/font-awesome/fonts', 'public/build/fonts')
    ;
});
