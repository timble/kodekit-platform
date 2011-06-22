<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<ul <?= isset($id) && $id === false ? '' : 'id="folder-tree-html"'; ?>>
<? foreach($folders as $folder): ?>
	<li>
		<a href="#!/<?= $folder->path; ?>" title="<?= $folder->path; ?>">
			<!--id:<?= $folder->path; ?>-->
			<?= $folder->name; ?>
		</a>
	<? if (count($folder->children)): ?>
		<?= @template('folders', array('folders' => $folder->children, 'id' => false)); ?>
	<? endif; ?>
	</li>
<? endforeach; ?>
</ul>