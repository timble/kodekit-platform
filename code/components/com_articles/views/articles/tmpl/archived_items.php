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
<? $i = 0 ?>
<? foreach($articles as $article) : ?>
	<? $article_slug  = $article->id.($article->slug ? ':'.$article->slug : '') ?>
	<? $category_slug = $article->category_id.($article->category_slug ? ':'.$article->category_slug : '') ?>

	<li class="row<?= $i ?>">
		<h4 class="contentheading">

			<a href="<?= @route(ContentHelperRoute::getArticleRoute($article_slug)) ?>">
				<?= @escape($article->title) ?></a>
		</h4>

		<? if(($parameters->get('show_section') && $article->section_id) || ($parameters->get('show_category') && $article->category_id)) : ?>
			<div>
			<? if($parameters->get('show_section') && $article->section_id) : ?>
				<span>
				<? if($parameters->get('link_section')) : ?>
					<a href="<?= @route(ContentHelperRoute::getSectionRoute($article->section_id)) ?>">
						<?= @escape($article->section_title) ?>
					</a>
				<? else : ?>
				    <?= @escape($article->section_title) ?>
				<? endif ?>

				<? if($parameters->get('show_category')) : ?>
					<?= ' - ' ?>
				<? endif ?>
				</span>
			<? endif ?>

			<? if($parameters->get('show_category') && $article->category_id) : ?>
				<span>
				<? if($parameters->get('link_category')) : ?>
					<a href="<?= @route(ContentHelperRoute::getCategoryRoute($category_slug, $article->section_id)) ?>">
						<?= @escape($article->category_title) ?>
					</a>
				<? else : ?>
					<?= @escape($article->category_title) ?>
				<? endif ?>
				</span>
			<? endif ?>
			</div>
		<? endif ?>

		<h5 class="metadata">
		<? if($parameters->get('show_create_date')) : ?>
			<span class="created-date">
				<?= @text('Created') .': '.  @helper('date.format', array('date' => $article->created_on, 'format' => @text('DATE_FORMAT_LC2'))) ?>
			</span>
			<? endif ?>
			<? if($parameters->get('show_author')) : ?>
			<span class="author">
				<?= @text('Author').': '.@escape($article->author) ?>
			</span>
		<? endif ?>
		</h5>
		<div class="intro">
			<? if(strlen($text = strip_tags($article->introtext)) > 255) : ?>
				<?= substr($text, 0, strrpos(substr($text, 0, 255), ' ')).'...' ?>
			<? else : ?>
				<?= $text ?>
			<? endif ?>
		</div>
	</li>
	<? $i = 1 - $i ?>
<? endforeach ?>
</ul>
<div id="navigation">
	<span><?= @helper('paginator.pagination', array('total' => $total)) ?></span>
	<span><?//= $this->pagination->getPagesCounter() ?></span>
</div>