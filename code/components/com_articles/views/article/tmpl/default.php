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

<style src="media://com_articles/css/site.css"/>

<? if ($params->get('show_page_title') && ($params->get('page_title') != $article->title)): ?>
<h1 class="page-header"><?=@escape($params->get('page_title')); ?></h1>
<? endif; ?>

<? echo @helper('com://site/articles.template.helper.article.edit', array('row' => $article)); ?>
<div class="clear_both"></div>
<? echo @helper('com://site/articles.template.helper.article.render', array(
    'row'       => $article,
    'show_more' => false, 'linkable' => false)); ?>