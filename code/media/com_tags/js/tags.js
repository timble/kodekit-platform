/** $Id$ */

var Tags = Ajax.extend({

	element : null,
	form    : null,
	
	options: 
	{
		method      : 'post',
		evalScripts : false
	},
	
	initialize: function(element, options)
    {
		this.element = $(element);
		this.form    = this.element.getElement('form');
		this.parent(this.form.getAttribute('action'), Json.evaluate(options));
		
		this.onComplete();
		
        if (this.options.initialize) this.options.initialize.call(this);
    },
    
    execute: function(action, data)
    {	
    	this.options.data = data.cleanQueryString()+'&action='+action;
    	this.request();
    },
    
    onComplete: function()
    { 
    	if(typeof this.response !== 'undefined') {
        	this.element.empty().setHTML(this.response.text);
        }
    	
    	form = this.element.getElement('form');
        form.addEvent('submit', function(e) {
   			new Event(e).stop();
   			this.execute('add', form.toQueryString());
  		}.bind(this));
    }    
});

window.addEvent('domready', function() {
	Tags = new Tags('tags-panel');
});