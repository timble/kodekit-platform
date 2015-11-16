<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?
//The module configuration
$config = array(
    'title'     => translate(title()),
    'id'        => 'module' . $module->id,
    'translate' => false
);
?>

<? if(isset(parameters()->rel->first)) : ?>
    <?= helper('accordion.startPane', $config); ?>
<? endif ?>

<?= helper('accordion.startPanel', $config); ?>
<?= content(); ?>
<?= helper('accordion.endPanel'); ?>

<? if(isset(parameters()->rel->last)) : ?>
    <?= helper('accordion.startPane'); ?>
<? endif ?>