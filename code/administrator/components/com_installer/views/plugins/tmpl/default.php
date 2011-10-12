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

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
<?= @helper('behavior.tooltip') ?>

<?= @template('com://admin//installer.view.grid.sidebar'); ?>

<div class="-installer-grid">
<form action="" method="get" class="-koowa-grid">
   <table class="adminlist">
    	<thead>
    		<tr>
    			<th class="title" width="20px">
    			    <?= @helper('grid.checkall') ?>
    			</th>
    			<th class="title">
    			    <?= @text('Plugin') ?>
    			</th>
    			<th class="title" width="7%" align="center">
    			    <?= @text('Type') ?>
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
    		<tr>
    		    <td></td>
    		    <td></td>
    		    <td>
    		        <?= @helper('listbox.types') ?>
    		    </td>
    		    <td></td>
    		    <td></td>
    		    <td></td>
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
    	<? foreach($plugins as $plugin) : ?>
    		<tr <? if($plugin->iscore) echo 'data-readonly' ?>>
    			<td align="center">
    			    <input type="checkbox" name="id[]" value="<?= $plugin->id ?>-0" class="-koowa-grid-checkbox" <? if($plugin->iscore) echo 'disabled title="'.@escape(@text('DESCPLUGINS')).'"' ?> />
    			</td>
    			<td>
    				<?= $plugin->name ?>
    			</td>
    			<td align="center">
    			    <?= $plugin->type ?>
    			</td>
    			<td align="center">
    			    <?= $plugin->version ?>
    			</td>
    			<td>
    			    <?= @helper('date.format', array('date' => $plugin->creationDate, 'format' => '%d %B %Y')) ?>
    			</td>
    			<td>
    				<span class="hasTip" title="<?= @text('Author Information') ?>::<?= $plugin->authorEmail.'<br />'.$plugin->authorUrl ?>">
    					<?= $plugin->author ?>
    				</span>
    			</td>
    		</tr>
    	<? endforeach ?>
    	</tbody>
    </table>
    </form>
    <?= @template('com://admin//installer.view.install.form'); ?>
</div>