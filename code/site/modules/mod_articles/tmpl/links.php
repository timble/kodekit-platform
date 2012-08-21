<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>

<ul class="mod_articles">
    <?php foreach ($articles as $article): ?>
    <li class="mod_articles">
        <?php echo @helper('com://site/articles.template.helper.article.link',
        array(
            'row'     => $article,
            'text'    => @escape($article->title),
            'attribs' => array('class' => 'mod_articles')));?>
    </li>
    <?php endforeach; ?>
</ul>