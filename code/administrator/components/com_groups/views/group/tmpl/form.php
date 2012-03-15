<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" class="-koowa-form" id="group-form">
    <div class="editor-container">
        <div class="title">
            <input class="required" type="text" name="name" maxlength="255" value="<?= $group->name ?>" placeholder="<?= @text('Group name') ?>" />
        </div>
        <div class="editor">
	        <div class="panel">
	            <h3><?= @text( 'Group' ); ?></h3>
	        	<?= @helper('select.groups', array('name' => 'target_id', 'selected' => $group->parent_id, 'exclude' => $group)) ?>
	       	</div>
       	</div>
    </div>
</form>