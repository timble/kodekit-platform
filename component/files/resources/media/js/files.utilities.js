/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

if(!Files) var Files = {};

if (!Files._) {
	Files._ = function(string) {
		return string;
	};
}

Files.Filesize = new Class({
	Implements: Options,
	options: {
		units: ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB']
	},
	initialize: function(size, options) {
		this.setOptions(options);
		this.size = size;
	},
	humanize: function() {
		var i = 0, size = this.size;
		while (size >= 1024) {
			size /= 1024;
			i++;
		}

		return (i === 0 || size % 1 === 0 ? size : size.toFixed(2)) + ' ' + Files._(this.options.units[i]);
	}
});

Files.FileTypes = {};
Files.FileTypes.map = {
	'audio': ['aif','aiff','alac','amr','flac','ogg','m3u','m4a','mid','mp3','mpa','wav','wma'],
	'video': ['3gp','avi','flv','mkv','mov','mp4','mpg','mpeg','rm','swf','vob','wmv'],
	'image': ['bmp','gif','jpg','jpeg','png','psd','tif','tiff'],
	'document': ['doc','docx','rtf','txt','xls','xlsx','pdf','ppt','pptx','pps','xml'],
	'archive': ['7z','gz','rar','tar','zip']
};

Files.getFileType = function(extension) {
	var type = 'document';
	extension = extension.toLowerCase();
	$each(Files.FileTypes.map, function(value, key) {
		if (value.contains(extension)) {
			type = key;
		}
	});
	return type;
};

/**
 * Returns the first error message in a response object
 * @param response
 * @returns null|string
 */
Files.getResponseError = function(response) {
    var error;

    if (response && typeof response.errors === 'object' && response.errors instanceof Array) {
        if (response.errors.length) {
            error = response.errors[0].message;
        }
    }

    return error;
}