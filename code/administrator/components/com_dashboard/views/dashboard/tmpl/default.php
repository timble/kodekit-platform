<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<div class="-koowa-box-flex">
    <?= @template('default_icons'); ?>
</div>
<div style="width:550px;">
<?= @helper('accordion.startPane') ?>
    <? foreach ($modules as $module) : ?>
        <?= @helper('accordion.startPanel', array('title' => $module->title)) ?>
        <?= @service('mod://admin/'.substr($module->type, 4).'.html')->module($module)->params($module->params)->display(); ?>
        <?= @helper('accordion.endPanel') ?>
    <? endforeach ?>
<?= @helper('accordion.endPane') ?>
</div>