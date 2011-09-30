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

<style src="media://com_files/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css" />

<script src="media://com_files/plupload/plupload.js" />
<script src="media://com_files/plupload/plupload.html5.js" />
<script src="media://com_files/plupload/plupload.html4.js" />
<script src="media://com_files/plupload/plupload.flash.js" />

<script src="media://com_files/plupload/jquery-1.6.4.min.js" />
<script src="media://com_files/plupload/jquery.plupload.queue/jquery.plupload.queue.js" />

<script>
jQuery.noConflict();

window.addEvent('domready', function() { 
	var element = jQuery('#files-upload-multi');
	 
	element.pluploadQueue({
		runtimes: 'html5,flash,html4',
		browse_button: 'pickfiles',
		dragdrop: true,
		rename: true,
		url: Files.getUrl({view: 'file'}),
		flash_swf_url: 'media://com_files/plupload/plupload.flash.swf',
		urlstream_upload: true, // required for flash
		multipart_params: {
			_token: Files.token
		},
		headers: {
			'X-Requested-With': 'xmlhttprequest'
		}
	});
	
	var uploader = element.pluploadQueue();
	
	document.id('files-upload-multi_filelist').setStyle('display', 'none');
	uploader.bind('QueueChanged', function(uploader) {
		var style = uploader.files.length == 0 ? 'none' : 'block';
		document.id('files-upload-multi_filelist').setStyle('display', style);
	});

	uploader.bind('BeforeUpload', function(uploader) {
		// set directory in the request
		uploader.settings.url = Files.getUrl({view: 'file'});
		uploader.settings.multipart_params.parent = Files.app.getPath();
	});
	
	uploader.bind('UploadComplete', function(uploader) {
		jQuery('li.plupload_delete a,div.plupload_buttons', element).show();
	});

	uploader.bind('FileUploaded', function(uploader, file, response) {
		var json = JSON.decode(response.response, true) || {};
		if (json.status) {
			var item = json.item;
			var cls = Files[item.type.capitalize()];
			var row = new cls(item);

			Files.app.grid.insert(row);
			Files.app.fireEvent('uploadFile', [row]);
		} else {
			var error = json.error ? json.error : 'Unknown error';
			alert(error);
		}
	});

	$$('.plupload_clear').addEvent('click', function(e) {
		e.stop();

		// need to work on a clone, otherwise iterator gets confused after elements are removed
		var files = uploader.files.slice(0);
		files.each(function(file) {
			uploader.removeFile(file);
		});
	});

	Files.app.uploader = uploader;
});

/**
 * Switcher between uploaders
 */
window.addEvent('domready', function() {
	var toggleForm = function(type) {
		var el = document.id('files-uploader-'+type);
		var style = el.getStyle('display') == 'block' ? 'none' : 'block';

		$$('.upload-form').setStyle('display', 'none');

		if (style == 'block') {
			el.setStyle('display', style);
		}

		// Plupload needs to be refreshed if it was hidden
		if (type == 'computer') {
			jQuery('#files-upload-multi').pluploadQueue().refresh();
		}
		
	};
	
	$$('.upload-form-toggle').addEvent('click', function(e) {
		e.stop();
		toggleForm(this.get('data-type'));
	});
});

/**
 * Remote file form
 */
window.addEvent('domready', function() {
	var form = document.id('remoteForm');
	var request = new Request.JSON({
		url: Files.getUrl({view: 'file'}),
		data: form,
		onSuccess: function(json) {
			if (this.status == 201 && json.status) {
				var el = json.item;
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);
				Files.app.container.insert(row);
				Files.app.fireEvent('uploadFile', [row]);
				form.reset();
			} else {
				var error = json.error ? json.error : 'Unknown error';
				alert('An error occurred: ' + error);
			}
		},
		onFailure: function(xhr) {
			alert('An error occurred with status code: '+xhr.status);
		}
	});
	form.addEvent('submit', function(e) {
		e.stop();
		request.options.url = Files.getUrl({view: 'file'});
		request.send();
	});
});
</script>

<div id="files-upload" style="clear: both">
	<div id="files-upload-controls">
		<h3><?= @text('Upload') ?>:</h3>
		<ul class="upload-buttons">
			<li><button class="upload-form-toggle" data-type="computer"><?= @text('Computer'); ?></button></li>
			<li><button class="upload-form-toggle" data-type="web"><?= @text('Web'); ?></button></li>
		</ul>
		<p id="upload-max">
			<?= @text('Max'); ?>
			<span id="upload-max-size"></span>
		</p>
	</div>
	<div class="clr"></div>
	<div id="files-uploader-computer" class="upload-form" style="display: none">
		
		<div style="clear: both"></div>
		
		<div id="files-upload-multi"></div>

	</div>
	<div class="clr"></div>
	<div id="files-uploader-web" class="upload-form" style="display: none">
		<form action="" method="post" name="remoteForm" id="remoteForm" >
			<fieldset class="actions adminform">
				<table class="admintable">
					<tr>
						<td width="100" align="right" class="key">
							<label for="remote-url"><?= @text('Remote URL'); ?></label>
						</td>
						<td>
							<input type="text" id="remote-url" name="file" size="50" />
						</td>
					</tr>

					<tr>
						<td width="100" align="right" class="key">
							<label for="remote-name"><?= @text('File name (optional)'); ?></label>
						</td>
						<td>
							<input type="text" id="remote-name" name="path" />
						</td>
					</tr>

					<tr>
						<td width="100" align="right" class="key">
						</td>
						<td>
							<input type="submit" value="<?= @text('Transfer File'); ?>"/>
						</td>
					</tr>
					<tr>
						<input type="hidden" class="file-basepath" name="parent" />
						<input type="hidden" name="action" value="save" />
					</tr>
				</table>
			</fieldset>
		</form>
	</div>
</div>
