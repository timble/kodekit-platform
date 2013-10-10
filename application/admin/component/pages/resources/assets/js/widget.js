/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/*
---

name: Widget.js

description: Transforms boxes into widgets, that can close/open and later be draggable

license: MIT-style license.

author: Stian Didriksen <stian@nooku.org>

requires: [Fx.Slide]

provides: Widget

...
*/

var Widget = new Class({

	Extends: Fx.Slide,

	options: {
		cookie: false,
		classes: ['widget-open', 'widget-closed'],
		duration: 300,
	},
	
	initialize: function(el, options){
		
		this.widget = el;
		wrap   = el.getChildren();
	
		this.handle = wrap.shift();
		
		//The arrow handle
		new Element('span', {'class': 'arrow'}).inject(this.handle, 'top');

		this.parent(new Element('div', {style: 'overflow:hidden'}).injectInside(el).adopt(wrap), options);
		
		if(this.options.cookie) this.options.cookie = new Hash.Cookie(this.options.cookie, {duration: 3600, path: window.location.path});
		
		var cookie = this.handle.get('text').toLowerCase().replace(' ', '_');
		if(this.options.cookie.get(cookie) || (el.hasClass('widget-closed') && !this.options.cookie.has(cookie))) {
			this.hide().onToggle(true);
		} else {
			el.removeClass('widget-closed');
		}

		this.handle.addEvent('click', function(){
			
			this.toggle();

			this.onToggle(this.open);
			
		}.bind(this));
	},
	
	onToggle: function(open){
		
		if(this.options.cookie) this.options.cookie.set(this.handle.get('text').toLowerCase().replace(' ', '_'), open);
		
		var swap = [this.options.classes[open ? 0 : 1], this.options.classes[open ? 1 : 0]];
		
		this.widget
					.removeClass(this.options.classes[open ? 0 : 1])
					.addClass(this.options.classes[open ? 1 : 0]);
	}

});

Element.implement({

	widget: function(options){
		new Widget(this, options);
		return this;
	}

});