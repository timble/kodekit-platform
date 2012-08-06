<?php
/**
 * @version     $Id: form_modules.php 3030 2011-10-09 13:21:09Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>
<?= @helper('behavior.modal') ?>

<?= @helper('tabs.startPanel', array('title' => 'Modules')) ?>
<section id="pages-modules">
    <fieldset>
        <? foreach($modules->available as $module) : ?>
            <input type="hidden" name="modules[<?= $module->id ?>][others]" value="" />
            
            <a class="modal" href="<?= @route('option=com_pages&view=module&layout=modal&tmpl=component&module='.$module->id.'&page='.$page->id) ?>" rel="{handler: 'iframe', size: {x: 800, y: 400}}">
                <label>
                    <? $checked = count($modules->assigned->find(array('modules_module_id' => $module->id))) ? 'checked="checked"' : '' ?>
                    <input type="checkbox" name="modules[<?= $module->id ?>][current]" value="1" class="module-<?= $module->id ?>" <?= $checked ?>/>
                    
                    <?= $module->title ?>
                </label>
            </a><br>
        <? endforeach ?>
    </fieldset>
</section>
<?= @helper('tabs.endPanel') ?>
