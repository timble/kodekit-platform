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

<textarea style="display: none" id="details_container">
<div class="manager">
	<table width="100%" cellspacing="0">
		<thead>
			<tr>
				<th><?= @text('Preview'); ?></th>
				<th><?= @text('Name'); ?></th>
				<th><?= @text('Dimensions'); ?></th>
				<th><?= @text('Size'); ?></th>
				<th><?= @text('Delete'); ?></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
</textarea>

<textarea style="display: none" id="details_parent">
	<tr class="files-node">
		<td class="imgTotal">
			<a href="#" class="navigate">
				<img src="media://com_files/images/folderup_16.png" width="16" height="16" border="0" alt=".." />
			</a>
		</td>
		<td class="description">
			<a href="#" class="navigate">
				..
			</a>
		</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</textarea>

<textarea style="display: none" id="details_folder">
	<tr class="files-node files-folder">
		<td class="imgTotal">
			<a href="#" class="navigate">
				<img src="media://com_files/images/folder.png" width="16" height="16" border="0" />
			</a>
		</td>
		<td class="description">
			<a href="#" class="navigate">
				[%=name%]
			</a>
		</td>
		<td></td>
		<td></td>
		<td>
			<input type="checkbox" class="files-select" value="[%=path%]" />
		</td>
	</tr>
</textarea>

<textarea style="display: none" id="details_file">
	<tr class="files-node files-file">
		<td class="imgTotal">
			<a class="navigate" href="#">
				<img src="[%=Files.sitebase%]/[%=icons['16']%]" width="16" height="16" border="0" />
			</a>
		</td>
		<td class="description">
			<a href="#" class="navigate">
				[%=name%]
			</a>
		</td>
		<td></td>
		<td>
			[%=new Files.Filesize(size).humanize()%]
		</td>
		<td>
			<input type="checkbox" class="files-select" value="[%=path%]" />
		</td>
	</tr>
</textarea>

<textarea style="display: none" id="details_image">
	<tr class="files-node files-image">
		<td class="imgTotal">
			<a class="navigate" href="#">
				<img src="[%=Files.sitebase%]/[%=icons['16']%]" width="16" height="16" border="0" />
			</a>
		</td>
		<td class="description">
			<a href="#" class="navigate">
				[%=name%]
			</a>
		</td>
		<td>
			[%=width%] x [%=height%]
		</td>
		<td>
			[%=new Files.Filesize(size).humanize()%]
		</td>
		<td>
			<input type="checkbox" class="files-select" value="[%=path%]" />
		</td>
	</tr>
</textarea>