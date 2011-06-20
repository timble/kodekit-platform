<script>
(function() {

var uploader_details = {
	config: {
		url: '<?= JRoute::_('index.php?option=com_files&view=file&format=json&'.JFactory::getSession()->getName().'='.JFactory::getSession()->getId(), false); ?>',
		path: 'media://system/swf/uploader.swf',
		typeFilter: {
			'<?= $allowed_extensions; ?>': '<?= $allowed_extensions; ?>'
		},
		token: '<?= JUtility::getToken(); ?>'
	}
};

window.addEvent('domready', function() {

	var web = document.id('upload-toggle-web');
	var web_form = document.id('upload-web');

	web_form.setStyle('display', 'none');
	web.addEvent('click', function(e) {
		e.stop();
		web_form.setStyle('display', 'block');
	});

	var toggleButtons = function(status) {
		var start = document.id('upload-start');
		var clear = document.id('upload-clear');

		$$(start, clear).setStyle('display', status ? 'block' : 'none');
		web_form.setStyle('display', status ? 'none' : 'block');
		web.set('disabled', status);
	};

	Files.uploader = new Files.Uploader(document.id('upload-progress'), document.id('upload-queue'), {
		fileClass: Files.Uploader.File,
		url: uploader_details.config.url+'&identifier='+Files.identifier,
		method: 'post',
		data: {
			'parent': Files.app.getPath(),
			'action': 'add',
			'_token': uploader_details.config.token
		},
		path: uploader_details.config.path,
		typeFilter: uploader_details.config.typeFilter,
		target: 'upload-browse',

		onBeforeStart: function() {
			this.options.data.parent = Files.app.getPath();
			this.setOptions({data: this.options.data});
		},
		onLoad: function() {
			document.id('upload-flash').setStyle('display', 'block');
			document.id('upload-noflash').setStyle('display', 'none');

			toggleButtons(false);
			document.id('upload-web').setStyle('display', 'none');

			// We relay the interactions with the overlayed flash to the link
			this.target.addEvents({
				click: function() {
					return false;
				},
				mouseenter: function() {
					this.addClass('hover');
				},
				mouseleave: function() {
					this.removeClass('hover');
					this.blur();
				},
				mousedown: function() {
					this.focus();
				}
			});

			document.id('upload-clear').addEvent('click', function() {
				this.status.setStyle('display', 'none');
				Files.uploader.remove();
				return false;
			}.bind(this));

			document.id('upload-start').addEvent('click', function() {
				this.status.setStyle('display', 'block');
				Files.uploader.start();
				return false;
			}.bind(this));
		},
		onQueue: function() {
			if (this.fileList.length == 0) {
				toggleButtons(false);
			}
			else {
				toggleButtons(true);
			}
		},
		/**
		 * Is called when files were not added, "files" is an array of invalid File classes.
		 *
		 * This example creates a list of error elements directly in the file list, which
		 * hide on click.
		 */
		onSelectFail: function(files) {
			files.each(function(file) {
				var li = new Element('li', {
					'class': 'validation-error',
					html: file.validationErrorMessage || file.validationError,
					title: MooTools.lang.get('FancyUpload', 'removeTitle'),
					events: {
						click: function() {
							this.destroy();
						}
					}
				}).inject(this.list, 'top');
				new Element('a', {
					href: '#',
					text: '[x]',
					events: {
						click: function(e) {
							e.stop();
							this.getParent().dispose();
						}
					}
				}).inject(li);


			}, this);
		},

		onFileSuccess: function(file, response) {
			var json = JSON.decode(response, true) || {};
			if (json.status) {
				file.element.addClass('file-success');
				file.info.set('html', 'Image was uploaded');
				var el = json.file;
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);
				Files.app.container.insert(row);
				this.fireEvent('uploadFile', [row]);
			} else {
				file.element.addClass('file-failed');
				var error = json.error ? json.error : 'Unknown error';
				file.info.set('html', '<strong>An error occurred:</strong> ' + error);
			}
		}
	});
});

})();

window.addEvent('domready', function() {
	var form = document.id('remoteForm');
	var request = new Request.JSON({
		url: form.getProperty('action'),
		data: form,
		onSuccess: function(json) {
			if (this.status == 201 && json.status) {
				var el = json.file;
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);
				Files.app.container.insert(row);
				this.fireEvent('uploadFile', [row]);
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
		request.send();
	});
});
</script>

<div id="upload">
	<form action="<?= @route('view=file') ?>" method="post" name="adminForm" id="uploadForm" enctype="multipart/form-data">
		<fieldset id="upload-noflash" class="actions">
			<input type="file" id="file-upload" name="file" />
			<input type="hidden" class="file-basepath" name="parent" />
			<input type="submit" id="file-upload-submit" value="<?= @text('Start Upload'); ?>"/>
			<input type="hidden" name="action" value="save" />
		</fieldset>
	</form>
	<div id="upload-flash" class="hide">
		<h3><?= @text('Upload') ?>:</h3>
		<ul class="upload-buttons">
			<li><button id="upload-browse"><?= @text('Computer'); ?></button></li>
			<li><button id="upload-toggle-web"><?= @text('Web'); ?></button></li>
		</ul>
		<p id="upload-max">
			<?= @text('Max'); ?>
			<?= @helper('admin::com.files.template.helper.filesize.humanize', array('size' => $maxsize))?>
		</p>
		<div id="upload-progress" style="display: none">
			<img src="media://com_files/images/bar.gif" alt="<?= @text('Current Progress'); ?>" class="progress current-progress" />
			<img src="media://com_files/images/bar.gif" alt="<?= @text('Overall Progress'); ?>" class="progress overall-progress" />
			<p class="overall-title"></p>
			<div class="current-text"></div>
			<div class="current-title"></div>
		</div>
		<table class="upload-queue" id="upload-queue"  cellpadding="0" cellspacing="0">
		</table>
		<ul class="upload-buttons">
			<li><button id="upload-clear"><?= @text('Clear List'); ?></button></li>
			<li><button id="upload-start"><?= @text('Start Upload'); ?></button></li>
		</ul>
	</div>
	<div class="clr"></div>
	<div id="upload-web">
		<form action="<?= @route('view=file&format=json&identifier='.$state->identifier->identifier) ?>" method="post" name="remoteForm" id="remoteForm" >
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
