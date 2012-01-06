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

	plupload.addI18n({'Add files': 'Select files from your computer'});

	element.pluploadQueue({
		runtimes: 'html5,flash,html4',
		browse_button: 'pickfiles',
		dragdrop: true,
		rename: true,
		url: '', // this is added on the go in BeforeUpload event
		flash_swf_url: 'media://com_files/plupload/plupload.flash.swf',
		urlstream_upload: true, // required for flash
		multipart_params: {
			action: 'add',
			_token: Files.token
		},
		headers: {
			'X-Requested-With': 'xmlhttprequest'
		}
	});

	var uploader = element.pluploadQueue(),
		//We only want to run this once
		exposePlupload = function(uploader) {
			document.id('files-upload').addClass('uploader-files-queued').removeClass('uploader-files-empty');
			if(document.id('files-upload-multi_browse')) {
				document.id('files-upload-multi_browse').set('text', 'Add files');
			}
			//Scrollfix
			if(document.id('files-upload').scrollIntoView) document.id('files-upload').scrollIntoView(true);
			uploader.unbind('QueueChanged', exposePlupload);
		};

	if(uploader.features.dragdrop) {
		document.id('files-upload').addClass('uploader-droppable');
	} else {
		document.id('files-upload').addClass('uploader-nodroppable');
	}

	uploader.bind('QueueChanged', exposePlupload);

	uploader.bind('BeforeUpload', function(uploader, file) {
		// set directory in the request
		uploader.settings.url = Files.app.createRoute({
			view: 'file',
			plupload: 1,
			folder: Files.app.getPath()
		});
	});

	uploader.bind('UploadComplete', function(uploader) {
		jQuery('li.plupload_delete a,div.plupload_buttons', element).show();
	});

	// Keeps track of failed uploads and error messages so we can later display them in the queue
	var failed = {};
	uploader.bind('FileUploaded', function(uploader, file, response) {
		var json = JSON.decode(response.response, true) || {};
		if (json.status) {
			var item = json.item;
			var cls = Files[item.type.capitalize()];
			var row = new cls(item);
			Files.app.grid.insert(row);
			if (row.type == 'image' && Files.app.grid.layout == 'icons') {
				var image = row.element.getElement('img');
				if (image) {
					row.getThumbnail(function(response) {
						if (response.item.thumbnail) {
							image.set('src', response.item.thumbnail).addClass('loaded').removeClass('loading');
							row.element.getElement('.files-node').addClass('loaded').removeClass('loading');
						}
					});
				}
			}
			Files.app.fireEvent('uploadFile', [row]);
		} else {
			var error = json.error ? json.error : 'Unknown error';

			failed[file.id] = error;
		}
	});

	uploader.bind('StateChanged', function(uploader) {
		$each(failed, function(error, id) {
			icon = jQuery('#' + id).attr('class', 'plupload_failed').find('a').css('display', 'block');
			if (error) {
				icon.attr('title', error);
			}
		});

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

	/**
	 * Switcher between uploaders
	 */
	var toggleForm = function(type) {
		var el = document.id('files-uploader-'+type);
		var style = el.getStyle('display') == 'block' ? 'none' : 'block';

		$$('.upload-form').setStyle('display', 'none');

		if (style == 'block') {
			el.setStyle('display', style);
		}

		// Plupload needs to be refreshed if it was hidden
		if (type == 'computer') {
			var uploader = jQuery('#files-upload-multi').pluploadQueue();
			uploader.refresh();
			if(!uploader.files.length) {
				document.id('files-upload').removeClass('uploader-files-queued').addClass('uploader-files-empty');
				if(document.id('files-upload-multi_browse')) {
					document.id('files-upload-multi_browse').set('text', 'Select files from your computer');
					uploader.bind('QueueChanged', exposePlupload);
				}
			}
		}

		//Scrollfix
		if(el.scrollIntoView) el.scrollIntoView(true);
	};

	$$('.upload-form-toggle').addEvent('click', function(e) {
		var hash = this.get('href').split('#')[1];
		$$('.upload-form-toggle').removeClass('active');
		e.preventDefault();
		this.addClass('active');

		toggleForm(hash);
	});
});

