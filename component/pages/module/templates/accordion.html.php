<?
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
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