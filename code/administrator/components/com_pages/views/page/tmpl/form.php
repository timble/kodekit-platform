<?php
/**
 * @version     $Id: form.php 3035 2011-10-09 16:57:12Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<!--
<script src="media://com_pages/js/widget.js" />
<script src="media://com_pages/js/page.js" />
-->

<script>
    window.addEvent('domready', function(){
        $$('.widget').widget({cookie: 'widgets-page'});

        new Page(<?= json_encode(array('active' => $state->type['option'])) ?>);
    });
</script>

<?= @template('com://admin/default.view.form.toolbar') ?>

<form action="" method="post" class="-koowa-form">
    <input type="hidden" name="pages_menu_id" value="<?= $state->menu ?>" />
    <input type="hidden" name="enabled" value="0" />
    <input type="hidden" name="hidden" value="0" />

    <?= @template('form_types') ?>

    <? if($state->type) : ?>
    <div id="main">
        <div class="title">
        	<input type="text" name="title" placeholder="<?= @text('Title') ?>" value="<?= $page->title ?>" size="50" maxlength="255" />
            <?= @text('Visitors can access this page at'); ?>
            <?= dirname(JURI::base()) ?>/<input type="text" name="slug" placeholder="<?= @text('Alias') ?>" value="<?= $page->slug ?>" maxlength="255" />
        </div>
        <?= @helper('tabs.startPane', array('id' => 'pane_1')); ?>
	        <?= @helper('tabs.startPanel', array('title' => 'General')); ?>
	            <?= @template('form_general') ?>
	        <?= @helper('tabs.endPanel'); ?>
	        
	        <? if($state->type['name'] == 'component') : ?>
	        <?= @helper('tabs.startPanel', array('title' => 'Component')); ?>
	            <?= @template('form_component') ?>
	        <?= @helper('tabs.endPanel'); ?>
	        
	        <?= @helper('tabs.startPanel', array('title' => 'System')); ?>
		        <fieldset class="form-horizontal">
		            <?= $page->params_page->render('params'); ?>
		        </fieldset>
	        <?= @helper('tabs.endPanel'); ?>
	        <?= @helper('tabs.startPanel', array('title' => 'Modules')) ?>
	            <?= @template('form_modules') ?>
	        <?= @helper('tabs.endPanel') ?>
	        <? endif ?>  
        <?= @helper('tabs.endPane') ?>
    </div>
    <? endif ?>
</form>
