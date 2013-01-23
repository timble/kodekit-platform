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

<? if($show_title) : ?>
<h3><?= $module->title ?></h3>
<? endif ?>

<ul>
    <?php foreach ($articles as $article): ?>
    <li>
        <a href="<?php echo @helper('com://site/articles.template.helper.route.article', array('row' => $article)) ?>"><?php echo @escape($article->title) ?></a>
    </li>
    <?php endforeach; ?>
</ul>