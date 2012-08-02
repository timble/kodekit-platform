<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<h3><?= @text('Categories') ?></h3>
<?= @template('com://admin/categories.view.categories.list', array('categories' => @service('com://admin/weblinks.model.categories')->sort('title')->table('weblinks')->getList())); ?>