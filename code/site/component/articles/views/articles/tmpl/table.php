<?
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>

<div class="page-header">
    <h1><?php echo @escape($params->get('page_title')); ?></h1>
</div>

<? if($params->get('show_description') && $category->description && $params->get('show_description_image') && $category->image) : ?>
<div class="clearfix">
<? if ($params->get('show_description') && $category->description): ?>
    <?= $category->description; ?>
<? endif; ?>

<? if ($params->get('show_description_image') && $category->image): ?>
    <img class="thumbnail" src="<?= $category->image->path ?>" align="right" height="<?= $category->image->height ?>" width="<?= $category->image->width ?>" />
<? endif; ?>
</div>
<? endif ?>

<table class="table table-striped">
    <thead>
    <tr>
        <th width="100%">
            <?= @text('Title'); ?>
        </th>
        <? if ($params->get('show_create_date')): ?>
        <th>
            <?= @text('Date'); ?>
        </th>
        <? endif; ?>
    </tr>
    </thead>
    <? foreach ($articles as $article): ?>
    <tr>
        <td>
            <a href="<?= @helper('route.article', array('row' => $article)) ?>"><?= $article->title ?></a>
        </td>
        <? if ($params->get('show_create_date')) : ?>
        <td nowrap="nowrap">
            <?= @helper('date.format', array('date'   => $article->created)); ?>
        </td>
        <? endif; ?>
    </tr>
    <? endforeach; ?>
</table>

<? if(count($articles) != $total) : ?>
    <?= @helper('paginator.pagination',array(
            'limit'      => $params->get('articles_per_page', 10),
            'total'      => $total,
            'show_limit' => false,
            'show_count' => false)
    ); ?>
<? endif; ?>