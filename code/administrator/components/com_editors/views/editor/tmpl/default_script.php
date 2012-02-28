<? /* Image and article buttons needs this in order to work */ ?>
<?= @helper('behavior.modal') ?>

<style src="media://com_editors/css/default.css" />

<? if ($options['toggle']) : ?>
    <style src="media://com_editors/css/form.css" />
    <script src="media://com_editors/js/Fx.Toggle.js" />
<? endif ?>

<script src="media://com_editors/tinymce/tiny_mce<?= KDEBUG ? '_src.js' : '.js' ?>" />
<script src="media://com_editors/tinymce/themes/advanced/js/editor.js" />
<script src="media://com_editors/js/Editor.js" />

<? if($editors) : ?>
<script src="media://com_editors/codemirror/js/codemirror.js" />

<script>	
var quicktagsL10n = 
{
	quickLinks: "(Quick Links)",
	wordLookup: "Enter a word to look up:",
	dictionaryLookup: "Dictionary lookup",
	lookup: "lookup",
	closeAllOpenTags: "Close all open tags",
	closeTags: "close tags",
	enterURL: "Enter the URL",
	enterImageURL: "Enter the URL of the image",
	enterImageDescription: "Enter a description of the image"
};

try { convertEntities(quicktagsL10n);} catch(e) { };
</script>
<? endif ?>
		
<script>
(function(){
var settings = <?= json_encode($settings) ?>, options = <?= json_encode($options) ?>;

settings.setup =  function(ed) {
	ed.onBeforeRenderUI.add(function(ed) {
		//options.tinyMCE = ed;
		var editor = new Editor(ed.id, options), dirty = false;
		ed.onChange.add(function(ed){
			if(!dirty && ed.isDirty()) {
				editor.fireEvent('isDirty');
			} else if(dirty && !ed.isDirty()) {
				editor.fireEvent('isNotDirty');
			}
			dirty = ed.isDirty();
		});
	});
}

tinyMCE.init(settings);
})();
</script>

<? if($editors) : ?>
<script>
CodeMirrorConfig = new Hash(CodeMirrorConfig).extend({
	stylesheet: [
	  	'media://com_editors/codemirror/css/xmlcolors.css', 
	  	'media://com_editors/codemirror/css/jscolors.css', 
	  	'media://com_editors/codemirror/css/csscolors.css'
	],
	path: 'media://com_editors/codemirror/js/'
});
</script>
<? endif ?>