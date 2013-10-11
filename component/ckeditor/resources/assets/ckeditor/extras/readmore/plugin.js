CKEDITOR.plugins.add('readmore',
    {
        icons: 'readmore',
        init : function(editor) {
            var pluginName = 'readmore';
            var mypath = this.path;
            editor.ui.addButton(
                'readmore',
                {
                    label : "Readmore",
                    command : 'readmore.cmd',
                    icon : mypath + 'images/icon.png'
                }
            );
            var cmd = editor.addCommand('readmore.cmd', {

               exec : function(editor)
               {
                   var readmore = editor.document.$.getElementsByTagName("hr");
                   if(readmore.length == 0){
                    editor.insertHtml( '<hr id="system-readmore" />' );
                   }
               }

            });

        }
    }
);

function showDialogPlugin(e){
    e.openDialog('readmore.dlg');
}