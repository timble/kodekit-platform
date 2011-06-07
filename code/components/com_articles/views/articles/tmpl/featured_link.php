<?php
/**
 * @version     $Id: form.php 1638 2011-06-07 23:00:45Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<?
    $article_slug  = $article->id.($article->slug ? ':'.$article->slug : '');
    $category_slug = $article->category_id.($article->category_slug ? ':'.$article->category_slug : '');
?>

<li>
	<a class="blogsection" href="<?= @route(ContentHelperRoute::getArticleRoute($article_slug, $category_slug, $article->section_id)) ?>">
		<?= @escape($article->title) ?>
	</a>
</li>