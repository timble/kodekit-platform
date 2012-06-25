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


<style src="media://com_articles/css/site.css"/>

<? foreach ($articles as $article): ?>

<? if ($article->state == 0): ?>
    <div class="articles-unpublished">
<? endif; ?>
    <? echo @helper('com://site/articles.template.helper.article.render', array(
        'row'       => $article)); ?>
<? if ($article->state == 0): ?>
    </div>
<? endif; ?>
<? endforeach; ?>