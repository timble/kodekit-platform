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
	<table width="100%" cellspacing="0" class="adminlist">
		<thead>
			<tr>
				<th width="10"></th>
				<th><?= @text('Name'); ?></th>
				<th><?= @text('Dimensions'); ?></th>
				<th><?= @text('Size'); ?></th>
				<th><?= @text('Last Modified'); ?></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
		<tfoot>
			<tr>
				<td id="files-paginator-container-details" colspan="16"></td>
			</tr>
		</tfoot>
	</table>
</div>
</textarea>

<textarea style="display: none" id="details_folder">
	<tr class="files-node files-folder">
		<td>
			<input type="checkbox" class="files-select" value="[%=path%]" />
		</td>
		<td class="description">
			<a href="#" class="navigate">
				<img src="media://com_files/images/folder.png" width="16" height="16" border="0" />
				[%=name%]
			</a>
		</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</textarea>

<textarea style="display: none" id="details_file">
	[%var date = new Date();date.setTime(modified_date*1000);%]
	<tr class="files-node files-file">
		<td>
			<input type="checkbox" class="files-select" value="[%=path%]" />
		</td>
		<td class="description">
			<a href="#" class="navigate">
				<img src="[%=Files.sitebase%]/[%=icons['16']%]" width="16" height="16" border="0" />
				[%=name%]
			</a>
		</td>
		<td></td>
		<td align="center">
			[%=new Files.Filesize(size).humanize()%]
		</td>
		<td align="center">
			[%=date.toUTCString()%]
		</td>
	</tr>
</textarea>

<textarea style="display: none" id="details_image">
	[%var date = new Date();date.setTime(modified_date*1000);%]
	<tr class="files-node files-image">
		<td>
			<input type="checkbox" class="files-select" value="[%=path%]" />
		</td>
		<td class="description">
			<a href="#" class="navigate">
				<img src="[%=Files.sitebase%]/[%=icons['16']%]" width="16" height="16" border="0" />
				[%=name%]
			</a>
		</td>
		<td align="center">
			[%=width%] x [%=height%]
		</td>
		<td align="center">
			[%=new Files.Filesize(size).humanize()%]
		</td>
		<td align="center">
			[%=date.toUTCString()%]
		</td>
	</tr>
</textarea>