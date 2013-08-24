<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<title content="replace"><?= $category->title ?></title>

<div class="page-header">
    <h1><?= escape($params->get('page_title')); ?></h1>
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
            <?= translate('Title'); ?>
        </th>
        <? if ($params->get('show_create_date')): ?>
        <th>
            <?= translate('Date'); ?>
        </th>
        <? endif; ?>
    </tr>
    </thead>
    <? foreach ($articles as $article): ?>
    <tr>
        <td>
            <a href="<?= helper('route.article', array('row' => $article)) ?>"><?= $article->title ?></a>
        </td>
        <? if ($params->get('show_create_date')) : ?>
        <td nowrap="nowrap">
            <?= helper('date.format', array('date'   => $article->created)); ?>
        </td>
        <? endif; ?>
    </tr>
    <? endforeach; ?>
</table>

<?= helper('paginator.pagination',array(
        'limit'      => $params->get('articles_per_page', 10),
        'total'      => $total,
        'show_limit' => false,
        'show_count' => false)
); ?>