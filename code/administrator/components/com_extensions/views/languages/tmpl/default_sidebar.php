<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<h3><?= @text( 'Application' ); ?></h3>
<nav>
    <a <? if($state->application == 'site') echo 'class="active"' ?> href="<?= @route('application=site') ?>">
    	<?= @text('Site') ?>
    </a>
    <a <? if($state->application == 'administrator') echo 'class="active"' ?> href="<?= @route('application=administrator') ?>">
    	<?= @text('Administrator') ?>
    </a>
</nav>