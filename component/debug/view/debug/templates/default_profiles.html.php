<?
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-debugger for the canonical source repository
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
