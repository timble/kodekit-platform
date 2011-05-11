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

<form action="<?= @route('name='.$template->name.'&client='.$state->client) ?>" method="post" name="adminForm">
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

        <? if(!$state->client) : ?>
            <fieldset class="adminform">
                <legend><?= @text('Menu Assignment') ?></legend>
                <table class="admintable" cellspacing="1">
                    <tr>
                        <td valign="top" class="key">
                            <?= @text('Menus') ?>:
                        </td>
                        <td>
                        <? if($template->pages == 'all') : ?>
                            <?= @text('Cannot assign default template') ?>
                            <input type="hidden" name="default" value="1" />
                        <? else : ?>
                            <script type="text/javascript">
                            window.addEvent('domready', function(){
                                var selections = $('selections'),
                                    setSelections = function(disabled, selected){
                                        this.disabled = disabled;
                                        $$(this.options).each(function(option){
                                            option.disabled = disabled;
                                            if(selected !== null) option.selected = selected;
                                        });
                                    };
                                $('menus-none').addEvent('change', function(){
                                    setSelections.call(selections, true, false);
                                });
                                $('menus-select').addEvent('change', function(){
                                    setSelections.call(selections, false, null);
                                });
                            
                                <? if($template->pages == 'none') : ?>
                                    $('menus-none').fireEvent('change');
                                <? endif ?>
                            });
                            </script>
                        
                            <label for="menus-none">
                                <input id="menus-none" type="radio" name="menus" value="none" <? if($template->pages == 'none') echo 'checked="checked"' ?> />
                                <?= @text('None') ?>
                            </label>
                            <label for="menus-select">
                                <input id="menus-select" type="radio" name="menus" value="select" <? if($template->pages != 'none') echo 'checked="checked"' ?> />
                                <?= @text('Select From List') ?>
                            </label>
                        <? endif ?>
                        </td>
                    </tr>
                    <? if ($template->pages != 'all') : ?>
                    <tr>
                        <td valign="top" class="key">
                            <?= @text('Menu Selection') ?>:
                        </td>
                        <td>
                            <?= JHTML::_('select.genericlist', JHTML::_('menu.linkoptions'), 'selections[]', 'class="inputbox" size="15" multiple="multiple"', 'value', 'text', $template->assigned, 'selections') ?>
                        </td>
                    </tr>
                    <? endif ?>
                </table>
            </fieldset>
        <? endif ?>
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