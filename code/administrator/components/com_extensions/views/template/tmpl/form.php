<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @helper('behavior.modal'); ?>
<?= @helper('behavior.tooltip'); ?>
<?= @helper('behavior.validator'); ?>

<?= @helper('behavior.tooltip') ?>

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="<?= @route('name='.$template->name.'&application='.$state->application) ?>" method="post" class="-koowa-form">
        	
	<div class="form-content">
        <fieldset class="form-horizontal">
            <legend><?= @text('Details') ?></legend>
            <div class="control-group">
                <label class="control-label"><?= @text('Name') ?></label>
                <div class="controls">
                    <?= @text($template->name) ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?= @text('Description') ?></label>
                <div class="controls">
                    <?= @text($template->description) ?>
                </div>
            </div>
        </fieldset>
	</div>
   
   <div class="sidebar">
        <fieldset class="adminform">
            <legend><?= @text('Parameters') ?></legend>
            <? if($html = $template->params->render()) : ?>
                <?= $html ?>
            <? else : ?>
                <div style="text-align: center; padding: 5px;">
                    <?= @text('No Parameters') ?>
                </div>
            <? endif ?>
        </fieldset>
    </div>
</form>