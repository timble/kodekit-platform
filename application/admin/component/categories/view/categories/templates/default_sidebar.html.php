<?
/**
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h3><?= @text('Categories') ?></h3>
<?= @template('com:categories.view.categories.list.html', array('categories' => @object('com:articles.model.categories')->sort(array('ordering', 'title'))->table($state->table)->getRowset())); ?>
