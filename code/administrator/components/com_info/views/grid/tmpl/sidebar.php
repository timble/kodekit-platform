<?php
/**
 * @version     $Id: sidebar.php 3024 2011-10-09 01:44:30Z johanjanssens $
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
        <li <? if($this->getView()->getName() == 'system') echo 'class="active"' ?>>
        	<a href="<?= @route('view=system') ?>">
        	    <?= @text('System Information') ?>
        	</a>
        </li>
        <li <? if($this->getView()->getName() == 'configuration') echo 'class="active"' ?>>
        	<a href="<?= @route('view=configuration') ?>">
        	    <?= @text('Configuration File') ?>
        	</a>
        </li>
        <li <? if($this->getView()->getName() == 'directories') echo 'class="active"' ?>>
        	<a href="<?= @route('view=directories') ?>">
        	    <?= @text('Directory Permissions') ?>
        	</a>
        </li>  
        <li <? if($this->getView()->getName() == 'phpinformation') echo 'class="active"' ?>>
        	<a href="<?= @route('view=phpinformation') ?>">
        	    <?= @text('PHP Information') ?>
        	</a>
        </li>
        <li <? if($this->getView()->getName() == 'phpsettings') echo 'class="active"' ?>>
        	<a href="<?= @route('view=phpsettings') ?>">
        	    <?= @text('PHP Settings') ?>
        	</a>
        </li>
    </ul>
</div>