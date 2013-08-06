var iframeWindow = null;
CKEDITOR.plugins.add('files',
    {
        requires: [ 'iframedialog' ],
        icons: 'images',
        init: function( editor )
        {
            var height = 480, width = 750;
            CKEDITOR.dialog.addIframe(
                'filesDialog',
                'files',
                '?option=com_ckeditor&container=files-files&view=files&type=file&layout=dialog&tmpl=dialog', width, height,
                function() {
                    var iframe = document.getElementById( this._.frameId );
                    iframeWindow = iframe.contentWindow;
                },

                { // userDefinition
                    onOk : function()
                    {

                        var iframedocument = iframeWindow.document;
                        var src = iframedocument.id('image-url').get('value');
                        var link = iframedocument.id('image-link').get('value');
                        var attrs = {};
                        ['alt', 'title','type'].each(function(id) {
                            var value = iframedocument.id('image-'+id).get('value');
                            if (value) {
                                attrs[id] = value;
                            }
                        });

                        var str = '<a href="'+src+'" ';
                        var parts = [];

                        $each(attrs, function(value, key) {
                            parts.push(key+'="'+value+'"');
                        });

                        str += parts.join(' ')+' >';
                        str += link+"</a>";

                        // puts the image in the editor
                        this._.editor.insertHtml(str);
                    },
                    onShow : function()
                    {
                        this.parts.dialog.addClass('image_dialog');
                    }
                }
            );

            editor.addCommand( 'filesDialog', new CKEDITOR.dialogCommand( 'filesDialog' ) );

            editor.ui.addButton( 'files',
                {
                    label: 'File Dialog',
                    command: 'filesDialog',
                    icon: this.path + 'images/image.png'
                } );

        }
    }
);

function showDialogPlugin(e){
    e.openDialog('files.dlg');
}