<?php
/**
 * @version     $Id: form.php 1167 2011-05-11 15:37:22Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('default_sidebar'); ?>

<form action="<?= @route('application='.$state->application)?>" method="get" class="-koowa-grid">
    <table class="adminlist"  style="clear: both;">
    	<thead>
    		<tr>
    			<th width="10"></th>
    			<th>
    				<?= @text('Name'); ?>
    			</th>
    			<th width="10%">
    				<?= @text('Version'); ?>
    			</th>
    			<th width="15%">
    				<?= @text('Date'); ?>
    			</th>
    			<th width="25%">
    				<?= @text('Author'); ?>
    			</th>
    			<th width="25%">
    				<?= @text('Author Email'); ?>
    			</th>
    		</tr>
    	</thead>
    	<tbody>
    		<? foreach ($languages as $language): ?>
    		<tr>
    			<td align="center">
    				<input type="radio" name="language[]" class="-koowa-grid-checkbox" value="<?= $language->name; ?>" />
    			</td>
    			<td>
    				<?= $language->title; ?>
    				<? if($language->default): ?>
    					<img src="media://system/images/star.png" alt="<?= @text('Default'); ?>" />
    				<? endif; ?>
    			</td>
    			<td align="center">
    				<?= $language->version; ?>
    			</td>
    			<td align="center">
    				<?= @date(array('date' => $language->creationDate, 'format' => '%d %B %Y')); ?>
    			</td>
    			<td align="center">
    				<?= $language->author; ?>
    			</td>
    			<td align="center">
    				<?= $language->authorEmail; ?>
    			</td>
    		</tr>
    		<? endforeach; ?>
    	</tbody>
    	<tfoot>
    		<tr>
    			<td colspan="8">
    				<?= @helper('paginator.pagination', array('total' => $total)) ?>
    			</td>
    		</tr>
    	</tfoot>
    </table>
</form>