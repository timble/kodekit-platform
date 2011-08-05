
Files.Tree = new Class({
	Extends: MooTreeControl,
	Implements: [Options],
	options: {
		mode: 'folders',
		grid: true,
		onClick: $empty,
		onAdopt: $empty,
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
	/**
	 * We need to duplicate this because in the latest Mootree noClick argument is removed.
	 */
	select: function(node, noClick) {
		if (!$chk(noClick)) {
			this.onClick(node); node.onClick(); // fire click events
		}
		if (this.selected === node) return; // already selected
		if (this.selected) {
			// deselect previously selected node:
			this.selected.select(false);
			this.onSelect(this.selected, false);
		}
		// select new node:
		this.selected = node;
		node.select(true);
		this.onSelect(node, true);
		
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