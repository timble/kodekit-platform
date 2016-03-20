<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.overlay', array('url' => route('component=activities&view=activities&layout=list'))); ?>

<div class="sidebar">
    <div class="mod_users">
    	<h3><?= translate('Logged in Users'); ?></h3>
    	<?= object('com:users.controller.user')->layout('list')->limit(10)->authentic(true)->render(); ?>
    </div>
</div>