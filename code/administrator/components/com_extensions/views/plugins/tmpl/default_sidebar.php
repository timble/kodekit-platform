<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<h3><?= @text( 'Types' ); ?></h3>
<ul class="scrollable">
    <li <? if(!$state->type) echo 'class="active"' ?>>
        <a href="<?= @route('&type=') ?>">
            <?= @text('All types') ?>
        </a>
    </li>
    <? foreach($types as $type) : ?>
    <li <? if($state->type == $type) echo 'class="active"' ?>>
        <a href="<?= @route('type='.$type) ?>">
            <?= $type ?>
        </a>
    </li>
    <? endforeach ?>
</ul>