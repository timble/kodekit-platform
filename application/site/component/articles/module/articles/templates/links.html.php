<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<? if($show_title) : ?>
<h3><?= $module->title ?></h3>
<? endif ?>

<ul>
    <?php foreach ($articles as $article): ?>
    <li>
        <a href="<?php echo helper('com:articles.route.article', array('row' => $article)) ?>"><?php echo escape($article->title) ?></a>
    </li>
    <?php endforeach; ?>
</ul>