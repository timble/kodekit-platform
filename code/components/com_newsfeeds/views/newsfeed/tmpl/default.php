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
<h1 class="page-header"><?= str_replace('&apos;', "'", $channel['title']); ?></h1>
<? endif; ?>

<? if($params->get( 'show_feed_description' ) ) : ?>
	<div>
		<?= str_replace('&apos;', "'", $channel['description']); ?>
		
		<? if(isset($image['url']) && isset($image['title']) && $params->get( 'show_feed_image' ) ) : ?>
			<img align="right" src="<?= $image['url']; ?>" alt="<?= $image['title']; ?>" />
		<? endif; ?>
	</div>
<? endif; ?>
<a href="<?= $channel['link']; ?>" target="_blank"><?= @text('Go to channel') ?>: <?= str_replace('&apos;', "'", $channel['title']); ?></a>

<? foreach ( $items as $item ) :  ?>
	<? if (!is_null( $item->get_link())) : ?>
		<h2><a href="<?= $item->get_link(); ?>"><?= $item->get_title(); ?></a></h2>
	<?php endif; ?>
	<?php if ( $params->get( 'show_item_description' ) && $item->get_description()) : ?>
		<?= @helper('text.limit', array('text' => $item->get_description(), 'words' => $params->get( 'feed_word_count' ))); ?>
	<? endif; ?>
<? endforeach; ?>