/**
 * Remote file form
 */
window.addEvent('domready', function() {
	var form = document.id('remoteForm'), filename = document.id('remote-name'),
		submit = form.getElement('.remote-submit'), submit_default = submit.get('value'),
		input = document.id('remote-url'), 
		validate = new Request.JSON({
			onRequest: function(){
				submit.set('value', submit_default);
			},
			onSuccess: function(response){
				if(response.error) return this.fireEvent('failure', this.xhr);

				var length = response['content-length'].toInt(10);
				if(length && length < Files.app.container.parameters.maximum_size) {
					var size = new Files.Filesize(length).humanize();
					submit.addClass('valid').set('value', submit_default+' ('+size+')');
				} else {
					submit.setProperty('disabled', 'disabled').removeClass('valid');
				}

			},
			onFailure: function(xhr){
				var response = JSON.decode(xhr.responseText, true);
				if (response.code && parseInt(response.code/100) == 4) {
					submit.setProperty('disabled', 'disabled').removeClass('valid');
				}		
				else {
					submit.removeProperty('disabled').addClass('valid');
				}
			}
		});
 
	input.addEvent('blur', function(e) {
		if (this.value) {
			if (Files.app.container.parameters.maximum_size) {
				validate.setOptions({url: Files.app.createRoute({view: 'proxy', url: this.value})}).get();
			}
			else {
				submit.removeProperty('disabled').addClass('valid');
			}
			
			if(!filename.get('value')) {
				filename.set('value', new URI(this.value).get('file'));
			}
		} else {
			submit.setProperty('disabled', 'disabled').removeClass('valid');
		}
	});

	var request = new Request.JSON({
		url: Files.app.createRoute({view: 'file', folder: Files.app.getPath()}),
		data: {
			action: 'add',
			_token: Files.token,
			file: ''
		},
		onSuccess: function(json) {
			if (this.status == 201 && json.status) {
				var el = json.item;
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);
				Files.app.grid.insert(row);
				if (row.type == 'image' && Files.app.grid.layout == 'icons') {
					var image = row.element.getElement('img');
					if (image) {
						row.getThumbnail(function(response) {
							if (response.item.thumbnail) {
								image.set('src', response.item.thumbnail).addClass('loaded').removeClass('loading');
								row.element.getElement('.files-node').addClass('loaded').removeClass('loading');
							}
						});
					}
				}
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
		request.options.data.file = document.id('remote-url').get('value');
		request.options.url = Files.app.createRoute({
			view: 'file',
			folder: Files.app.getPath(),
			name: document.id('remote-name').get('value')
		});
		request.send();
	});

	//Width fix
	form.getElement('.remote-wrap').setStyle('margin-right', submit.getSize().x);
});
</script>

<div id="files-upload" style="clear: both" class="uploader-files-empty">
	<div id="files-upload-controls">
		<ul class="upload-buttons">
			<li><?= @text('Upload from:') ?></li>
			<li><a class="upload-form-toggle target-computer" href="#computer"><?= @text('Computer'); ?></a></li>
			<li><a class="upload-form-toggle target-web" href="#web"><?= @text('Web'); ?></a></li>
			<li id="upload-max">
				<?= @text('Max'); ?>
				<span id="upload-max-size"></span>
			</li>
		</ul>
	</div>
	<div class="clr"></div>
	<div id="files-uploader-computer" class="upload-form" style="display: none">

		<div style="clear: both"></div>

		<div id="files-upload-multi"></div>

	</div>
	<div class="clr"></div>
	<div id="files-uploader-web" class="upload-form" style="display: none">
		<form action="" method="post" name="remoteForm" id="remoteForm" >
			<div class="remote-wrap">
				<input type="text" placeholder="<?= @text('Remote URL') ?>" id="remote-url" name="file" size="50" />
				<input type="text" placeholder="<?= @text('File name') ?>" id="remote-name" name="name" />
			</div>
			<input type="submit" class="remote-submit valid" value="<?= @text('Transfer File'); ?>" />
			<input type="hidden" name="action" value="save" />
			</fieldset>
		</form>
	</div>
</div>
