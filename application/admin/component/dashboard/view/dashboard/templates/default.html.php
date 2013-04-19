<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<?= @overlay(array('url' => @route('option=com_activities&view=activities&layout=list'))); ?>

<div class="sidebar">
    <div class="mod_users">
    	<h3><?= @text('Logged in Users'); ?></h3>
    	<?= @object('com:users.controller.user')->layout('list')->limit(10)->loggedin(true)->render(); ?>
    </div>
</div>