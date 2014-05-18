<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<table class="adminlist">
	<thead>
    	<tr>
    		<th class="-koowa-sortable"><?= translate('Identifier') ?></th>
    		<th class="-koowa-sortable"><?= translate('Event'); ?></th>
    		<th class="-koowa-sortable"><?= translate('Time'); ?></th>
    		<th class="-koowa-sortable"><?= translate('Memory'); ?></th>
    	</tr>
  	</thead>
  	<tbody>
  		<? foreach ( $events as $event ) : ?>
  		<tr>  
			<td class="-koowa-sortable"><?= $event['target'] ?></td>
			<td class="-koowa-sortable"><?= $event['target'] ?></td>
            <td class="-koowa-sortable"><?= $event['message'] ?></td>
            <td class="-koowa-sortable" data-comparable="<?= $event['time'] ?>"><?= sprintf('%.3f', $event['time']).' seconds' ?></td>
            <td class="-koowa-sortable"><?= $event['memory'] ?></td>
        </tr>
         <? endforeach; ?>
  	</tbody>
</table>
