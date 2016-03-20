<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<h3><?= translate('Menus') ?></h3>
<?= import('com:pages.menus.list.html', array('state' => parameters(), 'menus' => object('com:pages.model.menus')->fetch())); ?>
