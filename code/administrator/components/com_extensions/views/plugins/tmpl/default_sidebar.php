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
<nav class="scrollable">
    <a <? if(!$state->type) echo 'class="active"' ?> href="<?= @route('&type=') ?>">
        <?= @text('All types') ?>
    </a>
    <? foreach($types as $type) : ?>
    <a <? if($state->type == $type) echo 'class="active"' ?> href="<?= @route('type='.$type) ?>">
        <?= $type ?>
    </a>
    <? endforeach ?>
</nav>