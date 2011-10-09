<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access'); ?>

<!--<script src="media://lib_koowa/js/koowa.js" />-->
<!--<style src="media://lib_koowa/css/koowa.css" />-->
<?= @helper('behavior.tooltip') ?>

<?= @template('com://admin//installer.view.grid.sidebar'); ?>

<div class="-installer-grid">
<form action="<?= @route() ?>" method="get" class="-koowa-grid">
    <table class="adminlist">
    	<thead>
    		<tr>
    			<th class="title" width="20px"></th>
    			<th class="title" nowrap="nowrap">
    			    <?= @text('Currently Installed') ?>
    			</th>
    			<th class="title" width="5%" align="center">
    			    <?= @text('Enabled') ?>
    			</th>
    			<th class="title" width="10%" align="center">
    			    <?= @text('Version') ?>
    			</th>
    			<th class="title" width="15%">
    			    <?= @text('Date') ?>
    			</th>
    			<th class="title" width="25%">
    			    <?= @text('Author') ?>
    			</th>
    		</tr>
    	</thead>
    	<tfoot>
    	    <tr>
    		    <td colspan="6">
    		        <?= @helper('paginator.pagination', array('total' => $total)) ?>
    		    </td>
    		</tr>
    	</tfoot>
    	<tbody>
    	<? foreach($components as $component): ?>
    		<tr>
    			<td align="center">
    				<input type="radio" name="id[]" value="<?= $component->id ?>" class="-koowa-grid-checkbox" />
    			</td>
    			<td>
    				<?= $component->name ?>
    			</td>
    			<td align="center">
    			<? if (!$component->option) : ?>
    				<strong>X</strong>
    			<? else : ?>
    				<?= @helper( 'grid.enable' , array('row' => $component)) ?>
    			<? endif ?>
    			</td>
    			<td align="center">
    			    <?= $component->version ?>
    			</td>
    			<td>
    			    <?= @helper('date.format', array('date' => $component->creationDate, 'format' => '%d %B %Y')) ?>
    			</td>
    			<td>
    				<span class="hasTip" title="<?= @text('Author Information') ?>::<?= $component->authorEmail.'<br />'.$component->authorUrl ?>">
    					<?= $component->author ?>
    				</span>
    			</td>
    		</tr>
    	<? endforeach ?>
    	</tbody>
    </table>
    </form>
    <?= @template('com://admin//installer.view.install.form'); ?>
</div>