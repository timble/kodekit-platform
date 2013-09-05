<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<textarea style="display: none" id="compact_details_image">
[% var width = metadata.image.width,
	height = metadata.image.height,
	ratio= 150 / (width > height ? width : height); %]
<div>
    <img class="icon" src="" alt="[%=name%]" />
    <div>[%=width%] x [%=height%], [%=size.humanize()%]</div>
</div>
</textarea>

<textarea style="display: none" id="compact_details_file">
<div class="details">
<div style="text-align: center">
<img class="icon" src="assets://files/images/document-64.png" width="64" height="64" alt="[%=name%]" border="0" />
</div>
<table class="table table-condensed parameters">
	<tbody>
		<tr>
			<td class="detail-label"><?= translate('Name'); ?></td>
			<td>[%=name%]</td>
		</tr>
		<tr>
			<td class="detail-label"><?= translate('Size'); ?></td>
			<td>[%=size.humanize()%]</td>
		</tr>
	</tbody>
</table>
</div>
</textarea>

<textarea style="display: none" id="compact_container">
<ul class="navigation">

</ul>
</textarea>

<textarea style="display: none"  id="compact_folder">
<li class="files-node files-folder">
	<a class="navigate" href="#" title="[%= name %]">
		[%= name %]
	</a>
</li>
</textarea>

<textarea style="display: none"  id="compact_image">
<li class="files-node files-image">
	<a class="navigate" href="#" title="[%= name %]">
		[%= name %]
	</a>
</li>
</textarea>

<textarea style="display: none"  id="compact_file">
<li class="files-node files-file">
	<a class="navigate" href="#" title="[%= name %]">
		[%= name %]
	</a>
</li>
</textarea>