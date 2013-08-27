CKEditor Plugin onchange
=============
Plugin create event change
------------
Create folder `ckeditor/plugins/onchange` and unpack archive to 

File `ckeditor/config.js`

	CKEDITOR.editorConfig = function( config ){
		config.extraPlugins = 'onchange'; // add plugin
	};
	
Use
------------

	editor.on( 'change', function(){ alert('Document chached!!!'); });
	
1. Author:Alfonso Martínez
2. Used: [ck_stat][used1] and [ck_backup][used2]
3. [more ckeditor Plugins][more]
[more]: http://xdan.ru/project/ckplugins/
[author]: http://xdan.ru/user/profile/Leroy
[used1]: https://github.com/xdan/ck_stat
[used2]: https://github.com/xdan/ck_backup