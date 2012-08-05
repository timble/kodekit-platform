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

<section id="publish">
    <h3><?= @text('Publish') ?></h3>
    <fieldset>
        <div class="row toggle-select">
            <label for="status"><?= @text('Status') ?>:</label>
            <?= @helper('listbox.published',  array('deselect' => false, 'selected' => $page->isNew() ? 1 : $page->enabled)) ?>
        </div>
        <div class="row toggle-select">
            <label for="publish_up"><?= @text('Visibility') ?>:</label>
            <?= @helper('listbox.access',  array('deselect' => false)) ?>
        </div>
        <div class="row">
            <label for="parent"><?= @text('Parent') ?>:</label><br />
            <span id="parent"><?= @helper('listbox.parents', array('pages_page_id' => $page->id, 'pages_menu_id' => $state->menu, 'selected' => $page->parent_id)) ?></span>
        </div>
    </fieldset>
</section>

<section>
    <h3><?= @text('Content') ?></h3>
    <fieldset>
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
</section>

<? $advanced_parameters = $page->params_advanced ?>
<? if($rendered_parameters = $advanced_parameters->render('params')) : ?>
<section>
    <h3><?= @text('Advanced') ?></h3>
    <fieldset>
        <?= $rendered_parameters ?>
    </fieldset>
</section>
<? endif ?>
