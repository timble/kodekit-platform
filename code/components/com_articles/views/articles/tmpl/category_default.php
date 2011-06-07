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

<? if($parameters->get('show_page_title')) : ?>
    <div class="componentheading<? @escape($parameters->get('pageclass_sfx')) ?>">
    	<?= @escape($parameters->get('page_title')) ?>
    </div>
<? endif ?>

<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" class="contentpane<?= @escape($parameters->get('pageclass_sfx')) ?>">
    <tr>
    	<td valign="top" class="contentdescription<?= @escape($parameters->get('pageclass_sfx')) ?>" colspan="2">
        	<? if($category->image) : ?>
        		<img src="<?= KRequest::base().'/'.str_replace(JPATH_ROOT.DS, '', JPATH_IMAGES.'/stories/'.$category->image) ?>" align="<?= $category->image_position ?>" hspace="6" alt="<?= $category->image ?>" />
        	<? endif ?>
        	<?= $category->description ?>
        </td>
    </tr>
    <tr>
    	<td>
    		<?= (string) @template('category_default_items', array('articles' => $articles, 'parameters' => $parameters, 'user' => $user)) ?>
    	</td>
    </tr>
</table>