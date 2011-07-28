<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<style src="media://com_debug/highlighter/prettify.css" />
<script src="media://com_debug/highlighter/prettify.js" />
<script src="media://com_debug/highlighter/lang-sql.js" />
<script>
window.addEvent('domready', prettyPrint);
</script>

<table>
	<thead>
    	<tr>
    		<th><?= @text('#') ?></th>
    		<th><?= @text('Type') ?></th>
    		<th><?= @text('Time'); ?></th>
    		<th><?= @text('Query'); ?></th>
    	</tr>
  	</thead>
  	<tbody>
  		<?foreach ($queries as $key => $query) : ?>
  		<tr>  
  			<td><?= $key + 1; ?></td>
			<td><?= $query->operation; ?></td>
            <td><?= sprintf('%.3f', $query->time*1000).' msec' ?></td>
            <td><pre class="prettyprint lang-sql"><?= preg_replace('/(FROM|LEFT|INNER|OUTER|WHERE|SET|VALUES|ORDER|GROUP|HAVING|LIMIT|ON|AND)/', "\n".'\\0', $query->query); ?></pre></td>
        </tr>
         <? endforeach; ?>
  	</tbody>
</table>