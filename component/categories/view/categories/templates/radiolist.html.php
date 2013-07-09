<?php
/**
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<? $name = !isset($name) ? 'categories_category_id' : $name ?>

<? if(isset($uncategorised)) : ?>
<?= @helper('listbox.radiolist', array(
    'list'      => array((object) array('title' => 'Uncategorized', 'id' => 0)),
    'name'      => $name,
    'text'      => 'title',
    'selected'  => $selected,
    'translate' => true));
?>
<? endif ?>

<? foreach($categories as $category) : ?>
    <label class="radio" for="<?= $name ?><?= $category->id ?>">
        <input type="radio" name="<?= $name ?>" id="<?= $name ?><?= $category->id ?>"
               value="<?= $category->id ?>" <?= $category->id == $selected ? 'checked="checked"' : '' ?>>
        <?= @escape($category->title); ?>
    </label>
    <? if($category->hasChildren()) : ?>
        <div style="margin-left: 16px">
            <?= @helper('listbox.radiolist', array(
                'list'     => $category->getChildren(),
                'selected' => $selected,
                'name'     => $name,
                'text'     => 'title',
            ));
            ?>
        </div>
    <? endif; ?>
<? endforeach ?>