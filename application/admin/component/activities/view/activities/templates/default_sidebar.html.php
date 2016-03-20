<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.validator') ?>

<script data-inline>
    window.addEvent('domready', function () {
        /* Reset the filter values to blank */
        document.id('activities-filter').addEvent('reset', function (e) {
            e.target.getElements('input').each(function (el) {
                if (['day_range', 'end_date', 'user'].contains(el.name)) {
                    el.value = '';
                }
            });
            e.target.submit();
        });
    });
</script>


<h3><?= translate('Extensions') ?></h3>
<ul class="navigation">
    <li>
        <a class="<?= empty(parameters()->package) ? 'active' : ''; ?>" href="<?= route('package=') ?>">
            <?= translate('All extensions') ?>
        </a>
    </li>
    <? foreach ($packages as $package): ?>
        <li>
            <a <?= $package == parameters()->package ? 'class="active"' : '' ?> href="<?= route('package=' . $package) ?>">
                <?= ucfirst($package) ?>
            </a>
        </li>
    <? endforeach ?>
</ul>

<h3><?= translate('Filters') ?></h3>
<form action="" method="get" id="activities-filter">
    <fieldset>
        <div class="input-prepend">
            <span class="add-on"><?=translate('Show activities until')?></span>
            <input type="date" name="end_date" value="<?= parameters()->end_date ?>"/>
        </div>
        <div class="input-prepend">
            <span class="add-on"><?=translate('Going back')?></span>
            <input type="text" name="day_range" value="<?= (parameters()->day_range) ? parameters()->day_range : '' ?>"/>
        </div>
        <div class="input-prepend">
            <span class="add-on">User</span>
            <?=
            helper('com:users.listbox.users',
                array(
                    'autocomplete' => true,
                    'name'         => 'user',
                    'validate'     => false,
                    'attribs'      => array('size' => null),
                )) ?>
        </div>
        <div class="button__group">
            <input type="submit" name="submitfilter" class="button" value="<?= translate('Filter') ?>"/>
            <input type="reset" name="cancelfilter" class="button" value="<?= translate('Reset') ?>"/>
        </div>
    </fieldset>
</form>
