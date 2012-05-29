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

<? if (($total = $category->getTotalArticles(array('model_state' => array('aid' => $aid)))) || $params->get('show_empty_categories')): ?>
<h2>
    <? echo @helper('com://site/articles.template.helper.category.link', array('row' => $category)); ?>
</h2>

<? if ($params->get('show_category_description') && $category->description): ?>
    <p><? echo @escape($category->description); ?></p>
    <? endif; ?>

<? if ($params->get('show_cat_num_articles')): ?>
    <? echo @helper('com://site/articles.template.helper.category.totalarticles', array('total' => $total)); ?>
    <? endif; ?>
<? endif; ?>