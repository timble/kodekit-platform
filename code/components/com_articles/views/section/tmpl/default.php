<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');
?>


<? if ($params->get('show_page_title')): ?>
<h1 class="page-header"><?php echo @escape($params->get('page_title')); ?></h1>
<? endif; ?>

<? if ($params->get('show_description_image') && $category->image): ?>
<img src="<? echo @service('koowa:http.url',
    array('url' => $files_params->get('image_path') . '/' . $category->image));?>"
     align="<?php echo $category->image_position;?>" hspace="6" alt=""/>
<? endif; ?>

<? if ($params->get('show_description') && $category->description): ?>
<? echo @escape($category->description); ?>
<? endif; ?>

<? $view = $this->getView()->setCategories(); ?>

<? foreach ($view->categories->list as $category): ?>
<? echo @template('category', array('category' => $category)); ?>
<? endforeach; ?>