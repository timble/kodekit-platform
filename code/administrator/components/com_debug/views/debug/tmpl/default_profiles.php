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

<table>
	<thead>
    	<tr>
    		<th><?= @text('Identifier') ?></th>
    		<th><?= @text('Event'); ?></th>
    		<th><?= @text('Time'); ?></th>
    		<th><?= @text('Memory'); ?></th>
    	</tr>
  	</thead>
  	<tbody>
  		<? foreach ( $events as $event ) : ?>
  		<tr>  
			<td><?= $event['caller'] ?></div>
            <td><?= $event['message'] ?></td>
            <td><?= sprintf('%.3f', $event['time']).' seconds' ?></td>
            <td><?= $event['memory'] ?></td>
        </tr>
         <? endforeach; ?>
  	</tbody>
</table>
