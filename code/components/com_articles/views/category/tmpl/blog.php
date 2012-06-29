<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');
?>


<? echo @template('header'); ?>

<? echo @template('com://site/articles.view.articles.list'); ?>

<? if ($params->get('show_feed_link')): ?>
<? echo @helper('com://site/articles.template.helper.rss.link', array(
        'url' => @service('com://site/articles.helper.route')->getCategoryRoute($category->id,
            $category->section_id))); ?>
<? endif; ?>

<? echo count($articles) == $total_articles ? '' : @helper('paginator.pagination',
    array(
        'limit'      => $params->get('articles_per_page'),
        'offset'     => $state->offset,
        'total'      => $total_articles,
        'show_limit' => false)); ?>