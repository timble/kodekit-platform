<?php
/**
 * @version     $Id: default_sidebar.php 1625 2011-06-07 15:22:36Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="sidebar">
    <h3><?= @text( 'Groups' ); ?></h3>
    <ul>
        <li <? if(!$state->group) echo 'class="active"' ?>>
            <a href="<?= @route('&group=') ?>">
                <?= @text('All groups') ?>
            </a>
        </li>
        <? foreach($groups as $group) : ?>
        <li <? if($state->group == $group->name) echo 'class="active"' ?>>
            <a href="<?= @route('group='.$group->name) ?>">
                <?= $group->name; ?>
            </a>
        </li>
        <? endforeach ?>
    </ul>
    <h3><?= @text( 'Details' ); ?></h3>
    <p><?= @text('Files').':'.$count ?></p>
    <p><?= @text('Size').':'.number_format($size / 1024, 2) ?></p>
</div>