<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<table id="revisions-list">
	<thead>
      	<tr>
          	<th width="80"><?= @text('Revision') ?></th>
           	<th><?= @text('Last Edited') ?></th>
      	</tr>
   	</thead>

  	<tbody>
  	<? foreach ($revisions as $revision): ?>
      	<tr>
          	<td><?= @text('Revision') ?> <?= $revision->revision ?></td>
           	<td><?= $revision->created_on ?> <?= @text('by') ?> <?= $revision->user_name ?></td>
        </tr>
    <? endforeach; ?>
  	</tbody>
</table>