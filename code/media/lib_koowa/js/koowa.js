/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Media
 * @subpackage  Javascript
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Koowa global namespace
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package     Nooku_Media
 * @subpackage  Javascript
 */
var Koowa = {
	version: '0.7'
};

/* Section: Functions */
function $get(key, defaultValue) {
	return location.search.get(key, defaultValue);
}	

/* Section: onDomReady */
window.addEvent('domready', function() {
	$$('.submitable').addEvent('click', function(e){
		e = new Event(e);
		new KForm(Json.evaluate(e.target.getProperty('rel'))).submit();
	});
});

/* Section: Classes */

/**
 * Creates a 'virtual form'
 * 
 * @param	json	Configuration:  method, url, params, formelem
 * @example new KForm({method:'post', url:'foo=bar&id=1', params:{field1:'val1', field2...}}).submit();
 */
KForm = new Class({
	
	initialize: function(config)
	{
		this.config = config;
		if(this.config.formelem) {
			this.form = $(eval('document.'+this.config.formelem));
		} 
		else 
		{
			this.form = new Element('form', {
				name: 'dynamicform',
				method: this.config.method,
				action: this.config.url
			});
			this.form.injectInside($E('body'));
		}
	},
	
	addField: function(name, value)
	{
		var elem = new Element('input', {
			name: name,
			value: value,
			type: 'hidden'
		});
		elem.injectInside(this.form);
		return this;
	},
	
	submit: function()
	{
		$each(this.config.params, function(value, name){
			this.addField(name, value);
		}.bind(this));
		this.form.submit();
	}
});


/**
 * Grid class
 *
 * @package     Koowa_Media
 * @subpackage	Javascript
 */
KGrid = 
{
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
	 * Find all selected checkboxes' ids in the grid
	 *
	 * @return 	array	The items' ids
	 */
	getAllSelected: function()
	{
		var result = new Array;
		var inputs = $$('input[name^=id]');
		for (var i=0; i < inputs.length; i++) {
		   if (inputs[i].checked) {
		      result.include(inputs[i].value);
		   }
		}
		return result;
	},
	
	getIdQuery: function()
	{
		var result = new Array();
		$each(this.getAllSelected(), function(value){
			result.include('id[]='+value);
		});
		return result.join('&');
	},
	
	/**
	 * Find the first selected checkbox id in the grid
	 *
	 * @return 	integer	The item's id or false if no item is selected
	 */
	getFirstSelected: function()
	{
		var all = this.getAllSelected();
		if(all.length) return all[0];
	}
};

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
    		
    	if (this.options.evalScripts) 
    	{
    		scripts = element.getElementsBySelector('script[type=text/javascript]');
    		scripts.each(function(script) {
    			new Asset.javascript(script.src, {id: script.id });
    			script.remove();
			}.bind(this))
    	}
    	
    	if (this.options.evalStyles) 
    	{
    		styles  = element.getElementsBySelector('link[type=text/css]');
    		styles.each(function(style) {
    			new Asset.css(style.href, {id: style.id });
    			style.remove();
    		}.bind(this))
    	}
    	
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
				return query[key];
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