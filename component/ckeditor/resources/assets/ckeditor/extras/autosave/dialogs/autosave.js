CKEDITOR.dialog.add( 'autosave', function( editor ) {
    return {
        title: editor.lang.autosave.title,
        minWidth: 400,
        minHeight: 100,
        contents : [
            {
                id : 'tab1',
                label : '',
                title : '',
                elements :
                    [
                        {
                            type : 'html',
                            html : '<div id="autosaveContent">'+editor.lang.autosave.loadSavedContent+'</div>'
                        }
                    ]
            }
        ],
        onOk: function() {

            if (editor.plugins.bbcode) {
                editor._.data = localStorage.getItem('autosave' + editor.id);
            } else {
                editor.setData(localStorage.getItem('autosave' + editor.id));
            }
            localStorage.removeItem('autosave' + editor.id);
        },
        onCancel: function() {
            localStorage.removeItem('autosave' + editor.id);
        },
        buttons : [ CKEDITOR.dialog.cancelButton, CKEDITOR.dialog.okButton ]
    };
});