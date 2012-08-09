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
    <div class="control-group">
        <label class="control-label" for="status"><?= @text('Published') ?></label>
        <div class="controls">
            <input type="checkbox" name="enabled" value="1" <?= $page->enabled ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="publish_up"><?= @text('Hidden') ?></label>
        <div class="controls">
       	    <input type="checkbox" name="hidden" value="1" <?= $page->hidden ? 'checked="checked"' : '' ?> />
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
        	<?= @helper('listbox.parents', array('page' => $page, 'menu' => $state->menu, 'selected' => $page->parent_id)) ?>
        </div>
    </div>
</fieldset>

<fieldset class="form-horizontal">
    <? if(count($page->params_state->getParams('params'))) : ?>
        <?= $page->params_state->render('params') ?>
    <? endif ?>
</fieldset>
