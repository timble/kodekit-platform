<?php
/**
 * @version     $Id: default_sidebar.php 3030 2011-10-09 13:21:09Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<div class="sidebar">
    <h3><?= @text('Menus') ?></h3>
    <?= @template('com://admin/pages.view.menus.list', array('state' => $state, 'menus' => @service('com://admin/pages.model.menus')->getList())); ?>
</div>
