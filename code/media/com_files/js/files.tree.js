
Files.Tree = new Class({
	Extends: MooTreeControl,
	Implements: [Options],
	options: {
		mode: 'folders',
		grid: true,
		onClick: $lambda,
		onAdopt: $lambda,
		adopt: null,
		root: {
			open: true
		}
	},
	initialize: function(options) {
		this.setOptions(options);

		this.onAdopt = this.options.onAdopt;

		this.parent(this.options, this.options.root);

		if (options.adopt) {
			this.adopt(options.adopt);
		}
	},
	select: function(node, noClick) {
		this.parent(node, noClick);
		while (true) {
			if (!node.parent || node.parent.id == null) {
				break;
			}
			node.parent.toggle(false, true);
			node = node.parent;
		}
	},
	adopt: function(id, parentNode) {
		this.parent(id, parentNode);

		this.onAdopt(id, parentNode);
	}
});