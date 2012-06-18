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

<? $view = $this->getView()->setArticles(); ?>

<? echo @template('com://site/articles.view.articles.list', array('articles' => $view->articles->list)); ?>

<? if ($params->get('show_feed_link')): ?>
<? echo @helper('com://site/articles.template.helper.section.rss', array('row' => $section)); ?>
<? endif; ?>

<? echo count($view->articles->list) == $view->articles->total ? '' : @helper('paginator.pagination',
    array(
        'limit'      => $params->get('articles_per_page'),
        'offset'     => $state->offset,
        'total'      => $view->articles->count,
        'show_limit' => false)); ?>