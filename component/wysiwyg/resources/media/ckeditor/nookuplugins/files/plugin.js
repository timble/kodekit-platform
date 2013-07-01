CKEDITOR.plugins.add('files',
    {
        requires: [ 'iframedialog' ],
        icons: 'files',
        init: function( editor )
        {
            var height = 480, width = 750;
            CKEDITOR.dialog.addIframe(
                'myiframedialogDialog',
                'Files',
                '?option=com_files&container=files-files&view=images&tmpl=dialog', width, height,
                function()
                {
                    // Iframe loaded callback.
                },

                {
                    onOk : function()
                    {
                        // Dialog onOk callback.
                    }
                }
            );

            editor.addCommand( 'myiframedialog', new CKEDITOR.dialogCommand( 'myiframedialogDialog' ) );

            editor.ui.addButton( 'files',
                {
                    label: 'My Iframe in dialog',
                    command: 'myiframedialog',
                    icon: this.path + 'images/icon.gif'
                } );
        }
    }
);

function showDialogPlugin(e){
    e.openDialog('files.dlg');
}