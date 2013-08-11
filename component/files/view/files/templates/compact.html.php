<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?= @template('com:files.view.files.initialize.html'); ?>

<script src="media://files/js/files.compact.js" />

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

<div id="files-compact" class="tabs tabs-horizontal">
    <div class="tab">
        <input type="radio" id="tab-1" name="tab-group-1" checked="">
        <label for="tab-1"><?= @text('Insert') ?></label>
        <div id="files-insert" class="content">
                <div id="files-tree-container">
                    <div id="files-tree"></div>
                </div>
                <div id="files-grid"></div>
                <div id="details">
                    <div id="files-preview"></div>
                </div>
        </div>
    </div>
    <div class="tab">
        <input type="radio" id="tab-2" name="tab-group-1">
        <label for="tab-2"><?= @text('Upload') ?></label>
        <div class="content">
            <?= @template('com:files.view.files.uploader.html'); ?>
        </div>
    </div>
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
