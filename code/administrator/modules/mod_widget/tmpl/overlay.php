<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<?= @overlay(array('url' => @route($url), 'options' => array('selector' => $params->get('selector', 'body')))); ?>