<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>
<?= helper('behavior.validator') ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<form action="" method="post" class="-koowa-form" id="category-form">
    <div class="main">
		<div class="title">
			<input class="required" type="text" name="title" maxlength="255" value="<?= $category->title; ?>" placeholder="<?= translate( 'Title' ); ?>" />
		    <div class="slug">
		        <span class="add-on"><?= translate('Slug'); ?></span>
		        <input type="text" name="slug" maxlength="255" value="<?= $category->slug ?>" />
		    </div>
		</div>
        <?= object('com:ckeditor.controller.editor')->render(array('name' => 'description', 'text' => $category->description, 'toolbar' => 'basic')) ?>
	</div>

    <div class="sidebar">
	    <?= import('default_sidebar.html'); ?>
    </div>
</form>