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


<? if ($total = $category->getArticles(array('model_state' => array('aid' => $aid)))->count): ?>
    <h2>
        <? echo @helper('com://site/articles.template.helper.category.link', array('row' => $category)); ?>
    </h2>

    <? if ($params->get('show_category_description') && $category->description): ?>
        <p><? echo @escape($category->description); ?></p>
    <? endif; ?>

    <? echo @helper('com://site/articles.template.helper.category.totalarticles', array('total' => $total)); ?>
<? endif; ?>