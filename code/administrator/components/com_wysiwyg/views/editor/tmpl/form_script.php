<?
/**
 * @version     $Id: form.php 2004 2011-06-26 16:32:54Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<? /* Image and article buttons needs this in order to work */ ?>
<?= @helper('behavior.modal') ?>

<? if ($options['toggle']) : ?>
    <style src="media://com_wysiwyg/css/form.css" />
    <script src="media://com_wysiwyg/js/Fx.Toggle.js" />
<? endif ?>

<script src="media://com_wysiwyg/tinymce/tiny_mce<?= @service('application')->getCfg('debug') ? '_src.js' : '.js' ?>" />
<script src="media://com_wysiwyg/js/Editor.js" />

<? if($codemirror) : ?>
<script src="media://com_wysiwyg/codemirror/lib/codemirror.js" />
<script src="media://com_wysiwyg/codemirror/mode/css/css.js" />
<script src="media://com_wysiwyg/codemirror/mode/htmlmixed/htmlmixed.js" />
<script src="media://com_wysiwyg/codemirror/mode/javascript/javascript.js" />
<script src="media://com_wysiwyg/codemirror/mode/php/php.js" />
<script src="media://com_wysiwyg/codemirror/mode/xml/xml.js" />

<style src="media://com_wysiwyg/codemirror/lib/codemirror.css" />

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
Editor.baseurl = <?= json_encode(JURI::root()); ?>;

new Editor(<?= json_encode($id) ?>, <?= json_encode($options) ?>, <?= json_encode($settings) ?>);
</script>