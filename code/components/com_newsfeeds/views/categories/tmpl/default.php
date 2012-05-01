<?
/**
 * @version		$Id: default.php 3314 2012-02-10 02:14:52Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<? if ( $params->def( 'show_page_title', 1 ) ) : ?>
<h1 class="page-header"><?= @escape($params->get('page_title')); ?></h1>
<? endif; ?>

<ul>
<? foreach($categories as $category) : ?>
	<li>
		<a href="<?= @route('view=newsfeeds&category='. $category->id.':'.$category->slug) ?>" class="category<?= @escape($params->get( 'pageclass_sfx' )); ?>">
			<?= @escape($category->title);?>
		</a>
		<? if ( $params->get( 'show_cat_items' ) ) : ?>
		&nbsp;
		<span class="small">
			(<?= $category->numlinks;?>)
		</span>
		<? endif; ?>
		<? if ( $params->get( 'show_cat_description' ) && $category->description ) : ?>
		<br />
		<?= @escape($category->description); ?>
		<? endif; ?>
	</li>
<? endforeach; ?>
</ul>