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
<div class="files-icons-controls">
<div class="controls" style="display:none">
	<input type="checkbox" class="files-select" value="[%=path%]" />
</div>
<div class="ellipsis" style="width:[%= icon_size%]px">
	[%=name%]
</div>
</div>
</textarea>

<textarea style="display: none" id="icons_folder">
<div class="imgOutline files-node files-folder">
	<div class="imgTotal" style="width:[%= icon_size%]px; height: [%= icon_size*0.75%]px">
			<a href="#" class="navigate"></a>
	</div>
	[%= new EJS({element: 'icons_controls'}).render(this) %]
</div>
</textarea>

<textarea style="display: none" id="icons_file">
<div class="imgOutline files-node files-file">
	<div class="imgTotal" style="width:[%= icon_size%]px; height: [%= icon_size*0.75%]px">
	 	<a class="navigate extension-label" href="#" 
	 		data-filetype="[%=filetype%]" 
	 		data-extension="[%=extension%]"></a>
	</div>
	[%= new EJS({element: 'icons_controls'}).render(this) %]
</div>
</textarea>

<textarea style="display: none" id="icons_image">
<div class="imgOutline [%= Files.app.options.thumbnails ? 'thumbnails' : 'nothumbnails' %] files-node files-image">

	<div class="imgTotal" style="width:[%= icon_size%]px; height: [%= icon_size*0.75%]px">
		<a class=" navigate" href="#" title="[%=name%]"
	 		data-filetype="[%=filetype%]" 
	 		data-extension="[%=extension%]">
		[% if (Files.app.options.thumbnails) { %]
		    <div class="spinner"></div>
			<img src="media://com_files/images/blank.png" alt="[%=name%]" border="0" class="image-thumbnail" />
		[% } %]
		</a>
	</div>
	[%= new EJS({element: 'icons_controls'}).render(this) %]
</div>
</textarea>