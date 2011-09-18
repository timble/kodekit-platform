<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access'); ?>

<div id="sidebar">
	<h3><?= @text( 'Types' ); ?></h3>
    <ul>
        <li <? if($this->getView()->getName() == 'components') echo 'class="active"' ?>>
        	<a href="<?= @route('view=components') ?>">
        	    <?= @text('Components') ?>
        	</a>
        </li>
        <li <? if($this->getView()->getName() == 'plugins') echo 'class="active"' ?>>
        	<a href="<?= @route('view=plugins') ?>">
        	    <?= @text('Plugins') ?>
        	</a>
        </li>
        <li <? if($this->getView()->getName() == 'modules') echo 'class="active"' ?>>
        	<a href="<?= @route('view=modules') ?>">
        	    <?= @text('Modules') ?>
        	</a>
        </li>  
        <li <? if($this->getView()->getName() == 'templates') echo 'class="active"' ?>>
        	<a href="<?= @route('view=templates') ?>">
        	    <?= @text('Templates') ?>
        	</a>
        </li>
        <li <? if($this->getView()->getName() == 'languages') echo 'class="active"' ?>>
        	<a href="<?= @route('view=languages') ?>">
        	    <?= @text('Languages') ?>
        	</a>
        </li>
    </ul>
</div>