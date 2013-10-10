/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

Files.Paginator = new Class({
	Implements: [Options, Events],
	state: null,
	values: {
		total: 0,
		limit: 0,
		offset: 0,
		page_total: 0,
		page_current: 0
	},
	initialize: function(element, options) {
		if (options.state) {
			this.state = options.state;
			this.setData(this.state.getData());
		}

		this.setOptions(options);

		var element = document.id(element);

		this.element = element;
		this.elements = {
			page_total: element.getElement('span.page-total'),
			page_current: element.getElement('span.page-current'),
			page_start: element.getElement('a.pagination__first'),
			page_next: element.getElement('a.pagination__next'),
			page_prev: element.getElement('a.pagination__previous'),
			page_end: element.getElement('a.pagination__last'),
			page_container: element.getElement('div.page-list'),
			pages: {},
			limit_box: element.getElement('select')
		};

		this.setValues();

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
		this.fireEvent('beforeSetValues');

		var values = this.values, els = this.elements;

		this.setPageData(els.page_start, {offset: 0});

		this.setPageData(els.page_end, {offset: (values.page_total-1)*values.limit});

		this.setPageData(els.page_prev, {offset: Math.max(0, (values.page_current-2)*values.limit)});

		var offset = Math.min(((values.page_total-1)*values.limit),(values.page_current*values.limit));
		this.setPageData(els.page_next, {offset: offset});

		els.page_container.empty();
		var i = 1;
		while (i <= values.page_total) {

			if (i == values.page_current) {
				var el = new Element('a', {
					href: '#',
					text: i,
					'class': 'btn disabled',
					'data-limit': values.limit,
					'data-offset': (i-1)*values.limit,
					'events': {
						'click': function(e) { e.stop(); }
					}
				});
			} else {
				var el = new Element('a', {
					href: '#',
					text: i,
					'class': 'btn',
					'data-limit': values.limit,
					'data-offset': (i-1)*values.limit
				});
			}
			els.pages[i] = el;
			el.inject(els.page_container);
			i++;
		}

		els.page_current.set('text', values.page_current);
		els.page_total.set('text', values.page_total);

		els.limit_box.set('value', values.limit);

		this.fireEvent('afterSetValues');
	},
	setPageData: function(page, data) {
		this.fireEvent('beforeSetPageData', {page: page, data: data});

		var limit = data.limit || this.values.limit;
		page.set('data-limit', limit);
		page.set('data-offset', data.offset);

		var method = data.offset == this.values.offset ? 'addClass' : 'removeClass';
		page.getParent().getParent()[method]('off');
		page.set('data-enabled', (data.offset != this.values.offset)-0);

		this.fireEvent('afterSetPageData', {page: page, data: data});
	},
	setData: function(data) {
		this.fireEvent('beforeSetData', {data: data});

		var values = this.values;
		if (data.total == 0) {
			values.limit = this.state.get('limit');
			values.offset = this.state.get('offset');
			values.total = 0;
			values.page_total = 1;
			values.page_current = 1;
		} else {
			$each(data, function(value, key) {
				values[key] = value;
			});

			values.limit = Math.max(values.limit, 1);
			values.offset = Math.max(values.offset, 0);

			if (values.limit > values.total) {
				values.offset = 0;
			}

			if (!values.limit) {
				values.offset = 0;
				values.limit = values.total;
			}

			values.page_total = Math.ceil(values.total/values.limit);

			if (values.offset > values.total) {
				values.offset = (values.page_total-1)*values.limit;
			}
			values.page_current = Math.floor(values.offset/values.limit)+1;
		}

		this.fireEvent('afterSetData', {data: data});
	}
});