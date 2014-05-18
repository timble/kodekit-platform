<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<script>
window.addEvent('domready', function() {
	document.id('files-canvas').addEvent('click:relay(input.-check-all)', function(e) {
		var value = e.target.checked,
			grid = Files.app.grid,
			nodes = grid.nodes;

		$each(nodes, function(node) {
			if (value && !node.checked) {
				grid.checkNode(node);
			} else if (!value && node.checked) {
				grid.checkNode(node);
			}

		});

	});
})
</script>

<textarea style="display: none" id="details_container">
<div class="manager -koowa-grid">
	<table>
		<thead>
			<tr>
				<th width="1">
                    <input type="checkbox" class="-check-all" />
				</th>
				<th width="32"></th>
				<th><?= translate('Name'); ?></th>
				<th><?= translate('Size'); ?></th>
				<th><?= translate('Last Modified'); ?></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
</textarea>

<textarea style="display: none" id="details_folder">
	<tr class="files-node files-folder">
		<td>
			<input type="checkbox" class="files-select" value="" />
		</td>
		<td>
		    <img src="assets://files/images/folder-64.png" height="24px" alt="[%=name%]" border="0" />
		</td>
		<td colspan="3">
			<a href="#" class="navigate">
				[%=name%]
			</a>
		</td>
	</tr>
</textarea>

<textarea style="display: none" id="details_file">
	<tr class="files-node files-file">
		<td>
			<input type="checkbox" class="files-select" value="" />
		</td>
		<td>
		    <img src="assets://files/images/document-64.png" height="24px" alt="[%=name%]" border="0" />
		</td>
		<td>
			<a href="#" class="navigate">
				[%=name%]
			</a>
		</td>
		<td align="center">
			[%=size.humanize()%]
		</td>
		<td align="center">
			[%=getModifiedDate(true)%]
		</td>
	</tr>
</textarea>

<textarea style="display: none" id="details_image">
	<tr class="files-node files-image">
		<td>
			<input type="checkbox" class="files-select" value="" />
		</td>
		<td>
			[% if (typeof thumbnail === 'string') { %]
    			<img src="[%= client_cache || Files.blank_image %]" alt="[%=name%]" border="0" class="image-thumbnail [%= client_cache ? 'loaded' : '' %]" height="24px" />
    		[% } else { %]
		   		<img src="assets://files/images/image-16.png" height="24px" alt="[%=name%]" border="0" />
		    [% } %]
		</td>
		<td>
			<a href="#" class="navigate">
				[%=name%]
			</a>
		</td>
		<td align="center">
			[%=size.humanize()%]
			([%=metadata.image.width%] x [%=metadata.image.height%])
		</td>
		<td align="center">
			[%=getModifiedDate(true)%]
		</td>
	</tr>
</textarea>