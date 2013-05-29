<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('listbox.radiolist', array(
	'list'      => array((object) array('title' => 'Uncategorized', 'id' => 0)),
	'name'      => 'categories_category_id',
    'text'      => 'title',
	'selected'  => $article->categories_category_id,
    'translate' => true));
?>

<? foreach($categories as $category) : ?>
    <label class="radio" for="categories_category_id<?= $category->id ?>">
        <input type="radio" name="categories_category_id" id="categories_category_id<?= $category->id ?>" value="<?= $category->id ?>">
        <?= @escape($category->title); ?>
    </label>
    <? if($category->hasChildren()) : ?>
        <div style="margin-left: 16px">
        <?= @helper('listbox.radiolist', array(
				'list'     => $category->getChildren(),
				'selected' => $article->categories_category_id,
				'name'     => 'categories_category_id',
		        'text'     => 'title',
			));
		?>
        </div>
	<? endif; ?>
<? endforeach ?>