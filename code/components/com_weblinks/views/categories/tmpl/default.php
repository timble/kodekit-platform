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
<h1><?= @escape($params->get('page_title')); ?></h1>
<? endif; ?>

<? foreach($categories as $category) : ?>
	<h2>
		<a href="<?= @route('view=weblinks&category='. $category->id.':'.$category->slug) ?>" class="category<?= @escape($params->get( 'pageclass_sfx' )); ?>">
			<?= @escape($category->title);?>
		</a>		
	</h2>
	<p>
	    <?= @escape($category->description);?>
	</p>
<? endforeach; ?>