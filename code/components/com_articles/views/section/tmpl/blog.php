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

<? $view = $this->getView()->setArticles(); ?>

<? echo @template('com://site/articles.view.articles.list', array('articles' => $view->articles->list)); ?>

<? echo @helper('com://site/articles.template.helper.rss.link',
    array('url' => @service('com://site/articles.helper.route')->getSectionRoute($section->id))); ?>

<? echo count($view->articles->list) == $view->articles->count ? '' : @helper('paginator.pagination',
    array(
        'limit'      => $params->get('articles_per_page'),
        'offset'     => $state->offset,
        'total'      => $view->articles->count,
        'show_limit' => false)); ?>