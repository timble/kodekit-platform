/** $Id$ */

var Terms = Ajax.extend({

	element : null,
	form    : null,
	
	options: 
	{
		method      : 'post',
		action      : '',
		evalScripts : false
	},
	
	initialize: function(element, options)
    {
		this.element = $(element);
		this.form    = this.element.getElement('form');
		this.parent(this.form.getProperty('action'), Json.evaluate(options));
		
		this.onComplete();
		
        if (this.options.initialize) this.options.initialize.call(this);
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
    	this.url = [this.url, 'id='+data].join('&');
    	this.setHeader('X-HTTP-Method-Override', 'delete');
    	this.request();
    },
    
    _actionAdd: function(data)
    {
    	data = [data, this.form.toQueryString()].join('&');
    	this.request(data);
    },
    
    request: function(data)
    {
    	data = data || this.options.data;
		switch($type(data)) {
			case 'element': data = $(data).toQueryString(); break;
			case 'object' : data = Object.toQueryString(data); break;
		}
		
    	this.parent(data);
    },
    
    onComplete: function()
    { 
    	if(typeof this.response !== 'undefined') {
        	this.element.empty().setHTML(this.response.text);
        }
    	
    	this.form = this.element.getElement('form');
        this.form.addEvent('submit', function(e) {
   			new Event(e).stop();
   			this.execute('add');
  		}.bind(this));
    }    
});

window.addEvent('domready', function() {
	Terms = new Terms('terms-panel');
});