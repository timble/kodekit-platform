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

<? if ($params->get('show_page_title')): ?>
<h1 class="page-header"><?php echo @escape($params->get('page_title')); ?></h1>
<? endif; ?>

<? if ($params->get('show_description_image') && $category->image): ?>
<img src="<? echo @service('koowa:http.url',
    array('url' => $files_params->get('image_path') . '/' . $category->image));?>"
     align="<?php echo $category->image_position;?>" hspace="6" alt=""/>
<? endif; ?>

<? if ($params->get('show_description') && $category->description): ?>
<? echo @escape($category->description); ?>
<? endif; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
    <? if ($params->get('show_headings')): ?>
    <thead>
    <tr>
        <th>
            <? echo JText::_('Item Title'); ?>
        </th>
        <? if ($params->get('show_create_date')): ?>
        <th>
            <? echo JText::_('Date'); ?>
        </th>
        <? endif; ?>
    </tr>
    </thead>
    <? endif; ?>
    <? foreach ($articles as $article): ?>
    <tr>
        <td>
            <? echo @helper('com://site/articles.template.helper.article.link', array(
            'row'  => $article,
            'text' => @escape($article->title))); ?>
        </td>
        <? if ($params->get('show_create_date')) : ?>
        <td>
            <? echo @service('koowa:template.helper.date')->format(array(
            'date'   => $article->created)); ?>
        </td>
        <? endif; ?>
    </tr>
    <? endforeach; ?>
</table>

<? echo count($articles) == $total_articles ? '' : @helper('paginator.pagination',
    array(
        'limit'      => $params->get('articles_per_page'),
        'offset'     => $state->offset,
        'total'      => $total_articles,
        'show_limit' => false));?>