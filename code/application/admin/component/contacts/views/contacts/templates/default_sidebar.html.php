<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h3><?= @text('Categories') ?></h3>

<?= @template('com://admin/categories.view.categories.list.html', array('categories' => @service('com://admin/contacts.model.categories')->sort('title')->table('contacts')->getRowset())); ?>