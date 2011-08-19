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
<style src="media://lib_koowa/css/koowa.css" />

<?= @helper('behavior.tooltip') ?>

<?= @template('default_sidebar'); ?>

<form action="<?= @route() ?>" method="get" class="-koowa-grid">
    <table class="adminlist">
        <thead>
            <tr>
                <th width="10"></th>
                <th>
                    <?= @text('Name') ?>
                </th>
                <th width="10%" align="center">
                    <?= @text('Version') ?>
                </th>
                <th width="15%" class="title">
                    <?= @text('Date') ?>
                </th>
                <th width="25%" class="title">
                    <?= @text('Author') ?>
                </th>
                <th width="25%">
    				<?= @text('Author Email'); ?>
    			</th>
            </tr>
        </thead>
        <tbody>
        <? foreach($templates as $i => $template) :?>
            <tr>
                <td>
                    <input type="radio" id="name" name="name" value="<?= $template->name ?>" class="-koowa-grid-checkbox" />
                </td>
                <td>
                    <span class="editlinktip hasTip" title="<?= $template->title ?>::<img border=&quot;1&quot; src=&quot;<?= $templateurl.'/'.$template->name.'/template_thumbnail.png' ?>&quot; name=&quot;imagelib&quot; alt=&quot;<?= @text( 'No preview available' ); ?>&quot; width=&quot;206&quot; height=&quot;145&quot; />">
                        <a href="<?= @route('&view=template&name='.$template->title.'&application='.$state->application) ?>">
                            <?= $template->title ?>
                        </a>
                        <? if($template->default) : ?>
                   		 <img src="media://system/images/star.png" alt="<?= @text('Default') ?>" />
                        <? endif ?>
                    </span>
                </td>
                <td align="center">
                    <?= $template->version ?>
                </td>
                <td align="center">
                	<? if ((string) $template->creationDate): ?>
                        <?= @date(array('date' => $template->creationDate, 'format' => '%d %B %Y')); ?>
                    <? endif; ?>
                </td>
                <td align="center">
                    <span class="editlinktip hasTip" title="<?= @text('Author Information') ?>::<?= $template->authorEmail . '<br />' . @ $template->authorUrl ?>">
                        <?= $template->author ?>
                    </span>
                </td>
                <td align="center">
    				<?= $template->authorEmail; ?>
    			</td>
            </tr>
        <? endforeach ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="20">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>
    </table>
</form>