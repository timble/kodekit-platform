<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Koowa
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<style src="media://com_koowa/css/koowa.css" />
<script src="media://com_koowa/js/updater.css" />

<div class="koowa-outer">
    <div class="koowa-inner">
        <? if (version_compare($latest, Koowa::getVersion())) : ?>
        <h2><?= sprintf(@text("You're currently using the latest Nooku Framework %s"), Koowa::getVersion()) ?></h2>
        <p><?= @text('Congratulations, you have nothing to update.') ?></p>
        <? else : ?>
        <h2><?= sprintf(@text("There is a new version of the Nooku Framework available: %s"), $latest) ?></h2>
        <form action="<?= @route('action=update') ?>" method="get" id="update">
            <input type="hidden" name="option" value="com_koowa" />
            <input type="hidden" name="view" value="dashboard" />
            <input type="hidden" name="action" value="update" />
            <button><?= sprintf(@text('Update Framework to %s'), $latest) ?></button>
            <span class="progress"></span>
            <span class="status"></span>
        </form>
        <? endif; ?>
    
    </div>
</div>