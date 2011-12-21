/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
 
if(!Files) var Files = {};

Files.Filesize = new Class({
	Implements: Options,
	options: {
		units: ['Bytes', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb']
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
		return (i === 0 ? size : size.toFixed(2)) + ' ' + this.options.units[i];
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
	$each(Files.FileTypes.map, function(value, key) {
		if (value.contains(extension)) {
			type = key; 
		}
	});
	return type;
};