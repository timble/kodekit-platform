<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<? if ( $params->def( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?= @escape($params->get('pageclass_sfx')); ?>">
		<?= @escape($params->get('page_title')); ?>
	</div>
<? endif; ?>

<ul>
<? foreach($categories as $category) : ?>
	<li>
		<a href="<?= @route('view=weblinks&category='. $category->id.':'.$category->slug) ?>" class="category<?= @escape($params->get( 'pageclass_sfx' )); ?>">
			<?= @escape($category->title);?>
		</a>
		&nbsp;
		<span class="small">
			(<?= $category->numlinks;?>)
		</span>
	</li>
<? endforeach; ?>
</ul>