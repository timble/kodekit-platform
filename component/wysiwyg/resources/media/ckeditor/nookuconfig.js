// get path of directory ckeditor
var basePath = CKEDITOR.basePath;
basePath = basePath.substr(0, basePath.indexOf("ckeditor/"));

//load external plugin
(function() {
    CKEDITOR.plugins.addExternal('readmore','nookuplugins/readmore/', 'plugin.js');
})();

// config for toolbar, extraPlugins,...
CKEDITOR.editorConfig = function( config )
{
    config.extraPlugins = 'readmore';
//     Can use default toolbar or custom toolbar if you want
//   config.toolbar_Basic.push(['helloworld.btn']);   
    config.toolbar_MyToolbarSet =
        [
            ['Bold','Italic','-'],['readmore.btn']
        ];
    config.toolbar_MyToolbarSet.push(['readmore.btn']);
};