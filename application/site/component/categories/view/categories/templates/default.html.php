<?
/**
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<? foreach($categories as $category) : ?>
<article>
<div class="page-header">
    <h1>
        <a href="<?= @helper('route.category', array('row' => $category)) ?>">
        	<?= @escape($category->title);?>
        </a>
    </h1>
</div>

<div class="clearfix">
    <? if($category->thumbnail) : ?>
        <a href="<?= @helper('route.category', array('row' => $category)) ?>">
            <img class="article__thumbnail" src="<?= $category->thumbnail ?>" />
        </a>
    <? endif ?>
	
	<? if ($category->description) : ?>
	<p><?= $category->description; ?></p>
	<? endif; ?>
</div>

<a href="<?= @helper('route.category', array('row' => $category)) ?>"><?= @text('Read more') ?></a>
</article>
<? endforeach; ?>
