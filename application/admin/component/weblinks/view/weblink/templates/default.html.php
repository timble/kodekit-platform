<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<?= @helper('behavior.validator'); ?>

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<!--
<script src="media://js/koowa.js" />
<style src="media://css/koowa.css" />
-->
<form action="" method="post" id="weblink-form" class="-koowa-form">
	<input type="hidden" name="id" value="<?= $weblink->id ?>" />
	<input type="hidden" name="published" value="0" />
	
	<div class="main">
		<div class="title">
		    <input class="required" type="text" name="title" maxlength="255" value="<?= $weblink->title ?>" placeholder="<?= @text('Title') ?>" />
		    <div class="slug">
		        <span class="add-on"><?= @text('Slug'); ?></span>
		        <input type="text" name="slug" maxlength="255" value="<?= $weblink->slug ?>" />
		    </div>
		</div>
	    <div class="scrollable">
	        <fieldset>
	        	<legend><?= @text( 'Details' ); ?></legend>
				<div>
				    <label for=""><?= @text( 'URL' ); ?></label>
				    <div>
				        <input class="required validate-url" type="text" name="url" value="<?= $weblink->url; ?>" maxlength="250" />
				    </div>
				</div>
				<div>
				    <label for=""><?= @text( 'Description' ); ?></label>
				    <div>
				        <textarea rows="9" name="description"><?= $weblink->description; ?></textarea>
				    </div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="sidebar">
		<div class="scrollable">
            <?= @template('default_sidebar.html'); ?>
        </div>
	</div>
</form>