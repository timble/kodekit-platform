<? /* Image and article buttons needs this in order to work */ ?>
<?= @helper('behavior.modal') ?>

<style src="media://com_editors/css/default.css" />

<?= @helper('behavior.validator') ?>

<? if ($options['toggle']) : ?>
    <style src="media://com_editors/css/form.css" />
    <script src="media://com_editors/js/Fx.Toggle.js" />
<? endif ?>

<script src="media://com_editors/tinymce/tiny_mce<?= KDEBUG ? '_src.js' : '.js' ?>" />
<script src="media://com_editors/tinymce/themes/advanced/js/quicktags.js" />
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
// Support Form.Validator if present
if(this.Form && Form.Validator) {
    Form.Validator.add('validate-editor', {
    	errorMsg: function(){
    	    return Form.Validator.getMsg('required');
    	},
    	test: function(element){
    		return !!Editors.get(element.id).getText().trim().length;
    	}
    });
}

var settings = <?= json_encode($settings) ?>, options = <?= json_encode($options) ?>;

settings.setup =  function(ed) {
	ed.onBeforeRenderUI.add(function(ed) {
		//options.tinyMCE = ed;
		new Editor(ed.id, options);
	});
}

tinyMCE.init(settings);
</script>

<? if($editors) : ?>
<script>
edCanvas = document.getElementById("<?= $name ?>");
</script>

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