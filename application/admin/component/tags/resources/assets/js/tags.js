/**
 * @package     Nooku_Components
 * @subpackage  Tags
 * @copyright	Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

(function(){
var $ = document.id;

this.Tags = new Class({
	Extends: Request,
	element : null,
	form    : null,
	
	options: {
		action      : '',
		evalScripts : false,
		
        onComplete: function() {
			if (this.response && this.response.text) {
				this.element.empty().set('html', this.response.text);
				new Tags(this.element);
			} else {
				this.get(this.url);
			}
        }
	},
	
	initialize: function(element, options) {
		options = options || {};
		this.element = document.id(element);
		var that = this;
		this.element.getElements('a[data-action]').addEvent('click', function(e) {
			e.stop();
			that.execute(this.get('data-action'), this.get('data-id'));
		});
		this.form = this.element.getElement('form');
		this.url = this.form.getProperty('action');
		
		options.url = this.url;
		this.parent(options);
		
		this.form.addEvent('submit', function(e) {
			e.stop();
			this.execute('add');
      	}.bind(this));
    },
    
    execute: function(action, data)
    {
    	var method = '_action'+action.capitalize();
    	
    	if($type(this[method]) == 'function') 
    	{
    		this.options.action = action;
    		this[method].call(this, data);
    	}
    },
    
    _actionDelete: function(data)
    {
    	this.options.url = [this.options.url, 'id='+data].join('&');
    	
    	this.delete(this.form);
    },
    
    _actionAdd: function(data)
    {
    	this.post(this.form);
    }
});
})();

window.addEvent('domready', function() {
	new Tags('tags-list');
});