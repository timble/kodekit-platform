<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<textarea style="display: none" id="compact_details_image">
[% var width = metadata.image.width,
	height = metadata.image.height,
	ratio= 150 / (width > height ? width : height); %]
<ul>
	<li class="info">
		[%=width%] x  [%=height%] | [%=size.humanize()%]
	</li>
	<li class="preview">
		<img src="" width="[%=Math.min(ratio*width, width)%]"
			height="[%=Math.min(ratio*height, height)%]" alt="[%=name%]" border="0" />
	</li>
</ul>
</textarea>

<textarea style="display: none" id="compact_details_file">
<ul>
	<li class="info">
		[%=name%] | [%=size.humanize()%]
	</li>
	<li class="preview extension-[%=metadata.extension%]">
		<img src="media://com_files/images/document-64.png" width="32" height="32" alt="[%=name%]" border="0" />
	</li>
</ul>
</textarea>

<textarea style="display: none" id="compact_container">
<ul>

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