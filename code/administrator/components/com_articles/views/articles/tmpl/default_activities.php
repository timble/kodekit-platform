<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<h3><?= @text('Recent Activities')?></h3>
<div class="scrollable">
	<?= @service('com://admin/activities.controller.activity')
			->view('activities')
			->layout('simple')
			->package('articles')
			->name('article')
			->limit('10')
			->display(); ?>
</div>