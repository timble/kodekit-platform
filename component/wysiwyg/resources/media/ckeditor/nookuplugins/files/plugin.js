var iframeWindow = null;
CKEDITOR.plugins.add('files',
    {
        requires: [ 'iframedialog' ],
        icons: 'files',
        init: function( editor )
        {
            var height = 480, width = 750;
            CKEDITOR.dialog.addIframe(
                'filesDialog',
                'Files',
                '?option=com_files&container=files-files&view=images&tmpl=dialog', width, height,
                function() {
                    var iframe = document.getElementById( this._.frameId );
                    iframeWindow = iframe.contentWindow;
                },

                { // userDefinition
                    onOk : function()
                    {

                        var iframedocument = iframeWindow.document;
                        var src = iframedocument.id('image-url').get('value');
                        var attrs = {};
                        ['align', 'alt', 'title'].each(function(id) {
                            var value = iframedocument.id('image-'+id).get('value');
                            if (value) {
                                attrs[id] = value;
                            }
                        });
                        if (iframedocument.id('image-caption').get('value')) {
                            attrs['class'] = 'caption';
                        }

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
                        this.parts.dialog.addClass('files_dialog');
                    }
                }
            );

            editor.addCommand( 'filesDialog', new CKEDITOR.dialogCommand( 'filesDialog' ) );

            editor.ui.addButton( 'files',
                {
                    label: 'Files Dialog',
                    command: 'filesDialog',
                    icon: this.path + 'images/image.png'
                } );

        }
    }
);

function showDialogPlugin(e){
    e.openDialog('files.dlg');
}