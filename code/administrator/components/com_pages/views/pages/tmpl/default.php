<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<style src="media://com_pages/css/pages-default.css" />

<?= @template('default_sidebar'); ?>

<form id="pages-form" action="<?= @route() ?>" method="get" class="-koowa-grid" >
	<?= @template('default_filter'); ?>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="5"></th>
				<th width="60%">
					<?= @helper('grid.sort', array('column' => 'title')); ?>
				</th>
				<th width="5">
					<?= @helper('grid.sort', array('column' => 'enabled' , 'title' => 'Published')); ?>
				</th>
				<th width="5">
					<?= @helper('grid.sort',  array('column' => 'ordering' , 'title' => 'Ordering')); ?>
				</th>
				 <th width="7%">
                    <?= @helper('grid.sort', array('column' => 'access')) ?>
                </th>
				<th>
					<?= @helper('grid.sort',  array('column' => 'component_id' , 'title' => 'Type')); ?>
				</th>
			</tr>
			<tr>
				<td align="center">
                    <?= @helper('grid.checkall') ?>
                </td>
                <td>
                    <?= @helper('grid.search') ?>
                </td>
                <td></td>
                <td></td>
                <td>
                    <?= @helper('listbox.access', array('attribs' => array('id' => 'pages-form-access'))) ?>
                </td>
                <td></td>
			</tr>
		</thead>
		<tfoot>
            <tr>
                <td colspan="6">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>
			
		<tbody>
		<? foreach ($it = new RecursiveIteratorIterator($pages, RecursiveIteratorIterator::SELF_FIRST) as $page) : ?>
			<tr class="sortable">
				<td align="center">
		            <?= @helper('grid.checkbox',array('row' => $page)); ?>
				</td>
				<td>
		        <?
			        $link = 'type[name]='.$page->type;

			        if ($page->type == 'component')
			        {
				        parse_str(parse_url($page->link, PHP_URL_QUERY), $url_query);

				        $link .= '&type[option]='.$url_query['option'];
				        $link .= '&type[view]='.$url_query['view'];
				        $link .= '&type[layout]='.(isset($url_query['layout']) ? $url_query['layout'] : 'default');
			        }

			        $link .= '&view=page&menu='.$state->menu.'&id='.$page->id;
		        ?>
					<a href="<?= @route($link) ?>">
			            <? if($it->getDepth()) : ?>
				            <?= str_repeat('.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $it->getDepth())?><sup>|_</sup>&nbsp;
			            <? endif ?>
						<strong><?= @escape($page->title)?></strong>
					</a>
		            <? if ( $page->home == 1 ) : ?>
						<img src="media://system/images/star.png" alt="<?= @text('Default'); ?>" />
		            <? endif; ?>
		
				</td>
				<td align="center">
		            <?= @helper('grid.enable', array('row' => $page)) ?>
				</td>
				<td align="center">
		            <?= @helper('grid.order', array('row'=> $page, 'total' => $total))?>
				</td>
				<td align="center">
                    <?= @helper('grid.access', array('row' => $page)) ?>
                </td>
				<td>
		            <?= $page->type_description; ?>
				</td>
			</tr>
            <? endforeach; ?>
		</tbody>
	</table>
</form>