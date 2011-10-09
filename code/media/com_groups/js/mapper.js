/*
---

script: Mappethis.Raphael.js

description: Lets you click and this.drag to map relations between two lists

license: MIT-style license.

requires:
- /Native
- /Raphael

provides: [Mapper]

...
*/

var Mapper = new Class({

	Implements: Options,

	drag: false,
	
	drop: false,
	
	colors: [],
	
	options: {
		holder: 'holder',
		dimensions: {
			circle: 5
		},
		points: {
			from: [],
			to: []
		}
	},

	initialize: function(options){
console.log(options);
		this.setOptions(options);

		var s = $(this.options.holder).getSize().size,
			r = Raphael(this.options.holder, s.x, s.y);

		this.size = s;
		this.E = {x: 0, y: 0};
		this.Raphael = r;

   		var p = {
	   			x: [100, 100+(s.x*0.3), (s.x-100)-(s.x*0.3), s.x-100],
	   			y: [20, 60, 100, 140, 180, 220, 260, 300]
	   		},
	   		m = [20,20,20,20,20,20,20,140],
	   		c = 20;

		this.options.points.to.each(function(point, i){
			
			var pos = this.getCoordinatesTo(point);
			this.Raphael.circle(pos.x, pos.y, this.options.dimensions.circle).attr({fill: "white", "stroke": "lightgray", "stroke-width": 1});
			
			point.addEvents({
				mouseenter: function(point){
					if(!this.drag) return;
					point.getElement('h1').tween('color', this.drag.color);
					this.drop = point;
				}.pass(point, this),
				mouseleave: function(point){
					if(!this.drag) return;
					point.getElement('h1').tween('color', this.color);
					this.drop = false;
				}.pass(point, this)
			});

		}.bind(this));

	   	var curves = [
	   			//this.curve(p.x[0], p.y[6], p.x[1], p.y[6], p.x[2], m[6], p.x[3], m[6])
			],
			groups = ["1","2","3","4","5","6"];
		this.curves = curves;
		this.groups = groups;
		
		this.options.points.from.each(function(point, i){
			var coor = point.getCoordinates($(this.options.holder)), pos = {x: coor.right - this.options.dimensions.circle, y: coor.top + (coor.height / 2)}, dest = this.getCoordinatesFromTo(point), factor = (dest.x - pos.x) * 0.3, handles = [{x: dest.x - factor, y: pos.y}, {x: pos.x + factor, y: dest.y}], curve = this.curve(pos.x, pos.y, handles[0].x, handles[0].y, handles[1].x, handles[1].y, dest.x, dest.y, point.getElement('input'));

			this.curves.include(curve);

			var h1 = $$(point, this.getElementFromTo(point)).getElement('h1'), color = h1[0].getStyle('color');

			//@TODO optimize this
			this.color = color;

			point.addEvents({
				mouseenter: function(color, Mapper){
					if(Mapper.drag) return;
					this.tween('color', color);
				}.pass([this.colors[i], this], h1),
				mouseleave: function(color, Mapper){
					if(Mapper.drag) return;
					this.tween('color', color);
				}.pass([color, this], h1),
				mousedown: function (event, Mapper, points, color) {
					if(points[1]) points[1].tween('color', color);
					points[0].store('hilite', color);
				    Mapper.drag = this;
				    Mapper.points = points;
				    event = new Event(event);
				    Mapper.E.x = event.client.x;
				    Mapper.E.y = event.client.y;
				}.bindWithEvent(curve, [this, h1, color])
			});
		}.bind(this));
		
		$(this.options.holder).getParent().addEvents({
			mousemove: function(event){
				if(!this.drag) return;
				var page = event.page, coor = $(this.options.holder).getCoordinates(), pos = {x: page.x - coor.left, y: page.y - coor.top};
				this.drag.position(pos, this);
			}.bind(this),
			mouseup: function(){
				if(this.drag) {
					if(!this.drop) return this.cancel();
					var pos = this.getCoordinatesTo(this.drop);
					this.drag
							.position(pos, this)
							.input.set('value', this.drop.get('id').split('-').getLast());
					this.points[1] = this.drop.getElement('h1');
					this.points.tween('color', this.color);
					this.drag = false;
					this.drop = false;
				}
			}.bind(this)
			//mouseleave: this.cancel.bind(this)
		});
		window.addEvent('blur', this.cancel.bind(this));
	},
	
	createPath: function(pos, dest){
		var factor = (dest.x - pos.x) * 0.3, handles = [{x: dest.x - factor, y: pos.y}, {x: pos.x + factor, y: dest.y}];
		return [["M", pos.x, pos.y], ["C", handles[0].x, handles[0].y, handles[1].x, handles[1].y, dest.x, dest.y]];
	},
	
	cancel: function(){
		if(this.drag)
		{
			this.points[0].tween('color', this.points[0].retrieve('hilite'));
			this.drag = this.drag.cancel(this);
		}
	},
	
	getElementFromTo: function(from){
		var value = from.getElement('input').get('value'), dest = $$(this.options.points.to).filter('[id$=-'+value+']')[0];
		if(value < 1 || !dest) return $$(this.options.points.to)[0];
		return dest;
	},
	
	getCoordinatesFromTo: function(from){
		return this.getCoordinatesTo(this.getElementFromTo(from));
	},
	
	getCoordinatesTo: function(point, fail){
		if(!point) return fail;
		var coor = point.getCoordinates($(this.options.holder));
		return {x: coor.left, y: coor.top + (coor.height / 2)};
	},

	curve: function(x, y, ax, ay, bx, by, zx, zy, input) {
		var path = this.createPath({x:x, y:y}, {x:zx, y:zy}),
			controls = this.Raphael.set(
				this.Raphael.circle(x, y, 3).attr({"fill-opacity": 0, stroke: "lightgray", "stroke-opacity": 1, "stroke-width": 1}),
				this.Raphael.path(path).attr({stroke: this.colors.include(Raphael.getColor()).getLast(), "stroke-width": 2, "stroke-opacity": .9}),
				this.Raphael.circle(zx, zy, 3).attr({fill: "lightgray",  "stroke-opacity": 0, "stroke-width": 3})
			);

		controls.input = input;

		controls.color = this.colors.getLast();

		controls.cancel = function(Mapper){
			var pos = {x: this[0].attrs.cx, y: this[0].attrs.cy};
			this[2].attr({cx: pos.x, cy: pos.y});
			this[1].attr({path: Mapper.createPath(pos, pos)});
			return false;
		}

		controls.position = function(dest, Mapper){
			this[2].attr({cx: dest.x, cy: dest.y});
			this[1].attr({path: Mapper.createPath({x: this[0].attrs.cx, y: this[0].attrs.cy}, dest)});
			return this;
		}.bind(controls);

		return controls;
	}
});