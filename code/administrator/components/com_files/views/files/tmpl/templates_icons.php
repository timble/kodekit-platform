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

<style src="media://com_files/css/files-layout-icons.css" />

<textarea style="display: none" id="file_preview">
    <div class="files-preview">
        <div class="preview extension-[%=metadata.extension%]">
            <img src="media://com_files/images/document-64.png" width="64" height="64" alt="[%=name%]" border="0" />
        </div>
        <div class="details">
            <a href="[%=baseurl+'/'+filepath%]" target="_blank"><?= @text('View'); ?></a>
            <h3 class="name" title="[%=name%]">[%=name%]</h3>
        </div>
        <!--<h4 class="preview-section">general</h4>-->
    	<ul>
    		<li>
    		    <span class="label">size</span>
    			[%=size.humanize()%]
    		</li>
    		<!--<li>
    		    <span class="label">where</span>
    			[%path%]
    		</li>-->
    		<li>
    		    <span class="label">modified</span>
    			[%=getModifiedDate(true)%]
    		</li>
    	</ul>
    </div>
</textarea>

<textarea style="display: none" id="icons_container">
<div>

</div>
</textarea>

<textarea style="display: none" id="icons_folder">
<div class="files-node-shadow">
    <div class="imgOutline files-node files-folder">
    	<div class="imgTotal files-node-thumbnail" style="width:[%= icon_size%]px; height: [%= icon_size*0.75%]px">
    			<a href="#" class="navigate"></a>
    	</div>
    	<div class="files-icons-controls">
    	<div class="controls" style="display:none">
    		<input type="checkbox" class="files-select" value="" />
    	</div>
    	<div class="ellipsis" style="width:[%= icon_size%]px" title="[%=name%]">
    		[%=name%]
    	</div>
    	</div>
    </div>
</div>
</textarea>

<textarea style="display: none" id="icons_file">
<div class="files-node-shadow">
    <div class="imgOutline files-node files-file">
    	<div class="imgTotal files-node-thumbnail" style="width:[%= icon_size%]px; height: [%= icon_size*0.75%]px">
    	 	<a class="navigate extension-label" href="#"
    	 		data-filetype="[%=filetype%]"
    	 		data-extension="[%=metadata.extension%]"></a>
    	</div>
    	<div class="files-icons-controls">
    	<div class="controls" style="display:none">
    		<input type="checkbox" class="files-select" value="" />
    	</div>
    	<div class="ellipsis" style="width:[%= icon_size%]px" title="[%=name%]">
    		[%=name%]
    	</div>
    	</div>
    </div>
</div>
</textarea>

<textarea style="display: none" id="icons_image">
<div class="files-node-shadow">
    <div class="imgOutline [%= thumbnail ? 'thumbnails' : 'nothumbnails' %] files-node files-image [%= thumbnail ? (client_cache ? 'load' : 'loading') : '' %]">
    	<div class="imgTotal files-node-thumbnail" style="width:[%= icon_size%]px; height: [%= icon_size*0.75%]px">
    		<a class=" navigate" href="#" title="[%=name%]"
    	 		data-filetype="[%=filetype%]"
    	 		data-extension="[%=metadata.extension%]">
    		[% if (thumbnail) { %]
    		    <div class="spinner"></div>
    			<img src="[%= client_cache || Files.blank_image %]" alt="[%=name%]" border="0" class="image-thumbnail [%= client_cache ? 'loaded' : '' %]" style="max-width: [%=metadata.image.width%]px" />
    		[% } %]
    		</a>
    	</div>
    	<div class="files-icons-controls">
    	<div class="controls" style="display:none">
    		<input type="checkbox" class="files-select" value="" />
    	</div>
    	<div class="ellipsis" style="width:[%= icon_size%]px" title="[%=name%]">
    		[%=name%]
    	</div>
    	</div>
    </div>
</div>
</textarea>