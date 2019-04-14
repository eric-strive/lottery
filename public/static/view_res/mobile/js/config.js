
var shoproot = location.pathname.substr(0, location.pathname.lastIndexOf('/') + 1);

require.config({
    paths: {
        util: 'util',        
        Spinner: 'spin.min',
        jquery: 'jquery-2.1.1.min',
        config: 'config',       
    },
    shim: {
        'util': {
            exports: 'util'
        },        
        'jquery': {
            exports: '$',
            deps: ['config']
        },
         'Spinner': {
            exports: 'Spinner',
            deps: ['util']
        },
    },
    // urlArgs: "bust=1.5.3",
    urlArgs: "bust=" + (new Date()).getMonth().toString() + (new Date()).getDay().toString() + (new Date()).getHours().toString(),
    xhtml: true
});

define([], function () {
    var config = {};

    return config;
});