<?php
/**
 * @version     $Id: default.php 1708 2011-06-10 20:46:02Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
  
defined('KOOWA') or die('Restricted access'); ?>

<h3><?= @text('Categories') ?></h3>

<?= @template('com://admin/categories.view.categories.list', array('categories' => @service('com://admin/contacts.model.categories')->sort('title')->table('contacts')->getList())); ?>