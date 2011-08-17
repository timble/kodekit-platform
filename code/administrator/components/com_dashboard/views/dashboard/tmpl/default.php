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


<table class="adminform" width="100%">
<tr>
<td width="55%">
    <?= @template('default_icons'); ?>
</td>
<td width="45%">
<?= @helper('accordion.startPane') ?>
    <? foreach ($modules as $module) : ?>
        <?= @helper('accordion.startPanel', array('title' => $module->title)) ?>
        <?= KFactory::tmp('admin::mod.'.substr($module->type, 4).'.html')->module($module)->params($module->params)->display(); ?>
        <?= @helper('accordion.endPanel') ?>
    <? endforeach ?>
<?= @helper('accordion.endPane') ?>
</td>
</tr>
</table>