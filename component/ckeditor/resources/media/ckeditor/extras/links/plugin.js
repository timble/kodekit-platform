var iframeWindow = null;
CKEDITOR.plugins.add('links',
    {
        requires: [ 'iframedialog' ],
        icons: 'images',
        init: function( editor )
        {
            var height = 480, width = 900;
            CKEDITOR.dialog.addIframe(
                'linksDialog',
                'links',
                '?option=com_ckeditor&published=1&view=articles&layout=dialog&tmpl=dialog', width, height,
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

            editor.addCommand( 'linksDialog', new CKEDITOR.dialogCommand( 'linksDialog' ) );

            editor.ui.addButton( 'links',
                {
                    label: 'Link Dialog',
                    command: 'linksDialog',
                    icon: this.path + 'images/image.png'
                } );

        }
    }
);

function showDialogPlugin(e){
    e.openDialog('links.dlg');
}