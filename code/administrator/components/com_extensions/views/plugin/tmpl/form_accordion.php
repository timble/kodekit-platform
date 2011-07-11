<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<? $group	= isset($group) ? $group : '_default' ?>

<?= @helper('accordion.startPanel', array(
		'title'		=> @text(isset($title) ? $title : lcfirst($group).' Parameters'),
		'attribs'	=> array(
				'id'	=> isset($id) ? $id : $group.'-page'
		)
)) ?>

<? if($html = $params->render('params', $group)) : ?>
	<?= $html ?>
<? else : ?>
	<div style="text-align: center; padding: 5px;">
		<? $name = $group != '_default' ? ' '.$group : '' ?>
		<?= @text('There are no'.$name.' parameters for this item') ?>
	</div>
<? endif ?>

<?= @helper('accordion.endPanel') ?>