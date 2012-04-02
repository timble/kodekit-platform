<?
/**
 * @version		$Id: default.php 3314 2012-02-10 02:14:52Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<? if ($params->get('show_page_title', 1)) : ?>
	<div class="componentheading<?= @escape($params->get('pageclass_sfx')); ?>"><?= @escape($params->get('page_title')); ?></div>
<? endif; ?>
<table width="100%" class="contentpane<?= @escape($params->get('pageclass_sfx')); ?>">
<tr>
	<td class="componentheading<?= @escape($params->get('pageclass_sfx')); ?>">
		<a href="<?= $channel['link']; ?>" target="_blank">
			<?= str_replace('&apos;', "'", $channel['title']); ?></a>
	</td>
</tr>
<? if($params->get( 'show_feed_description' ) ) : ?>
<tr>
	<td>
		<?= str_replace('&apos;', "'", $channel['description']); ?>
		<br />
		<br />
	</td>
</tr>
<? endif; ?>
<? if(isset($image['url']) && isset($image['title']) && $params->get( 'show_feed_image' ) ) : ?>
<tr>
	<td>
		<img src="<?= $image['url']; ?>" alt="<?= $image['title']; ?>" />
	</td>
</tr>
<? endif; ?>
<tr>
	<td>
		<ul>
		<? foreach ( $items as $item ) :  ?>
			<li>
			<? if (!is_null( $item->get_link())) : ?>
				<a href="<?= $item->get_link(); ?>" target="_blank">
					<?= $item->get_title(); ?></a>
			<?php endif; ?>
			<?php if ( $params->get( 'show_item_description' ) && $item->get_description()) : ?>
				<br />
				<? $text = $this->getView()->limitText($item->get_description(), $params->get( 'feed_word_count' )); ?>
				<?= str_replace('&apos;', "'", $text); ?>
				<br />
				<br />
			<? endif; ?>
			</li>
		<? endforeach; ?>
		</ul>
	</td>
</tr>
</table>