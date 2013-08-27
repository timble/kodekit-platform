/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
(function() {
    CKEDITOR.plugins.addExternal('readmore','extras/readmore/', 'plugin.js');
    CKEDITOR.plugins.addExternal('images','extras/images/', 'plugin.js');
    CKEDITOR.plugins.addExternal('autosave','extras/autosave/', 'plugin.js');
    CKEDITOR.plugins.addExternal('files','extras/files/', 'plugin.js');
})();


CKEDITOR.editorConfig = function( config ) {

	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

    config.extraPlugins = 'codemirror,readmore,images,onchange,files';
    // wee need to remove the default image and link plugins for the custom plugin's to work properly

    config.protectedSource.push( /<\?[\s\S]*?\?>/g );
    config.autosave_delay = '10';
    config.codemirror = {
        theme: 'default',
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        enableSearchTools: true, // Whether or not to enable search tools, CTRL+F (Find), CTRL+SHIFT+F (Replace), CTRL+SHIFT+R (Replace All), CTRL+G (Find Next), CTRL+SHIFT+G (Find Previous)
        enableCodeFolding: true,
        enableCodeFormatting: true,
        autoFormatOnStart: true,
        autoFormatOnUncomment: true,
        highlightActiveLine: true,
        highlightMatches: true,
        showFormatButton: true,
        showCommentButton: true,
        showUncommentButton: true
    };

    config.allowedContent = true;
    config.toolbar_full =
        [
            { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
            { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
            { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
            { name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton',
                'HiddenField' ] },
            '/',
            { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
            { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
                '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
            { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
            { name: 'insert', items : [ 'images','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
            '/',
            { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
            { name: 'colors', items : [ 'TextColor','BGColor' ] },
            { name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','readmore' ] }
        ];

    config.toolbar_basic =
        [
            ['Bold','Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink']
        ];

    config.toolbar_standard =
        [
            { name: 'styles', items: [ 'Styles' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent' ] },
            { name: 'links', items: [ 'readmore' ] },
            { name: 'insert', items: [ 'images', 'files','Link','Unlink' ,'Table' ] },
            { name: 'clipboard', items: [ 'PasteText', '-', 'Undo', 'Redo' ] },
            { name: 'document', items: [ 'Source' ] }
        ];
    config.toolbar_title =
        [
            ['Undo','Redo']
        ];
};
