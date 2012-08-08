<?
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_articles/css/site.css"/>

<? if ($params->get('show_page_title')): ?>
<h1 class="page-header"><?php echo @escape($params->get('page_title')); ?></h1>
<? endif; ?>

<? if ($params->get('show_description_image') && $category->image): ?>
    <img src="<?= $category->image->path ?>" height="<?= $category->image->height ?>" width="<?= $category->image->width ?>" />
<? endif; ?>


<? if ($params->get('show_description') && $category->description): ?>
<?= $category->description; ?>
<? endif; ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
    <? if ($params->get('show_headings')): ?>
    <thead>
    <tr>
        <th>
            <?= @text('Item Title'); ?>
        </th>
        <? if ($params->get('show_create_date')): ?>
        <th>
            <?= @text('Date'); ?>
        </th>
        <? endif; ?>
    </tr>
    </thead>
    <? endif; ?>
    <? foreach ($articles as $article): ?>
    <tr>
        <td>
            <a href="<?= @helper('route.article', array('row' => $article)) ?>"><?= $article->title ?></a>
        </td>
        <? if ($params->get('show_create_date')) : ?>
        <td>
            <?= @helper('date.format', array('date'   => $article->created)); ?>
        </td>
        <? endif; ?>
    </tr>
    <? endforeach; ?>
</table>

<? if(count($articles) != $total) : ?>
    <?= @helper('paginator.pagination',array(
            'limit'      => $params->get('articles_per_page'),
            'total'      => $total,
            'show_limit' => false)
    ); ?>
<? endif; ?>