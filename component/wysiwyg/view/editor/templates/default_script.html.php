<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<? /* Image and article buttons needs this in order to work */ ?>
<?= @helper('behavior.modal') ?>

<? if ($options['toggle']) : ?>
    <style src="media://wysiwyg/css/form.css" />
    <script src="media://wysiwyg/js/Fx.Toggle.js" />
<? endif ?>

<script src="media://wysiwyg/tinymce/tiny_mce<?= @object('application')->getCfg('debug') ? '_src.js' : '.js' ?>" />
<script src="media://wysiwyg/js/Editor.js" />

<? if($codemirror) : ?>
<script src="media://wysiwyg/codemirror/lib/codemirror.js" />
<script src="media://wysiwyg/codemirror/mode/css/css.js" />
<script src="media://wysiwyg/codemirror/mode/htmlmixed/htmlmixed.js" />
<script src="media://wysiwyg/codemirror/mode/javascript/javascript.js" />
<script src="media://wysiwyg/codemirror/mode/php/php.js" />
<script src="media://wysiwyg/codemirror/mode/xml/xml.js" />

<style src="media://wysiwyg/codemirror/lib/codemirror.css" />

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
Editor.baseurl = '';

new Editor(<?= json_encode($id) ?>, <?= json_encode($options) ?>, <?= json_encode($settings) ?>);
</script>