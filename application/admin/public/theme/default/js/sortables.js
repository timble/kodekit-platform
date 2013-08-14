/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/*
---

name: Drag.Sortable.js

description: Improves the native sortables class

license: MIT-style license.

author: Stian Didriksen <stian@nooku.org>

copyright: Copyright needs to be Timble CVBA. (http://www.timble.net) All rights reserved.

requires: [Sortables]

provides: Drag.Sortable

...
*/

Drag.Sortable = new Class({

	Extends: Sortables,

	options: {
        nested: false, //activate a special nested mode
		revert: true,
		clone: true,
		fx: {
			duration: 300,
			transition: Fx.Transitions.Sine.easeInOut,
			from: {
				opacity: [1, 0.6]
			},
			to: {
				opacity: 1
			}
		},
		converter: false,
		adapter: {
			type: 'cookie',
			options: {}
		},
        direction: 'asc',


		onStart: function(element, ghost){

			//@TODO Use css class instead
			element.setStyle('opacity', 0);

			//Saves the element being dragged
			this.dragged = element;

		},
		onComplete: function(element){

			var key = this.lists.indexOf(this.list);

			this.adapters[key].store(this, this.serialize(key));

		}

	},

	initialize: function(lists, options){

		this.parent(lists, options);

		this.adapters = [];
		this.lists.each(function(list, key){
            var children = list.getChildren();
            if(this.options.direction === 'desc') {
                var k = 0;
                for (var i = children.length - 1; i >= 0; i--) {
                    children[k].setProperty('data-order', i);
                    k++;
                }
            } else {
                children.each(function(row, i){
                    row.setProperty('data-order', i);
                }, this);
            }

            /*
			list.getChildren().each(function(row, i){

			}, this);
            //*/

			var adapter = new Drag.Sortable.Adapter[this.options.adapter.type.capitalize()](this.options.adapter.options);
			adapter.retrieve(this, this.serialize(this.options.converter));
			this.adapters[key] = adapter;
		}, this);

	},

	getClone: function(event, element){

		var clone = this.parent(event, element);	

		clone.addClass('clone');

		return clone;

	},

	start: function(event, element){

		this.parent(event, element);	

		this.element.setStyle('opacity', 0);

		this.clone.set('morph', {duration: this.options.fx.duration, transition: this.options.fx.transition}).morph(this.options.fx.from);

        if(this.options.nested) {
            var spacing = this.element.getParents('table')[0].getStyle('border-spacing').split(' ')[0].toInt(),
                cells = this.clone.getChildren();
            var current = this.element, nexts = new Elements;
            while(current.getNext() && current.getNext().getProperty('data-sortable-parent').toInt() !== this.element.getProperty('data-sortable-parent').toInt() && current.getNext().getProperty('data-sortable-level').toInt() > this.element.getProperty('data-sortable-level').toInt()) {
                current = current.getNext().addClass('clone');

                current.getChildren().each(function(cell, i){
                    cell.setStyles({
                        maxWidth: '100%',
                        maxHeight: '100%',
                        minHeight: 'auto',
                        minWidth: 'auto',
                        width: this._getOffsetSize(cell),
                        height: this._getOffsetSize(cell, true),
                        paddingTop: cell.getStyle('padding-top'),
                        paddingRight: cell.getStyle('padding-right'),
                        paddingBottom: cell.getStyle('padding-bottom'),
                        paddingLeft: cell.getStyle('padding-left')
                    });
                }, this);

                nexts.include(current);
            }
            this.drag.addEvent('drag', function(el, event){

                var top = this.value.now.y + el.getScrollSize().y;
                nexts.setStyles({position: 'absolute', width: element.getScrollSize().x});
                nexts.forEach(function(element){
                    element.setStyle('top', top);
                    top += element.getScrollSize().y;
                });
                console.warn('drag', this.value.now.y);
            });
            this.drag.addEvent('complete', function(el, event){

                nexts.setStyles({position: '', top: ''}).removeClass('clone').getChildren().forEach(function(el){
                    el.setStyles({
                        maxWidth: '',
                        maxHeight: '',
                        minHeight: '',
                        minWidth: '',
                        width: '',
                        height: '',
                        paddingTop: '',
                        paddingRight: '',
                        paddingBottom: '',
                        paddingLeft: ''
                    });
                });
                nexts.inject(element, 'after');
            });
        }
	},

	reset: function(){

		this.element.set('opacity', this.opacity);

		this.parent();

	},

	end: function(){
		this.drag.detach();

		if (this.effect){
			var dim = this.element.getStyles('width', 'height');
			var pos = this.clone.computePosition(this.element.getPosition(this.clone.offsetParent));
			this.effect.element = this.clone;
			this.effect.start({
				top: pos.top,
				left: pos.left,
				width: dim.width,
				height: dim.height,
				opacity: this.opacity
			}).chain(this.reset.bind(this));
		} else {
			this.reset();
		}
	},

	serialize: function(){
		var params = Array.link(arguments, {modifier: Function.type, index: $defined});
		var serial = this.lists.map(function(list){
			return list.getChildren().map(params.modifier || function(element){
				return this.elements.indexOf(element);
			}, this);
		}, this);

		var index = params.index;
		if (this.lists.length == 1) index = 0;
		return $chk(index) && index >= 0 && index < this.lists.length ? serial[index] : serial;
	},

    getDroppables: function(){
        var droppables = this.parent();
        //Perhaps a bit daring to rely on this.element being set already
        if(this.options.nested) {
            var group = parseInt(this.element.getProperty('data-sortable-parent'), 10);
            droppables = droppables.filter(function(item){
                return parseInt(item.getProperty('data-sortable-parent'), 10) === group;
            });
        }
        return droppables;
    }

});

