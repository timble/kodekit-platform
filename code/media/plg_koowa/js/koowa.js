/**
 * @version		$Id$
 * @category    Koowa
 * @package     Koowa_Media
 * @subpackage  Javascript
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Koowa global namespace
 *
 * @package     Koowa_Media
 * @subpackage	Javascript
 */
var Koowa = {
	version: '0.7'
};

/* Section: Functions */
function $get(key, defaultValue) {
	return location.search.get(key, defaultValue);
}	

/* Section: Classes */

/**
 * Form class
 *
 * @package     Koowa_Media
 * @subpackage	Javascript
 */
KForm = 
{	
	addField: function(name, value)
	{
		var el = document.createElement('input');
		el.setAttribute('name', name)
		el.setAttribute('value', value)
		el.setAttribute('type', 'hidden');
		document.adminForm.appendChild(el);	
	},
	
	/**
	 * Submit the grid's form
	 *
	 * @param	Method	[get|post]
	 */
	submit: function(action)
	{
		var form = document.adminForm;
		form.method.value = action.toLowerCase();
		form.submit();
	}
} 
 
/**
 * Grid class
 *
 * @package     Koowa_Media
 * @subpackage	Javascript
 */
KGrid = 
{
	order: function (id, value) 
	{
		var form = document.adminForm;
		form.id.value= id;
		form.order_change.value	= value;
		form.action.value = 'order';
		form.submit();
	},
	
	access: function (id, value) 
	{
		var form = document.adminForm;
   	 	cb = eval( 'form.' + id );
    	if (cb) 
    	{
        	for (i = 0; true; i++) {
            	cbx = eval('form.cb'+i);
            	if (!cbx) break;
            	cbx.checked = false;
        	} 
        	
        	cb.checked = true;
        	form.access.value = value;
        	form.action.value = action;
			form.submit();
    	}
	},
	
	action: function(action, id)
	{
		var form = document.adminForm;
   	 	cb = eval( 'form.' + id );
    	if (cb) 
    	{    		
    		for (i = 0; true; i++) {
            	cbx = eval('form.cb'+i);
            	if (!cbx) break;
            	cbx.checked = false;
        	} 
        	
        	cb.checked = true;
        	form.action.value = action;
			form.submit();
    	}
	},
	
	/**
	 * Find the first selected checkbox id in the grid
	 *
	 * @return 	integer	The item's id or false if no item is selected
	 */
	getFirstSelected: function()
	{
		var inputs = $(document.adminForm).getElements('input[name^=id]');
		for (var i=0; i < inputs.length; i++) {
		   if (inputs[i].checked) {
		      return inputs[i].value;
		   }
		}
	}
}

/**
 * Query class
 *
 * @package     Koowa_Media
 * @subpackage	Javascript
 */
KQuery = new Class({
	
	toString: function() 
	{
		var result = [];
		
		for (var key in this) 
		{
			// make sure it's not a function
			if (!(this[key] instanceof Function)) 
			{
				// we only go one level deep for now
				if(this[key] instanceof Object) 
				{
					for (var subkey in this[key]) {
						result.push(key + '[' + subkey + ']' + '=' + this[key][subkey]);
					}
				} else {
					result.push(key + '=' + this[key]);
				}
			}
		}
		
		return result.join('&');
	}
});


/**
 * Overlay class
 *
 * @package     Koowa_Media
 * @subpackage	Javascript
 */
var KOverlay = Ajax.extend ({

	element : null,
	
	options: 
	{
		method      : 'get',
		evalScripts : true,
		evalStyles  : true
	},
	
	initialize: function(element, options)
    {
		this.element = $(element); 
        this.parent(element.getAttribute('href'), Json.evaluate(options));
        
        this.request();

        if (this.options.initialize) this.options.initialize.call(this);
    },
    
    onComplete: function()
    {
    	var element = new Element('div').setHTML(this.response.text);
    		
    	scripts = element.getElementsBySelector('script[type=text/javascript]');
    	scripts.each(function(script) {
    		if (this.options.evalScripts) {
    			new Asset.javascript(script.src, {id: script.id });
    		}
			script.remove();
		}.bind(this));
    	
    	this.element.replaceWith(element.getElement('#'+this.element.id));
    }
});


/**
 * String class
 *
 * @package     Koowa_Media
 * @subpackage	Javascript
 */
String.extend(
{
	get : function(key, defaultValue)
	{
		if(key == "") return;
	
		var uri   = this.parseUri();
		if($defined(uri['query'])) 
		{
			var query = uri['query'].parseQueryString();
			if($defined(query[key])) {
				return query[key]
			}
		}
		
		return defaultValue;
	},

	parseUri: function()
	{
		var bits = this.match(/^(?:([^:\/?#.]+):)?(?:\/\/)?(([^:\/?#]*)(?::(\d*))?)((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[\?#]|$)))*\/?)?([^?#\/]*))?(?:\?([^#]*))?(?:#(.*))?/);
		return (bits)
			? bits.associate(['uri', 'scheme', 'authority', 'domain', 'port', 'path', 'directory', 'file', 'query', 'fragment'])
			: null;
	},
	
	// backported from Mootools 1.2.3
	parseQueryString: function()
	{
		//var vars = this.split(/[&;]/), res = {};
		var vars = this.split(/[&;]/), res = new KQuery;
		if (vars.length) vars.each(function(val){
			var index = val.indexOf('='),
				keys = index < 0 ? [''] : val.substr(0, index).match(/[^\]\[]+/g),
				value = decodeURIComponent(val.substr(index + 1)),
				obj = res;
			keys.each(function(key, i){
				var current = obj[key];
				if(i < keys.length - 1)
					obj = obj[key] = current || {};
				else if($type(current) == 'array')
					current.push(value);
				else
					obj[key] = $defined(current) ? [current, value] : value;
			});
		});
		return res;
	},

	// backported from Mootools 1.2.3
	cleanQueryString: function(method)
	{
		return this.split('&').filter(function(val){
			var index = val.indexOf('='),
			key = index < 0 ? '' : val.substr(0, index),
			value = val.substr(index + 1);
			return method ? method.run([key, value]) : $chk(value);
		}).join('&');
	}
});