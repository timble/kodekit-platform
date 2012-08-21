<?
/**
 * @version		$Id: form.php 1294 2011-05-16 22:57:57Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<ul class="breadcrumb">

	<? for ($i = 0; $i < count($list); $i ++) : ?>
		<? // If not the last item in the breadcrumbs add the separator ?>
		<? if ($i < count($list) - 1) : ?>
			<? if(!empty($list[$i]->link)) : ?>
				<li><?= '<a href="'.$list[$i]->link.'" class="pathway">'.$list[$i]->name.'</a>'; ?></li>
			<? else : ?>
				<li><?= $list[$i]->name; ?></li>
			<? endif; ?>
			<span class="divider">/</span>
		<? elseif ($params->get('showLast', 1)) : // when $i == $count -1 and 'showLast' is true ?>
		    <li><?=  $list[$i]->name; ?></li>
		<? endif; ?>
	<? endfor; ?>
</ul>