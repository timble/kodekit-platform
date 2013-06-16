<?
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @template('com:files.view.files.initialize.html'); ?>

<script src="media://files/js/files.compact.js" />

<style src="media://files/css/files.compact.css" />
<style>
    #files-compact #details {
        height: 388px \0/; /* IE needs this */
    }
</style>

<script>
Files.sitebase = '<?= $sitebase; ?>';
Files.base     = '<?= $base; ?>';
Files.token    = '<?= $token; ?>';

window.addEvent('domready', function() {
	var config = <?= json_encode($state->config); ?>,
		options = {
			state: {
				defaults: {
					limit: 0,
					offset: 0
				}
			},
			editor: <?= json_encode($state->editor); ?>,
			tree: {
				theme: 'media://files/images/mootree.png'
			},
			types: <?= json_encode($state->types); ?>,
			container: <?= json_encode($state->container ? $state->container : null); ?>
		};
	options = $extend(options, config);

	Files.app = new Files.Compact.App(options);

	$$('#tabs-pane_insert dt').addEvent('click', function(){
		setTimeout(function(){window.fireEvent('refresh');}, 300);
	});
});
</script>

<?= @template('com:files.view.files.templates_compact.html');?>

<div id="files-compact">
	<?=	@helper('tabs.startPane', array('id' => 'pane_insert')); ?>
	<?= @helper('tabs.startPanel', array('title' => 'Insert')); ?>
		<div id="insert">
			<div id="files-tree-container" style="float: left">
				<div id="files-tree"></div>
			</div>

			<div id="files-grid" style="float: left"></div>
			<div id="details" style="float: left;">
				<div id="files-preview"></div>
			</div>
			<div class="clear" style="clear: both"></div>
		</div>
	<?= @helper('tabs.endPanel'); ?>
	<?= @helper('tabs.startPanel', array('title' => @text('Upload'))); ?>

		<?= @template('com:files.view.files.uploader.html'); ?>

	<?= @helper('tabs.endPanel'); ?>
	<?= @helper('tabs.endPane'); ?>
</div>

<script>
/* Modal fixes that applies when this view is loaded within an iframe in a modal view */
window.addEvent('domready', function(){
    if(window.parent && window.parent != window && window.parent.SqueezeBox) {
        var modal = window.parent.SqueezeBox;

		document.id('files-compact').getParents().setStyles({padding: 0, margin: 0, overflow: 'hidden'});

        //Height fixes for parent modal
        var fixHeight = function(){
            var newHeight = document.id('files-compact').measure(function(){return this.getSize().y;});
            window.parent.document.id('sbox-content').getElement('iframe').set('height', newHeight);
            modal.fx.win.set({height: newHeight});
        };
        document.getElements('#tabs-pane_insert dt, .upload-buttons li').addEvent('click', fixHeight);
        fixHeight();
        window.addEvent('QueueChanged', fixHeight);
	}
});
</script>
