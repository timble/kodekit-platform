<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<h3><?= translate('Menus') ?></h3>
<?= import('com:pages.view.menus.list.html', array('state' => state(), 'menus' => object('com:pages.model.menus')->fetch())); ?>
