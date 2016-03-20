<?
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-deugger for the canonical source repository
 */
?>

<ktml:style src="assets://debug/highlighter/prettify.css" />
<ktml:script src="assets://debug/highlighter/prettify.js" />
<ktml:script src="assets://debug/highlighter/lang-sql.js" />
<script>
window.addEvent('domready', prettyPrint);
</script>

<table class="adminlist">
	<thead>
    	<tr>
    		<th class="-koowa-sortable"><?= translate('#') ?></th>
    		<th class="-koowa-sortable"><?= translate('Type') ?></th>
    		<th class="-koowa-sortable"><?= translate('Time'); ?></th>
    		<th><?= translate('Query'); ?></th>
    	</tr>
  	</thead>
  	<tbody>
  		<?foreach ($queries as $key => $query) : ?>
  		<tr>
  			<td class="-koowa-sortable" align="right" width="10"><?= $key + 1; ?></td>
			<td class="-koowa-sortable"><?= $query->operation; ?></td>
            <td class="-koowa-sortable" style="white-space: nowrap" data-comparable="<?= $query->time*1000 ?>"><?= sprintf('%.3f', $query->time*1000).' msec' ?></td>
            <td><pre class="prettyprint lang-sql"><?= preg_replace('/(FROM|LEFT|INNER|OUTER|WHERE|SET|VALUES|ORDER|GROUP|HAVING|LIMIT|ON|AND)/', "\n".'\\0', $query->query); ?></pre></td>
        </tr>
         <? endforeach; ?>
  	</tbody>
</table>