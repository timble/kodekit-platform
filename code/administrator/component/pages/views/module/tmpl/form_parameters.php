<?
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<? $group = isset($group) ? $group : '_default' ?>
<? if($html = $params->render('params', $group)) : ?>
	<?= $html ?>
<? else : ?>
	<?= @text('There are no parameters for this item') ?>
<? endif ?>