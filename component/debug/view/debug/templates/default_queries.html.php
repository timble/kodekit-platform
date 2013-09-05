<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<style src="assets://debug/highlighter/prettify.css" />
<script src="assets://debug/highlighter/prettify.js" />
<script src="assets://debug/highlighter/lang-sql.js" />
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