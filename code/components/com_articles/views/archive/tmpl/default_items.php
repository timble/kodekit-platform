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

<ul id="archive-list" style="list-style: none;">
<? foreach ($this->items as $item) : ?>
	<li class="row<?= ($item->odd +1 ); ?>">
		<h4 class="contentheading">
			<a href="<?= JRoute::_(ContentHelperRoute::getArticleRoute($item->slug)); ?>">
				<?= @escape($item->title); ?></a>
		</h4>

		<? if (($this->params->get('show_section') && $item->sectionid) || ($this->params->get('show_category') && $item->catid)) : ?>
			<div>
			<? if ($this->params->get('show_section') && $item->sectionid && isset($item->section)) : ?>
				<span>
				<? if ($this->params->get('link_section')) : ?>
					<a href="<?= JRoute::_(ContentHelperRoute::getSectionRoute($item->sectionid)) ?>">
				<? endif; ?>

				<?= @escape($item->section); ?>

				<? if ($this->params->get('link_section')) : ?>
					</a>
				<? endif; ?>

				<? if ($this->params->get('show_category')) : ?>
					<?= ' - '?>
				<? endif; ?>
				</span>
			<? endif; ?>
			<? if ($this->params->get('show_category') && $item->catid) : ?>
				<span>
				<? if ($this->params->get('link_category')) : ?>
					<a href="<?= JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug, $item->sectionid)) ?>">
				<? endif; ?>
				<?= @escape($item->category); ?>
				<? if ($this->params->get('link_category')) : ?>
					</a>
				<? endif; ?>
				</span>
			<? endif; ?>
			</div>
		<? endif; ?>

		<h5 class="metadata">
		<? if ($this->params->get('show_create_date')) : ?>
			<span class="created-date">
				<?= @text('Created') .': '.  JHTML::_( 'date', $item->created, JText::_('DATE_FORMAT_LC2')) ?>
			</span>
			<? endif; ?>
			<? if ($this->params->get('show_author')) : ?>
			<span class="author">
				<?=  @text('Author').': '; echo @escape($item->created_by_alias) ? @escape($item->created_by_alias) : @escape($item->author); ?>
			</span>
		<?php endif; ?>
		</h5>
		<div class="intro">
			<?= substr(strip_tags($item->introtext), 0, 255);  ?>...
		</div>
	</li>
<?php endforeach; ?>
</ul>
<div id="navigation">
	<span><?= $this->pagination->getPagesLinks(); ?></span>
	<span><?= $this->pagination->getPagesCounter(); ?></span>
</div>
