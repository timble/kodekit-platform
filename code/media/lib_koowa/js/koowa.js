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
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Media
 * @subpackage  Javascript
 */
if(!Koowa) var Koowa = {};
Koowa.version = 0.7;

/* Section: Functions */
var $get = function(key, defaultValue) {
    return location.search.get(key, defaultValue);
}   

/* Section: onDomReady */
window.addEvent('domready', function() {
    $$('.submitable').addEvent('click', function(e){
        e = new Event(e);
        new Koowa.Form(Json.evaluate(e.target.getProperty('rel'))).submit();
    });
    
    $$('.-koowa-grid').each(function(grid){
        new Koowa.Grid(grid);
});
});

/* Section: Classes */

/**
 * Creates a 'virtual form'
 * 
 * @param   json    Configuration:  method, url, params, formelem
 * @example new KForm({method:'post', url:'foo=bar&id=1', params:{field1:'val1', field2...}}).submit();
 */
Koowa.Form = new Class({
    
    initialize: function(config) {
        this.config = config;
        if(this.config.element) {
            this.form = $(document[this.config.element]);
        } 
        else {
            this.form = new Element('form', {
                name: 'dynamicform',
                method: this.config.method,
                action: this.config.url
            });
            this.form.injectInside($(document.body));
        }
    },
    
    addField: function(name, value) {
        var elem = new Element('input', {
            name: name,
            value: value,
            type: 'hidden'
        });
        elem.injectInside(this.form);
        return this;
    },
    
    submit: function() {
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
 * @subpackage  Javascript
 */
Koowa.Grid = new Class({

    initialize: function(element){
        
        this.element    = $(element);
        this.form       = this.element.match('form') ? this.element : this.element.getParent('form');
        this.toggles    = this.element.getElements('.-koowa-grid-checkall');
        this.checkboxes = this.element.getElements('.-koowa-grid-checkbox');
        
        var self = this;
        this.toggles.addEvent('change', function(event){
            if(event) self.checkAll(this.get('checked'));
        });
        
        this.checkboxes.addEvent('change', function(event){
            if(event) self.resetCheckAll();
        });
    },
    
    checkAll: function(value){

        var changed = this.checkboxes.filter(function(checkbox){
            return checkbox.get('checked') !== value;
        });

        this.checkboxes.set('checked', value);

        changed.fireEvent('change');

    },
    
    uncheckAll: function(){

        var total = this.checkboxes.filter(function(checkbox){
        	return checkbox.get('checked') !== false ;
        }).length;

        this.toggles.set('checked', this.checkboxes.length === total);
        this.toggles.fireEvent('change');

    }
});
    /**
     * Find all selected checkboxes' ids in the grid
     *
     * @return  array   The items' ids
     */
Koowa.Grid.getAllSelected = function() {
        var result = new Array;
        var inputs = $$('input[class^=-koowa-grid-checkbox]');
        for (var i=0; i < inputs.length; i++) {
           if (inputs[i].checked) {
              result.include(inputs[i]);
           }
        }
        return result;
};
Koowa.Grid.getIdQuery = function() {
        var result = new Array();
        $each(this.getAllSelected(), function(selected){
            result.include(selected.name+'='+selected.value);
        });
        return result.join('&');
};

/**
 * Query class
 *
 * @package     Koowa_Media
 * @subpackage  Javascript
 */
Koowa.Query = new Class({
    
    toString: function() {
        var result = [];
        
        for (var key in this) {
            // make sure it's not a function
            if (!(this[key] instanceof Function)) {
                // we only go one level deep for now
                if(this[key] instanceof Object) {
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
 * @subpackage  Javascript
 */
Koowa.Overlay = new Class({
	Extends: Request,
    element : null,
    
    options: {
        method      : 'get',
        evalScripts : true,
        evalStyles  : true,
        
        onComplete: function() {
            var element = new Element('div', {html: this.response.text});
            element.getElement('[id='+this.element.id+']').replaces(this.element);
            if (this.options.evalScripts) {
                scripts = element.getElementsBySelector('script[type=text/javascript]');
                scripts.each(function(script) {
                    new Asset.javascript(script.src, {id: script.id });
                    script.remove();
                }.bind(this))
            }
            
            if (this.options.evalStyles) {
                styles  = element.getElementsBySelector('link[type=text/css]');
                styles.each(function(style) {
                    new Asset.css(style.href, {id: style.id });
                    style.remove();
                }.bind(this))
            }
        }
    },
    
    initialize: function(element, options) {
        if(typeof options == 'string') {
            var options = Json.evaluate(options);
        }
        
        this.element = $(element); 
        this.options.url = element.getAttribute('href'); 
        this.parent(options);
        
        this.send();
    }
});


/**
 * String class
 *
 * @package     Koowa_Media
 * @subpackage  Javascript
 */
String.extend({
    get : function(key, defaultValue) {
        if(key == "") return;
    
        var uri   = this.parseUri();
        if($defined(uri['query'])) {
            var query = uri['query'].parseQueryString();
            if($defined(query[key])) {
                return query[key];
            }
        }
        
        return defaultValue;
    },

    parseUri: function() {
        var bits = this.match(/^(?:([^:\/?#.]+):)?(?:\/\/)?(([^:\/?#]*)(?::(\d*))?)((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[\?#]|$)))*\/?)?([^?#\/]*))?(?:\?([^#]*))?(?:#(.*))?/);
        return (bits)
            ? bits.associate(['uri', 'scheme', 'authority', 'domain', 'port', 'path', 'directory', 'file', 'query', 'fragment'])
            : null;
    }
});