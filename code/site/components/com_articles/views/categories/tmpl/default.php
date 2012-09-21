<?
/**
 * @version		$Id: default.php 3647 2012-05-01 12:56:08Z tomjanssens $
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<? foreach($categories as $category) : ?>
	<div class="page-header">
	    <h1>
		    <a href="<?= @helper('route.category', array('row' => $category)) ?>">
			    <?= @escape($category->title);?>
		    </a>
	    </h1>
	</div>
	
	<? if (isset($category->image)) : ?>
		<?= @helper('com://site/categories.template.helper.string.image', array('row' => $category)) ?>
	<? endif; ?>
	
	<p>
	    <?= $category->description;?>
	</p>
<? endforeach; ?>


