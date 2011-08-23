<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<div id="sidebar">
	<h3><?= @text('Menus'); ?></h3>
	<?= @template('admin::com.pages.view.menus.list', array('state' => $state, 'menus' => KFactory::tmp('admin::com.pages.model.menus')->getList())); ?>
</div>