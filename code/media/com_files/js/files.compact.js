
var Files = Files || {};
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
		}
	},

	initialize: function(options) {
		this.editor = this.options.editor;
		this.preview = document.id(this.options.preview);

		this.parent(options);
	},
	setPaginator: function() {
	},
	setGrid: function() {
		var opts = this.options.grid;
		var that = this;
		$extend(opts, {
			'onClickParent': function(e) {
				if (that.tree.selected.parent) {
					that.tree.select(that.tree.selected.parent);
				}
			},
			'onClickImage': function(e) {
				var target = document.id(e.target).getParent('.files-node');
				target.getParent().getChildren().removeClass('active');
				target.addClass('active');
				var row = target.retrieve('row');
				var copy = $extend({}, row);
				copy.template = 'details_image';

				that.preview.empty();

				copy.render().inject(that.preview);
			},
			'onClickFile': function(e) {
				var target = document.id(e.target).getParent('.files-node');
				target.getParent().getChildren().removeClass('active');
				target.addClass('active');
				var row = target.retrieve('row');
				var copy = $extend({}, row);
				copy.template = 'details_file';

				that.preview.empty();

				copy.render().inject(that.preview);
			}
		});
		this.grid = new Files.Grid(this.options.grid.element, opts);
	},
	navigate: function(path) {
		if (path) {
			this.preview.empty();
		}

		this.parent(path);
	}
});