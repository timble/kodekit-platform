<?
/**
 * @version		$Id: default.php 3537 2012-04-02 17:56:59Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<? if ( $params->def( 'show_page_title', 1 ) ) : ?>
<h1><?= @escape($params->get('page_title')); ?></h1>
<? endif; ?>

<? foreach($categories as $category) : ?>
<h2>
	<a href="<?= @helper('route.category', array('row' => $category)) ?>" class="category">
		<?= @escape($category->title);?>
	</a>
</h2>
<p><?= $category->description;?></p>
<? endforeach; ?>
