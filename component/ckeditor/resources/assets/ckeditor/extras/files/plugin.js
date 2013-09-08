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
                '?option=com_ckeditor&view=files&tmpl=dialog&container=files-files&types[]=file', width, height,
                function() {
                    var iframe = document.getElementById( this._.frameId );
                    iframeWindow = iframe.contentWindow;

                    var dialog = CKEDITOR.dialog.getCurrent();
                    var editor = dialog.getParentEditor();
                    var selected = CKEDITOR.plugins.link.getSelectedLink( editor );

                    if(editor.getSelection().getSelectedText()){
                        iframeWindow.document.id('image-text').set('value', editor.getSelection().getSelectedText());
                    }

                    if(selected){
                        iframeWindow.document.id('image-url').set('value',selected.getAttribute('href'));
                        iframeWindow.document.id('image-alt').set('value',selected.getAttribute('alt'));
                        iframeWindow.document.id('image-title').set('value',selected.getAttribute('title'));
                    }
                },

                { // userDefinition
                    onOk : function()
                    {

                        var iframedocument = iframeWindow.document;
                        var src = iframedocument.id('image-url').get('value');
                        var link = iframedocument.id('image-text').get('value');
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
                icon: this.path + 'images/icon.png'
            });
            if ( editor.contextMenu ) {
                editor.addMenuGroup( 'fileGroup' );
                editor.addMenuItem( 'fileItem', {
                    label: 'Edit File Link',
                    icon: this.path + 'images/icon.png',
                    command: 'filesDialog',
                    group: 'fileGroup'
                });

                editor.contextMenu.addListener( function( ) {

                    var element = CKEDITOR.plugins.link.getSelectedLink( editor );
                    //we only want to show this if the type = application
                    if(element){
                        if ( element.getAttribute('type').search('application') != -1) {
                            return { fileItem: CKEDITOR.TRISTATE_OFF };
                        }
                    }
                });
            }

        }
    }
);

function showDialogPlugin(e){
    e.openDialog('files.dlg');
}