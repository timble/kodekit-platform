<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<script src="media://lib_koowa/js/koowa.js" />
<?= @helper('behavior.tooltip') ?>

<div class="col width-15 menus">
    <ul>
        <li <? if(!$state->client) echo 'class="active"' ?>>
        	<a href="<?= @route('&client=0') ?>">
        	    <?= @text('Site') ?>
        	</a>
        </li>
        <li <? if($state->client) echo 'class="active"' ?>>
        	<a href="<?= @route('&client=1') ?>">
        	    <?= @text('Administrator') ?>
        	</a>
        </li>
    </ul>
</div>
<div class="col width-85">
    <form action="<?= @route() ?>" method="get" name="adminForm">
        <table class="adminlist">
            <thead>
                <tr>
                    <th width="20"></th>
                    <th class="title">
                        <?= @text('Name') ?>
                    </th>
                    <th width="5%">
                        <?= @text('Default') ?>
                    </th>
                    <? if(!$state->client) : ?>
                    <th width="5%">
                        <?= @text('Assigned') ?>
                    </th>
                    <? endif ?>
                    <th width="10%" align="center">
                        <?= @text('Version') ?>
                    </th>
                    <th width="15%" class="title">
                        <?= @text('Date') ?>
                    </th>
                    <th width="25%" class="title">
                        <?= @text('Author') ?>
                    </th>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="6">
                    	<?= @text( 'Filter' ) ?>:
                    	<?= @template('admin::com.default.view.list.search_form') ?>
                    </td>
                </tr>
            </thead>
            <tfoot>
            <? if($templates) : ?>
                <tr>
                    <td colspan="20">
                        <?= @helper('paginator.pagination', array('total' => $total)) ?>
                    </td>
                </tr>
            <? endif ?>
            </tfoot>
            <tbody>
            <? foreach($templates as $i => $template) : ?>
                <tr>
                    <td width="5">
                        <input type="radio" id="name" name="name" value="<?= $template->name ?>" class="-koowa-grid-checkbox" />
                    </td>
                    <td>
                        <span class="editlinktip hasTip" title="<?= $template->name ?>::<img border=&quot;1&quot; src=&quot;<?= $templateurl.'/'.$template->name.'/template_thumbnail.png' ?>&quot; name=&quot;imagelib&quot; alt=&quot;<?= @text( 'No preview available' ); ?>&quot; width=&quot;206&quot; height=&quot;145&quot; />">
                            <a href="<?= @route('&view=template&name='.$template->name) ?>">
                                <?= $template->name ?>
                            </a>
                        </span>
                    </td>
                    
                    <td align="center">
                    <? if($template->default) : ?>
                        <img src="media://system/images/star.png" alt="<?= @text('Default') ?>" />
                    <? endif ?>
                    </td>
                    <? if(!$state->client) : ?>
                        <td align="center">
                        <? if($template->assigned) : ?>
                            <img src="media://system/images/tick.png" alt="<?= @text('Assigned') ?>" />
                        <? endif ?>
                        </td>
                    <? endif ?>
                    <td align="center">
                        <?= $template->version ?>
                    </td>
                    <td>
                        <?= $template->creationDate ?>
                    </td>
                    <td>
                        <span class="editlinktip hasTip" title="<?= @text('Author Information') ?>::<?= $template->authorEmail . '<br />' . @ $template->authorUrl ?>">
                            <?= $template->author ?>
                        </span>
                    </td>
                </tr>
            <? endforeach ?>
            </tbody>
        </table>
    </form>
</div>