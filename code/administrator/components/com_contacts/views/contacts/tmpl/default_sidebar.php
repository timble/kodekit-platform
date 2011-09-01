<?php
/**
 * @version     $Id: default.php 1708 2011-06-10 20:46:02Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
 
defined('KOOWA') or die('Restricted access'); ?>

<div id="sidebar">
	<h3><?= @text('Categories') ?></h3>
	<?= @template('com://admin/categories.view.categories.list', array('categories' => KFactory::get('com://admin/categories.model.categories')->section('com_contact_details')->sort('title')->getList())); ?>
</div>