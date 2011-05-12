<?php 
/**
 * @version     $Id: form.php 1167 2011-05-11 15:37:22Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<script src="media://lib_koowa/js/koowa.js" />

<div class="col width-15 menus">
    <ul>
        <li <? if($state->application == 'site') echo 'class="active"' ?>>
        	<a href="<?= @route('&application=site') ?>">
        	    <?= @text('Site') ?>
        	</a>
        </li>
        <li <? if($state->application == 'administrator') echo 'class="active"' ?>>
        	<a href="<?= @route('&application=administrator') ?>">
        	    <?= @text('Administrator') ?>
        	</a>
        </li>
    </ul>
</div>
<div class="col width-85">
    <form action="<?= @route('application='.$state->application)?>" method="get">
        <table class="adminlist"  style="clear: both;">
        	<thead>
        		<tr>
        			<th width="20">
        			</th>
        			<th width="25%" class="title">
        				<?= @text('Name'); ?>
        			</th>
        			<th width="5%">
        				<?= @text('Default'); ?>
        			</th>
        			<th width="10%">
        				<?= @text('Version'); ?>
        			</th>
        			<th width="10%">
        				<?= @text('Date'); ?>
        			</th>
        			<th width="20%">
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
        			<td width="20" align="center">
        				<input type="radio" name="language[]" class="-koowa-grid-checkbox" value="<?= $language->language; ?>"
        					<?= $language->default ? 'checked="checked"' : ''; ?> />
        			</td>
        			<td width="25%">
        				<?= $language->name; ?>
        			</td>
        			<td width="5%" align="center">
        				<? if ($language->default): ?>
        					<img src="media://system/images/star.png" alt="<?= @text('Default'); ?>" />
        				<? endif; ?>
        			</td>
        			<td align="center">
        				<?= $language->version; ?>
        			</td>
        			<td align="center">
        				<?= @date(array('date' => $language->creationdate, 'format' => '%d %B %Y')); ?>
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
</div>