<?php
/**
 * @version     $Id: module.php 632 2011-03-20 14:28:45Z cristiano.cucco $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<div style="direction: <?= $module->params->get('rssrtl', 0) ? 'rtl' :'ltr'; ?>; text-align: <?= $module->params->get('rssrtl', 0) ? 'right' :'left'; ?>">

<table cellpadding="0" cellspacing="0">	
    <? if (!is_null( $feed->get_title() ) && $module->params->get('rsstitle', 1)) : ?>
	<tr>
		<td>
			<strong>
				<a href="<?= str_replace( '&', '&amp;', $feed->get_link()); ?>">
					<?= $feed->get_link(); ?>
				</a>
			</strong>
		</td>
	</tr>
	<? endif; ?>

	<? if ($module->params->get('rssdesc', 1)) : ?>
	<tr>
		<td>
			<?= $feed->get_description(); ?>
		</td>
	</tr>
	<? endif; ?>
			
	<? if ($feed->get_image_url() && $module->params->get('rssimage', 1)) : ?>
	<tr>
		<td align="center">
			<img src="<?= $feed->get_image_url(); ?>" alt="<?= $feed->get_image_title(); ?>"/>
		</td>
	</tr>
	<? endif; ?>

	<tr>
		<td>
			<ul class="newsfeed">
			<? foreach(array_slice($feed->get_items(), 0, $module->params->get('rssitems', 5)) as $item) : ?>
				<li>
				<? if ( !is_null( $item->get_link() ) ) : ?>
					<a href="<?= $item->get_link(); ?>" target="_child">
					    <?= $item->get_title(); ?>
					</a>
				<? endif; ?>

				<? if ($module->params->get('rssitemdesc', 1)) : 
					
					$text = html_entity_decode($item->get_description());
					$text = str_replace('&apos;', "'", $text);

					// Word limit
					if ($module->params->def('word_count', 0)) 
					{
						$texts = explode(' ', $text);
						$count = count($texts);
						if ($count > $module->params->def('word_count', 0)) 
						{
							$text = '';
							for ($i = 0; $i < $words; $i ++) {
								$text .= ' '.$texts[$i];
							}
								
							$text .= '...';
						}
					}
				    ?>
					<div style="text-align: <?= $module->params->get('rssrtl', 0) ? 'right': 'left'; ?> ! important">
					    <?= $text; ?>
					</div>
				<? endif; ?>
				</li>
			<? endforeach; ?>
			</ul>
		</td>
	</tr>
</table>
</div>