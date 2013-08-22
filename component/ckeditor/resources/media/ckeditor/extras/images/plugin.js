var iframeWindow = null;
CKEDITOR.plugins.add('images',
    {
        requires: [ 'iframedialog' ],
        icons: 'images',
        init: function( editor )
        {
            var height = 480, width = 750;
            CKEDITOR.dialog.addIframe(
                'imagesDialog',
                'images',
                '?option=com_ckeditor&view=files&tmpl=dialog&container=files-files&types[]=image', width, height,
                function() {
                    var iframe = document.getElementById( this._.frameId );
                    iframeWindow = iframe.contentWindow;

                    var dialog = CKEDITOR.dialog.getCurrent();
                    var editor = dialog.getParentEditor();

                    var image = editor.getSelection().getSelectedElement();

                    if(image){
                        iframeWindow.document.id('image-url').set('value',image.getAttribute('src'));
                        iframeWindow.document.id('image-alt').set('value',image.getAttribute('alt'));
                        iframeWindow.document.id('image-title').set('value',image.getAttribute('title'));
                        iframeWindow.document.id('image-align').set('value',image.getAttribute('align'));
                    }
                },

                { // userDefinition
                    onOk : function()
                    {

                        var iframedocument = iframeWindow.document;
                        var src = iframedocument.id('image-url').get('value');
                        var attrs = {};
                        ['align', 'alt', 'title','type'].each(function(id) {
                            var value = iframedocument.id('image-'+id).get('value');
                            if (value) {
                                attrs[id] = value;
                            }
                        });

                        var str = '<img src="'+src+'" ';
                        var parts = [];
                        $each(attrs, function(value, key) {
                            parts.push(key+'="'+value+'"');
                        });
                        str += parts.join(' ')+' />';

                        // puts the image in the editor
                        this._.editor.insertHtml(str);
                    },
                    onShow : function()
                    {
                        this.parts.dialog.addClass('image_dialog');
                    }
                }
            );

            editor.addCommand( 'imagesDialog', new CKEDITOR.dialogCommand( 'imagesDialog' ) );

            editor.ui.addButton( 'images',
            {
                label: 'Image Dialog',
                command: 'imagesDialog',
                icon: this.path + 'images/icon.png'
            });
            if ( editor.contextMenu ) {
                editor.addMenuGroup( 'imageGroup' );
                editor.addMenuItem( 'imageItem', {
                    label: 'Edit Image',
                    icon: this.path + 'images/icon.png',
                    command: 'imagesDialog',
                    group: 'imageGroup'
                });

                editor.contextMenu.addListener( function( ) {

                    var element = editor.getSelection().getSelectedElement();
                    //we only want to show this if the type = text/html
                    if(element){
                        if ( element.getName() == 'img') {
                            return { imageItem: CKEDITOR.TRISTATE_OFF };
                        }
                    }
                });
            }

        }
    }
);

function showDialogPlugin(e){
    e.openDialog('images.dlg');
}