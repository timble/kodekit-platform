/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
 
if (!Files) Files = {};
Files.Compact = {};

Files.Compact.App = new Class({
	Extends: Files.App,
	Implements: [Events, Options],

	options: {
		types: ['file', 'image'],
		editor: null,
		preview: 'files-preview',
		grid: {
			cookie: false,
			layout: 'compact',
			batch_delete: false
		},
		history: {
			enabled: false
		}
	},

	initialize: function(options) {
		this.parent(options);
		
		this.editor = this.options.editor;
		this.preview = document.id(this.options.preview);
	},
	setPaginator: function() {
	},
	setGrid: function() {
		var opts = this.options.grid;
		var that = this;
		$extend(opts, {
			'onClickImage': function(e) {
				var target = document.id(e.target),
				    node = target.getParent('.files-node-shadow') || target.getParent('.files-node');
				
				node.getParent().getChildren().removeClass('active');
				node.addClass('active');
				var row = node.retrieve('row');
				var copy = $extend({}, row);
				copy.template = 'details_image';

				that.preview.empty();

				copy.render('compact').inject(that.preview);

				that.preview.getElement('img').set('src', copy.image);
			},
			'onClickFile': function(e) {
				var target = document.id(e.target),
			   		node = target.getParent('.files-node-shadow') || target.getParent('.files-node');
			
				node.getParent().getChildren().removeClass('active');
				node.addClass('active');
				var row = node.retrieve('row');
				var copy = $extend({}, row);
				copy.template = 'details_file';
	
				that.preview.empty();

				copy.render('compact').inject(that.preview);
			}
		});
		this.grid = new Files.Grid(this.options.grid.element, opts);
	}
});