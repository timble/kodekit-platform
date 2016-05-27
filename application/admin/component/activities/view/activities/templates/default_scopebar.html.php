<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<div class="scopebar">
    <div class="scopebar__group">
        <a class="<?= is_null(parameter('action')) && is_null(parameter('application')) ? 'active' : ''; ?>" href="<?= route('application=&action=' ) ?>">
            <?= translate('All') ?>
        </a>
    </div>
    <div class="scopebar__group">
        <a  class="<?= (parameter('action') == 'add') ? 'active' : ''; ?> separator-left" href="<?= route('action=add' ) ?>">
            <?= translate('Created') ?>
        </a>
        <a class="<?= (parameter('action') == 'edit') ? 'active' : ''; ?>" href="<?= route('action=edit' ) ?>">
            <?= translate('Updated') ?>
        </a>
        <a class="<?= (parameter('action') == 'delete') ? 'active' : ''; ?>" href="<?= route('action=delete' ) ?>">
            <?= translate('Trashed') ?>
        </a>
    </div>
    <div class="scopebar__group">
        <a class="<?= (parameter('sort') == '-created_on') ? 'active' : ''; ?>" href="<?= route(parameter('sort') == '-created_on' ? 'sort=' : 'sort=-created_on' ) ?>">
            <?= translate('Latest First') ?>
        </a>
        <a class="<?= (parameter('sort') == 'created_on') ? 'active' : ''; ?>" href="<?= route(parameter('sort') == 'created_on' ? 'sort=' : 'sort=created_on' ) ?>">
            <?= translate('Oldest First') ?>
        </a>
    </div>
    </ul>
</div>