Element.implement({

	sortable: function(options){

		if(!this.$sortable) this.$sortable = new Drag.Sortable(this, options);

		return this.$sortable;

	}

});


if (!$chk(Drag.Sortable.Adapter)) Drag.Sortable.Adapter = {};


Drag.Sortable.Adapter.Cookie = new Class({

	Extends: Hash.Cookie,

	initialize: function(options){

		return this.parent(options.name || 'order', options);

	},

	retrieve: function(instance, order){
		instance.lists.each(function(list){
			var sorted = list.getChildren().sort(function(a, b){

				order = ['a', 'b'].map(function(key){
					return this.adapter.get(this[key].getProperty('data-order'));
				}, {adapter: this, a: a, b: b});

				return order[0] - order[1];

			}.bind(this));

			list.adopt(sorted);
		}, this);

	},

	store: function(instance, order){

		order.each(function(order, index){
			this[order] = index;
		}, store = {});

		this.hash.extend(store);
		this.save();

	}

});

Drag.Sortable.Adapter.Request = new Class({

	Extends: Request.JSON,

	options: {
		url: window.location.pathname + window.location.search,
		saveclass: 'saving',
		errorclass: 'error',

		onRequest: function(){
			this.instance.dragged.addClass(this.options.saveclass).removeClass(this.options.errorclass);
		},
		onFailure: function(){
			this.instance.dragged.removeClass(this.options.saveclass).addClass(this.options.errorclass);
		},
		onComplete: function(){
			this.instance.dragged.removeClass(this.options.saveclass).removeClass(this.options.errorclass);
		}
	},

	initialize: function(options){

		this.parent(options);

	},

	retrieve: function(instance, order){

		this.instance = instance;

	},

	store: function(instance, order){

		if(typeof this.options.data != 'object') this.options.data = {};
		instance.lists[0].getChildren().each(function(item, index){
			offset = index - item.getProperty('data-order');
			if(offset !== 0) this.options.data[item.getProperty('data-id')] = offset;
		}, this);

		this.send();
	}

});

