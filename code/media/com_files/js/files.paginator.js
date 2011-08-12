
Files.Paginator = new Class({
	Implements: [Options, Events],
	state: {
		total: 0,
		limit: 0,
		offset: 0,
		page_total: 0,
		page_current: 0
	},
	defaults: {limit: 0, offset: 0},
	initialize: function(element, options) {
		if (options.state) {
			this.setData(options.state);
		}
		if (options.defaults) {
			this.defaults = options.defaults;
		}
		this.setOptions(options);

		var element = document.id(element);

		this.element = element;
		this.elements = {
			page_total: element.getElement('span.page-total'),
			page_current: element.getElement('span.page-current'),
			page_start: element.getElement('div.start a'),
			page_next: element.getElement('div.next a'),
			page_prev: element.getElement('div.prev a'),
			page_end: element.getElement('div.end a'),
			page_container: element.getElement('div.page'),
			pages: {},
			limit_box: element.getElement('select')
		};
		this.element.addEvent('click:relay(a)', function(e) {
			e.stop();
			if (e.target.get('data-enabled') == '0') {
				return;
			}
			this.fireEvent('clickPage', e.target);
		}.bind(this));
		this.elements.limit_box.addEvent('change', function(e) {
			e.stop();
			this.fireEvent('changeLimit', this.elements.limit_box.get('value'));
		}.bind(this));

	},
	setValues: function() {
		var state = this.state, els = this.elements;

		this.setPageData(els.page_start, {offset: 0});

		this.setPageData(els.page_end, {offset: (state.page_total-1)*state.limit});

		this.setPageData(els.page_prev, {offset: Math.max(0, (state.page_current-2)*state.limit)});

		var offset = Math.min(((state.page_total-1)*state.limit),(state.page_current*state.limit));
		this.setPageData(els.page_next, {offset: offset});

		els.page_container.empty();
		var i = 1;
		while (i <= state.page_total) {

			if (i == state.page_current) {
				var el = new Element('span', {text: i});
			} else {
				var el = new Element('a', {
					href: '#',
					text: i,
					'data-limit': state.limit,
					'data-offset': (i-1)*state.limit
				});
			}
			els.pages[i] = el;
			el.inject(els.page_container);
			i++;
		}

		els.page_current.set('text', state.page_current);
		els.page_total.set('text', state.page_total);
	},
	setPageData: function(page, data) {
		var limit = data.limit || this.state.limit;
		page.set('data-limit', limit);
		page.set('data-offset', data.offset);

		var method = data.offset == this.state.offset ? 'addClass' : 'removeClass';
		page.getParent().getParent()[method]('off');
		page.set('data-enabled', (data.offset != this.state.offset)-0);
	},
	setData: function(data) {
		var state = this.state;
		if (data.total == 0) {
			state.limit = this.defaults.limit;
			state.offset = this.defaults.offset;
			state.total = 0;
			state.page_total = state.page_current = 1;
		} else {
			$each(data, function(value, key) {
				state[key] = value;
			});

			state.limit = Math.max(state.limit, 1);
			state.offset = Math.max(state.offset, 0);

			if (state.limit > state.total) {
				state.offset = 0;
			}

			if (!state.limit) {
				state.offset = 0;
				state.limit = state.total;
			}

			state.page_total = Math.ceil(state.total/state.limit);

			if (state.offset > state.total) {
				state.offset = (state.page_total-1)*state.limit;
			}
            state.page_current = Math.floor(state.offset/state.limit)+1;
		}
	}
});