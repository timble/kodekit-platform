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
				<img src="media://com_files/images/folder.png" width="80" height="80" border="0" />
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
		 	<a class="navigate" href="#" style="display: block; width: 100%; height: 100%">
				<img src="[%=Files.sitebase ? Files.sitebase : ''%]/[%=icons['32']%]" border="0" />
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
			<a class="img-preview navigate" href="#" title="[%=name%]" style="display: block; width: 100%; height: 100%">
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