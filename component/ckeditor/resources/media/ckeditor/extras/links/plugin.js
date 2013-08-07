var iframeWindow = null;
CKEDITOR.plugins.add('links',
    {
        requires: [ 'iframedialog' ],
        icons: 'images',
        init: function( editor)
        {

            var height = 480, width = 900;
            CKEDITOR.dialog.addIframe(
                'linksDialog',
                'links',
                '?option=com_ckeditor&published=1&view=articles&layout=dialog&tmpl=dialog', width, height,
                function() {
                    var iframe = document.getElementById( this._.frameId );
                    iframeWindow = iframe.contentWindow;

                    var dialog = CKEDITOR.dialog.getCurrent();
                    var editor = dialog.getParentEditor();
                    var selected = CKEDITOR.plugins.link.getSelectedLink( editor );

                    if(editor.getSelection().getSelectedText()){
                        iframeWindow.document.id('link-text').set('value', editor.getSelection().getSelectedText());
                    }

                    if(selected){
                        iframeWindow.document.id('link-url').set('value',selected.getAttribute('href'));
                        iframeWindow.document.id('link-alt').set('value',selected.getAttribute('alt'));
                        iframeWindow.document.id('link-title').set('value',selected.getAttribute('title'));
                    }

                },

                { // userDefinition
                    onOk : function()
                    {

                        var src = iframeWindow.document.id('link-url').get('value');
                        var text = iframeWindow.document.id('link-text').get('value');
                        var attrs = {};
                        ['alt', 'title'].each(function(id) {
                            var value = iframeWindow.document.id('link-'+id).get('value');
                            if (value) {
                                attrs[id] = value;
                            }
                        });

                        var str = '<a href="'+src+'" ';
                        var parts = [];
                        parts.push('type="text/html"');
                        $each(attrs, function(value, key) {
                            parts.push(key+'="'+value+'"');
                        });
                        str += parts.join(' ')+'>';
                        str += text+ '</a>';

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
            if ( editor.contextMenu ) {
                editor.addMenuGroup( 'linkGroup' );
                editor.addMenuItem( 'linkItem', {
                    label: 'Edit Link',
                    icon: this.path + 'images/image.png',
                    command: 'linksDialog',
                    group: 'linkGroup'
                });

                editor.contextMenu.addListener( function( ) {

                    var element = CKEDITOR.plugins.link.getSelectedLink( editor );
                    if(element){
                        if(element.getAttribute('type')  != 'text/html'){
                            return;
                        }
                    }

                    return { linkItem: CKEDITOR.TRISTATE_OFF };

                });
            }

        }
    }
);

function showDialogPlugin(e){
    e.openDialog('links.dlg');
}