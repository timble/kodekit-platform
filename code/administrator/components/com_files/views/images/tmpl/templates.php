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

<textarea style="display: none" id="image_details">
[% var ratio= 150 / (width > height ? width : height);%]
<ul>
	<li class="info">
		[%=width%] x  [%=height%] | [%=new Files.Filesize(size).humanize()%]
	</li>
	<li class="preview">
		<img src="/[%=baseurl%]/[%=path%]" width="[%=ratio*width%]"
			height="[%=ratio*height%]" alt="[%=name%]" border="0" />
	</li>
</ul>
</textarea>

<textarea style="display: none" id="image_container">
<ul>

</ul>
</textarea>

<textarea style="display: none"  id="image_parent">
<li class="files-node">
	<a class="navigate" href="#">
		..
	</a>
</li>
</textarea>

<textarea style="display: none"  id="image_image">
<li class="files-node files-image">
	<a class="navigate" href="#">
		[%= name %]
	</a>
</li>
</textarea>

<textarea style="display: none"  id="ercan">
<h1> [%= title %] </h1>
</textarea>