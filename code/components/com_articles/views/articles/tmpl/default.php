<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @author         Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');
?>
<? if ($params->get('show_page_title', 1)): ?>
<h1 class="page-header"><?php echo @escape($params->get('page_title')); ?></h1>
<? endif; ?>

<? echo @template('list'); ?>

<? echo (count($articles) == $total) ? '' : @helper('paginator.pagination', array(
    'total'      => $total,
    'show_limit' => false)); ?>

