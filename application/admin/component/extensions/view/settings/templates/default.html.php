<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<!--
<script src="assets://js/koowa.js" />
<style src="assets://css/koowa.css" />
-->

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="post" class="-koowa-form" >
    <div class="tabs tabs-vertical">
        <h3><?= translate('System')?></h3>
        <div class="tab">
            <input type="radio" id="tab-1" name="tab-group-1" checked="">
            <label for="tab-1"><?= translate('Global') ?></label>
            <div class="content">
                <?= import('default_global.html',  array('settings' => $settings->system)); ?>
            </div>
        </div>

        <div class="tab">
            <input type="radio" id="tab-2" name="tab-group-1">
            <label for="tab-2"><?= translate('Site') ?></label>
            <div class="content">
                <?= import('default_site.html',  array('settings' => $settings->system)); ?>
            </div>
        </div>

        <div class="tab">
            <input type="radio" id="tab-3" name="tab-group-1">
            <label for="tab-3"><?= translate('Mail') ?></label>
            <div class="content">
                <?= import('default_mail.html',  array('settings' => $settings->system)); ?>
            </div>
        </div>

        <h3><?= translate('Extensions')?></h3>
        <? foreach($settings as $name => $setting) : ?>
        <? if($setting->getType() == 'extension' && $setting->getPath()) : ?>
        <div class="tab">
            <input type="radio" id="tab-<?= $setting->getName() ?>" name="tab-group-1">
            <label for="tab-<?= $setting->getName() ?>"><?= translate(ucfirst($setting->getName())) ?></label>
            <div class="content">
                <?= import('default_extension.html', array('settings' => $setting)); ?>
            </div>
        </div>
        <? endif; ?>
        <? endforeach; ?>
    </div>
</form>