Drag.Sortable.Adapter.Koowa = new Class({

	Extends: Drag.Sortable.Adapter.Request,

	options: {
		method: 'post',
		key: 'ordering',
		offset: 'relative'
	},

    initialize: function(options){

        this.parent(options);

        this.addEvent('onSuccess', function(){
            var prev_orderings = [], next_orderings = [], base = false;
            this.getRows().each(function(item){
                var index = item.getProperty('data-index');
                if(index !== null) {
                    item.setProperty('data-order', index);
                }
                var order_element = item.getElement('.data-order')
                if(order_element) {
                    prev_orderings.push(order_element);
                    next_orderings.push(parseInt(order_element.get('text'), 10));
                }
            }, this);
            next_orderings.sort();
            next_orderings.each(function(item, index){
                if(base === false)  base = item;
                else                base++;
                //Don't update .data-order if the list isn't a clean 1-step incremental list
                if(item !== base)   prev_orderings.length = 0;
            });
            prev_orderings.each(function(item, index){
                item.set('text', next_orderings[index]);
            });
        });

    },

	store: function(instance, order){
		var backup = this.options.url, value, id = instance.element.getElement('[name^=id]').value;
		this.getRows().each(function(item, index){
			if(this.options.offset == 'relative') offset = index - parseInt(item.getProperty('data-order'), 10);
			if(this.options.offset == 'absolute') offset = instance.elements.indexOf(item);

            item.setProperty('data-index', index);
            if(item.getElement('[name^=id]').value == id) {
                value = offset;
            }
		}, this);

        if(value) {
            this.options.url += '&id='+id;
            this.options.data[this.options.key] = value;
            this.send();
            this.options.url = backup;
        }
	},

    getRows: function(){
        return this.instance.lists[0].getChildren().filter(function(item){
            return !item.hasClass('clone');
        });
    }

});
/*
---

name: Table.Sortable.js

description: Gives tables a sortable behavior

license: MIT-style license.

author: Stian Didriksen <stian@nooku.org>

copyright: Copyright needs to be Timble CVBA. (http://www.timble.net) All rights reserved.

requires: [Drag.Sortable]

provides: Table.Sortable

...
*/

if (!$chk(Table)) var Table = {};

Table.Sortable = new Class({

	Extends: Drag.Sortable,

	options: {

		zebra: true,
		constrain: true,
		numcolumn: false,


		onSort: function(){
            console.log('sort', arguments, this);
			this.clone.inject(this.element, 'before');
			this.ghost.inject(this.element, 'after');

		},
		
		onComplete: function(){
		
			var key = this.lists.indexOf(this.list);
			
			this.adapters[key].store(this, this.serialize(key));
		
			if(this.options.numcolumn) {
				
				(function(){
					var numbers = [];
					this.list.getElements(this.options.numcolumn).each(function(row){
						numbers.push(row.get('text').toInt());
					}, this);
					numbers.sort(function(a, b){
						return a > b;
					});
					this.list.getChildren().each(function(row, i){
						var numcol = row.getElement(this.options.numcolumn);
						if(numcol) numcol.set('text', numbers[i]);
						if(i % 2) {
							row.removeClass('row0').addClass('row1');
						} else {
							row.removeClass('row1').addClass('row0');
						}
					}, this);
				}.bind(this)).delay(400);
			}
		
		}
	},

    initialize: function(lists, options){

        this.parent(lists, options);

        //To allow scrolling the list without draggables getting messed up
        lists.getParent().setStyle('position', 'relative');
    },

	start: function(event, element){

		this.parent(event, element);

		var spacing = this.element.getParents('table')[0].getStyle('border-spacing').split(' ')[0].toInt(), 
			cells = this.clone.getChildren();

		this.element.getChildren().each(function(cell, i){
			cells[i].setStyles({
                maxWidth: '100%',
                maxHeight: '100%',
                minHeight: 'auto',
                minWidth: 'auto',
				width: this._getOffsetSize(cell),
				height: this._getOffsetSize(cell, true),
				paddingTop: cell.getStyle('padding-top'),
				paddingRight: cell.getStyle('padding-right'),
				paddingBottom: cell.getStyle('padding-bottom'),
				paddingLeft: cell.getStyle('padding-left')
			});
		}, this);

		this.ghost = this.getClone(new Event, element);
		this.ghost.inject(this.element, 'after');

	},

	reset: function(){

		this.parent();

		this.ghost.destroy();

	},

	_getOffsetSize: function(cell, vertical){
		var keys = vertical ? ['y', 'top', 'bottom'] : ['x', 'left', 'right'];
		return cell.getSize()[keys[0]] 
		- cell.getStyle('padding-'+keys[1]).toInt() 
		- cell.getStyle('padding-'+keys[2]).toInt();
	}

});

Element.implement({

	sortable: function(options){

		if(!this.$sortable) this.$sortable = this.get('tag') == 'tbody' ? new Table.Sortable(this, options) : new Drag.Sortable(this, options);

		return this.$sortable;

	}

});