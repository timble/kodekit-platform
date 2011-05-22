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

<form action="<?= @route('name='.$template->name.'&application='.$state->application) ?>" method="post" class="-koowa-form">
    <div class="col width-50">
        <fieldset class="adminform">
            <legend><?= @text('Details') ?></legend>
            <table class="admintable">
                <tr>
                    <td valign="top" class="key">
                        <?= @text('Name') ?>:
                    </td>
                    <td>
                        <strong>
                            <?= $template->name ?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="key">
                        <?= @text('Description') ?>:
                    </td>
                    <td>
                        <?= @text($template->description) ?>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class="col width-50">
        <fieldset class="adminform">
            <legend><?= @text('Parameters') ?></legend>
            <? if($html = $params->render()) : ?>
                <?= $html ?>
            <? else : ?>
                <div style="text-align: center; padding: 5px;">
                    <?= @text('No Parameters') ?>
                </div>
            <? endif ?>
        </fieldset>
    </div>
</form>