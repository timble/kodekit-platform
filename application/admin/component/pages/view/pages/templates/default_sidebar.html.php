<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h3><?= @text('Menus') ?></h3>
<?= @template('com:pages.view.menus.list.html', array('state' => $state, 'menus' => @object('com:pages.model.menus')->getRowset())); ?>
