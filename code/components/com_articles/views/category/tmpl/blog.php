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

<? echo @template('com://site/articles.view.articles.list'); ?>

<? echo count($articles) == $total_articles ? '' : @helper('paginator.pagination',
    array(
        'limit'      => $params->get('articles_per_page'),
        'offset'     => $state->offset,
        'total'      => $total_articles,
        'show_limit' => false)); ?>