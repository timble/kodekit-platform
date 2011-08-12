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

<textarea style="display: none" id="compact_details_image">
[% var ratio= 150 / (width > height ? width : height);
var path = (Files.sitebase ? '/' : '')+ (baseurl ? baseurl+'/'+path : path);
%]
<ul>
	<li class="info">
		[%=width%] x  [%=height%] | [%=new Files.Filesize(size).humanize()%]
	</li>
	<li class="preview">
		<img src="[%=path%]" width="[%=Math.min(ratio*width, width)%]"
			height="[%=Math.min(ratio*height, height)%]" alt="[%=name%]" border="0" />
	</li>
</ul>
</textarea>

<textarea style="display: none" id="compact_details_file">
<ul>
	<li class="info">
		[%=name%] | [%=new Files.Filesize(size).humanize()%]
	</li>
	<li class="preview">
		<img src="[%=(Files.sitebase ? '/'+Files.sitebase+'/' : '')+icons['32']%]" width="32" height="32" alt="[%=name%]" border="0" />
	</li>
</ul>
</textarea>

<textarea style="display: none" id="compact_container">
<ul>

</ul>
</textarea>

<textarea style="display: none"  id="compact_parent">
<li class="files-node">
	<a class="navigate" href="#">
		..
	</a>
</li>
</textarea>

<textarea style="display: none"  id="compact_image">
<li class="files-node files-image">
	<a class="navigate" href="#">
		[%= name %]
	</a>
</li>
</textarea>

<textarea style="display: none"  id="compact_file">
<li class="files-node files-file">
	<a class="navigate" href="#">
		[%= name %]
	</a>
</li>
</textarea>