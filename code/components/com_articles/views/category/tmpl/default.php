<?
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
            'date'   => $article->created,
            'format' => $params->get('date_format', JText::_('DATE_FORMAT_LC2')))); ?>
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