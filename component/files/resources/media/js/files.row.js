/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

if(!Files) var Files = {};

Files.Row = new Class({
	Implements: [Options, Events, Files.Template],
	initialize: function(object, options) {
		this.setOptions(options);

		$each(object, function(value, key) {
			this[key] = value;
		}.bind(this));

		if (!this.path) {
			this.path = (object.folder ? object.folder+'/' : '') + object.name;
		}
		this.identifier = this.path;

		this.filepath = (object.folder ? this.encodePath(object.folder)+'/' : '') + this.encode(object.name);
	},
	encodePath: function(path, encoder) {
		var parts = path.split('/');

		if (!encoder) {
			encoder = this.encode;
		}

		parts = parts.map(function(part) {
			return encoder(part);
		});

		return parts.join('/');
	},
	encode: function(string) {
		return encodeURIComponent(encodeURIComponent(string)).replace(/%2520/g, ' ');
	},
	realpath: function(string) {
		string = encodeURIComponent((string + '').toString());
		return encodeURIComponent(string).replace(/!/g, '%2521').replace(/'/g, '%2527').replace(/\(/g, '%2528').
	    	replace(/\)/g, '%2529').replace(/\*/g, '%252A').replace(/%2520/g, ' ');
	}
});

Files.File = new Class({
	Extends: Files.Row,

	type: 'file',
	template: 'file',
	initialize: function(object, options) {
		this.parent(object, options);

		if (Files.app) {
			this.baseurl = Files.app.baseurl;
		}
		
		this.size = new Files.Filesize(this.metadata.size);
		this.filetype = Files.getFileType(this.metadata.extension);
	},
	getModifiedDate: function(formatted) {
		var date = new Date();
		date.setTime(this.metadata.modified_date*1000);
		if (formatted) {
			return date.getUTCDate()+'/'+date.getUTCMonth()+'/'+date.getUTCFullYear()+' '+date.getUTCHours()+':'+date.getUTCMinutes();
		} else {
			return date;
		}
	},
	'delete': function(success, failure) {
		this.fireEvent('beforeDeleteRow');

		var that = this,
			request = new Request.JSON({
				url: Files.app.createRoute({view: 'file', folder: that.folder, name: that.name}),
				method: 'post',
				data: {
					'_action': 'delete',
					'_token': Files.token
				},
				onSuccess: function(response, responseText) {
					if (typeof success == 'function') {
						success(response);
					}
					that.fireEvent('afterDeleteRow', {status: true, response: response, request: this});
				},
				onFailure: function(xhr) {
					if (xhr.status == 204 || xhr.status == 1223) {
						// Mootools thinks it failed, weird
						return this.onSuccess();
					}

					response = xhr.responseText;
					if (typeof failure == 'function') {
						failure(xhr);
					}
					else {
						response = JSON.decode(xhr.responseText, true);
                        error = Files.getResponseError(response) || Files._('An error occurred during request');
						alert(error);
					}

					that.fireEvent('afterDeleteRow', {status: false, response: response, request: this, xhr: xhr});
				}
			});
		request.send();
	}
});

Files.Image = new Class({
	Extends: Files.File,

	type: 'image',
	template: 'image',
	initialize: function(object, options) {
		this.parent(object, options);

		this.image = this.baseurl+'/'+this.encodePath(this.filepath, this.realpath);

		this.client_cache = false;
		if(window.sessionStorage) {
		    if(sessionStorage[this.image.toString()]) {
		        this.client_cache = sessionStorage[this.image.toString()];
		    }
		}
	},
	getThumbnail: function(success, failure) {
		var that = this,
			request = new Request.JSON({
				url: Files.app.createRoute({view: 'thumbnail', filename: that.name, folder: that.folder}),
				method: 'get',
				onSuccess: function(response, responseText) {
					if (typeof success == 'function') {
						success(response);
					}
				},
				onFailure: function(xhr) {
					var response = xhr.responseText;

					if (typeof failure == 'function') {
						failure(xhr);
					}
					else {
                        response = JSON.decode(xhr.responseText, true);

                        alert(Files.getResponseError(response) || Files._('An error occurred during request'));
					}
				}
			});
		request.send();
	}
});


Files.Folder = new Class({
	Extends: Files.Row,

	type: 'folder',
	template: 'folder',

	getChildren: function(success, failure, extra_vars, url_builder) {
		var path = this.path,
			url = {
				view: 'nodes',
				folder: path
			};
		if (extra_vars) {
			url = $extend(url, extra_vars);
		}
		
		url = url_builder ? url_builder(url) : Files.app.createRoute(url);

        if (Files.Folder.Request.running) {
            Files.Folder.Request.cancel();
        }

		Files.Folder.Request._onSuccess = success;
		Files.Folder.Request._onFailure = failure;
		Files.Folder.Request.options.url = url;
		Files.Folder.Request.get();
	},
	'add': function(success, failure) {
		this.fireEvent('beforeAddRow');

		var that = this;
			request = new Request.JSON({
				url: Files.app.createRoute({view: 'folder', name: that.name, folder: Files.app.getPath()}),
				method: 'post',
				data: {
					'_action': 'add',
					'_token': Files.token
				},
				onSuccess: function(response, responseText) {
					if (typeof success == 'function') {
						success(response);
					}

					that.fireEvent('afterAddRow', {status: true, response: response, request: this});
				},
				onFailure: function(xhr) {
					var response = xhr.responseText;

					if (typeof failure == 'function') {
						failure(xhr);
					}
					else {
                        response = JSON.decode(xhr.responseText, true);

                        alert(Files.getResponseError(response) || Files._('An error occurred during request'));
					}

					that.fireEvent('afterAddRow', {status: false, response: response, request: this, xhr: xhr});
				}
			});
		request.send();
	},
	'delete': function(success, failure) {
		var that = this,
			request = new Request.JSON({
				url: Files.app.createRoute({view: 'folder', folder: Files.app.getPath(), name: that.name}),
				method: 'post',
				data: {
					'_action': 'delete',
					'_token': Files.token
				},
				onSuccess: function(response, responseText) {
					if (typeof success == 'function') {
						success(response);
					}

					that.fireEvent('afterDeleteRow', {status: true, response: response, request: this});
				},
				onFailure: function(xhr) {
					if (xhr.status == 204 || xhr.status == 1223) {
						// Mootools thinks it failed, weird
						return this.onSuccess();
					}

					var response = xhr.responseText;

					if (typeof failure == 'function') {
						failure(xhr);
					}
					else {
                        response = JSON.decode(xhr.responseText, true);

                        alert(Files.getResponseError(response) || Files._('An error occurred during request'));
					}

					that.fireEvent('afterDeleteRow', {status: false, response: response, request: this, xhr: xhr});
				}
			});
		request.send();
	}
});


Files.Folder.Request = new Request.JSON({
	method: 'get',
	onSuccess: function(response, responseText) {
		if (typeof this._onSuccess == 'function') {
			this._onSuccess(response);
		}
	},
	onFailure: function(xhr) {
		if (typeof this._onFailure == 'function') {
			this._onFailure(xhr);
		}
		else {
			var response = JSON.decode(xhr.responseText, true),
                error = Files.getResponseError(response) || Files._('An error occurred during request');

			alert(error);
		}
	}
});