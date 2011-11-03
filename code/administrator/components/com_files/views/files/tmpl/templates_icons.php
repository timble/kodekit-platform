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

<style src="media://com_files/css/files-layout-icons.css" />

<textarea style="display: none" id="file_preview">
	<ul>
		<li>
			<a href="[%=Files.baseurl+'/'+path%]" target="_blank"><?= @text('View'); ?></a>
		</li>
		<li class="preview extension-[%=extension%]">
			<img src="media://com_files/images/document-64.png" width="64" height="64" alt="[%=name%]" border="0" />
		</li>
		<li class="info">
			[%=name%]
		</li>
		<li>
			[%=new Files.Filesize(size).humanize()%]
		</li>
		<li>
			[%=getModifiedDate(true)%]
		</li>
	</ul>
</textarea>

<textarea style="display: none" id="icons_container">
<div>

</div>
</textarea>

<textarea style="display: none" id="icons_controls">
<div class="controls">
	<input type="checkbox" class="files-select" value="[%=path%]" />
</div>
<div class="imginfoBorder ellipsis">
	[%=name%]
</div>

</textarea>

<textarea style="display: none" id="icons_folder">
<div class="imgOutline files-node files-folder">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
			<a href="#" class="navigate">
				<img src="media://com_files/images/folder-64.png" width="64" height="64" border="0" />
			</a>
		</div>
	</div>
	[%= new EJS({element: 'icons_controls'}).render(this) %]
</div>
</textarea>

<textarea style="display: none" id="icons_file">
<div class="imgOutline files-node files-file">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
		 	<a class="navigate extension-label" href="#" 
		 		data-filetype="[%=Files.getFileType(extension)%]" 
		 		data-extension="[%=extension%]" 
		 		style="display: block; width: 100%; height: 100%;">
				<img src="media://com_files/images/document-64.png" border="0" width="64" />
			</a>
		</div>
		[%= new EJS({element: 'icons_controls'}).render(this) %]
	</div>

</div>
</textarea>

<textarea style="display: none" id="icons_image">
<div class="imgOutline files-node files-image">
	<div class="imgTotal">
		<div align="center" class="imgBorder">
			<a class="img-preview navigate" href="#" title="[%=name%]" style="display: block; width: 100%; height: 100%"
		 		data-filetype="[%=Files.getFileType(extension)%]" 
		 		data-extension="[%=extension%]">
				<div class="image">
					<img src="media://com_files/images/spinner.gif"
						alt="[%=name%]" border="0"
						class="image-thumbnail" />
				</div>
			</a>
		</div>
	</div>
	[%= new EJS({element: 'icons_controls'}).render(this) %]
</div>
</textarea>