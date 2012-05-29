<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @author         Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');
?>

<? echo @template('header'); ?>

<? $view = $this->getView()->setCategories(); ?>

<? foreach ($view->categories->list as $category): ?>
<? echo @template('category', array('category' => $category)); ?>
<? endforeach; ?>

<? if ($params->get('show_pagination')): ?>
<? echo ($view->categories->total == count($view->categories->list)) ? '' : @helper('paginator.pagination', array(
        'limit'      => $params->get('categories_per_page'),
        'offset'     => $state->offset,
        'total'      => $view->categories->total,
        'show_limit' => false)); ?>
<? endif; ?>
