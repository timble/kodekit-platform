<?php
/**
 * @version     $Id: form_general.php 3035 2011-10-09 16:57:12Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<fieldset class="form-horizontal">
	<legend><?= @text('Publish') ?></legend>
    <div class="control-group">
        <label class="control-label" for="status"><?= @text('Status') ?></label>
        <div class="controls">
            <?= @helper('listbox.published',  array('deselect' => false, 'selected' => $page->isNew() ? 1 : $page->enabled)) ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="publish_up"><?= @text('Visibility') ?></label>
        <div class="controls">
       	    <?= @helper('listbox.access',  array('deselect' => false)) ?>
       	</div>
    </div>
    <div class="control-group">
        <label class="control-label" for="parent"><?= @text('Parent') ?></label>
        <div id="parent" class="controls">
        	<?= @helper('listbox.parents', array('pages_page_id' => $page->id, 'pages_menu_id' => $state->menu, 'selected' => $page->parent_id)) ?>
        </div>
    </div>
</fieldset>

<fieldset>
	<legend><?= @text('Content') ?></legend>
    <? $model = $this->getView()->getModel() ?>

    <? if($state->type['name'] == 'component') : ?>
        <?= $page->params_url->render('urlparams') ?>
    <? endif ?>

    <? $state_parameters = $page->params_state ?>
    <? if(count($state_parameters->getParams('params'))) : ?>
        <?= $state_parameters->render('params') ?>
    <? endif ?>

    <? if(!(count($state_parameters->getNumParams('params')) || isset($url_parameters) && count($url_parameters->getNumParams('urlparams')))) : ?>
        <div style="text-align: center; padding: 5px;">
            <?= @text('There are no parameters for this item') ?>
        </div>
    <? endif ?>
</fieldset>

<? $advanced_parameters = $page->params_advanced ?>
<? if($rendered_parameters = $advanced_parameters->render('params')) : ?>
<fieldset>
	<h3><?= @text('Advanced') ?></h3>
    <?= $rendered_parameters ?>
</fieldset>
<? endif ?>
