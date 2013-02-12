<?
/**
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Versions
 * @copyright   Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
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