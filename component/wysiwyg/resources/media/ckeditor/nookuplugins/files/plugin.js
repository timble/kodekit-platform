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
                function()
                { // onContentLoad
                    // Iframe loaded callback.
                },

                { // userDefinition
                    onOk : function()
                    {
                        // Dialog onOk callback.
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
                    icon: this.path + 'images/icon.gif'
                } );

        }
    }
);

function showDialogPlugin(e){
    e.openDialog('files.dlg');
}