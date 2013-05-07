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

<h3><?= @text('Categories') ?></h3>
<?= @template('com:categories.view.categories.list.html', array('categories' => @object('com:weblinks.model.categories')->sort('title')->table('weblinks')->getRowset())); ?>