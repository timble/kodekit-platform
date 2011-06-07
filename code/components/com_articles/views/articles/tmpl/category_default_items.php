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

<form action="<?= @route() ?>" method="get">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <? if($parameters->get('filter') || $parameters->get('show_pagination_limit')) : ?>
        <tr>
        	<td colspan="5">
        		<table>
            		<tr>
            		<? if($parameters->get('filter')) : ?>
            			<td align="left" width="60%" nowrap="nowrap">
            				<?= @text($parameters->get('filter_type').' Filter').'&nbsp;' ?>
            				<?= @helper('grid.search') ?>
            			</td>
            		<? endif ?>

            		<? if($parameters->get('show_pagination_limit')) : ?>
            			<td align="right" width="40%" nowrap="nowrap">
            			<?
            				echo '&nbsp;&nbsp;&nbsp;'.@text('Display Num').'&nbsp;';
            				//echo $this->pagination->getLimitBox();
            			?>
            			</td>
            		<? endif ?>
            		</tr>
        		</table>
        	</td>
        </tr>
    <? endif ?>

    <? if($parameters->get('show_headings')) : ?>
        <tr>
    	<? if($parameters->get('show_title')) : ?>
         	<td class="sectiontableheader<?= @escape($parameters->get('pageclass_sfx')) ?>">
         		<?= @helper('grid.sort', array('title' => 'Title', 'column' => 'title')) ?>
        	</td>
    	<? endif ?>
    	<? if($parameters->get('show_date')) : ?>
        	<td class="sectiontableheader<?= @escape($parameters->get('pageclass_sfx')) ?>" width="25%">
        		<?= @helper('grid.sort', array('title' => 'Date', 'column' => 'created_on')) ?>
        	</td>
    	<? endif ?>
    	<? if($parameters->get('show_author')) : ?>
        	<td class="sectiontableheader<?= @escape($parameters->get('pageclass_sfx')) ?>"  width="20%">
        		<?= @helper('grid.sort', array('title' => 'Author', 'column' => 'author')) ?>
        	</td>
    	<? endif ?>
        </tr>
    <? endif ?>

    <? $i = 0 ?>
    <? foreach($articles as $article) : ?>
        <tr class="sectiontableentry<?= ($i +1 ).@escape($parameters->get('pageclass_sfx')) ?>" >
        <? if($parameters->get('show_title')) : ?>
        	<? if($article->access <= $user->get('aid', 0)) : ?>
            	<td>
            		<? $article_slug  = $article->id.($article->slug ? ':'.$article->slug : '') ?>
    				<? $category_slug = $article->category_id.($article->category_slug ? ':'.$article->category_slug : '') ?>

            		<a href="<?= @route(ContentHelperRoute::getArticleRoute($article_slug, $category_catslug, $article->section_id)) ?>">
            			<?= @escape($article->title) ?>
            		</a>
            	</td>
        	<? else : ?>
        	<td>
        		<?= @escape($article->title).' : ' ?>
        		<a href="<?= @route('option=com_users&view=login') ?>">
        			<?= @text('Register to read more...') ?>
        	    </a>
        	</td>
            <? endif ?>
        <? endif ?>
        <? if($parameters->get('show_date')) : ?>
        	<td>
        		<?= @helper('date.format', array('date' => $article->created_on, 'format' => $parameters->get('date_format'))) ?>
        	</td>
    	<? endif ?>
    	<? if($parameters->get('show_author')) : ?>
        	<td >
        		<?= @escape($article->author) ?>
        	</td>
        <? endif ?>
        </tr>
        <? $i = 1 - $i ?>
    <? endforeach ?>

    <? if($parameters->get('show_pagination')) : ?>
        <tr>
        	<td align="center" colspan="4" class="sectiontablefooter<?= @escape($parameters->get('pageclass_sfx')) ?>">
        		<?//= $this->pagination->getPagesLinks() ?>
        	</td>
        </tr>
        <tr>
        	<td colspan="5" align="right">
        		<?//= $this->pagination->getPagesCounter() ?>
        	</td>
        </tr>
    <? endif ?>
    </table>
</form>