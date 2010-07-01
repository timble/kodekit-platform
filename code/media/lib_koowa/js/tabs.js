/**
 * @version		$Id: koowa.js 1051 2009-07-13 22:08:57Z Johan $
 * @category    Koowa
 * @package     Koowa_Media
 * @subpackage  Javascript
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */
 
/**
 * Tabs behavior
 *
 * @package     Koowa_Media
 * @subpackage	Javascript
 */
var KTabs = new Class({

    getOptions: function()
    {
        return {

            display: 0,

            onActive: function(title, description){
                description.setStyle('display', 'block');
                title.addClass('open').removeClass('closed');
            },

            onBackground: function(title, description){
                description.setStyle('display', 'none');
                title.addClass('closed').removeClass('open');
            }
        };
    },

    initialize: function(dlist, options)
    {
    	this.dlist = $(dlist);
        this.setOptions(this.getOptions(), options);
        this.titles = this.dlist.getElements('dt');
        this.descriptions = this.dlist.getElements('dd');
        this.content = new Element('div').injectAfter(this.dlist).addClass('current');
          
        if(this.options.height) {
        	this.content.setStyle('height', this.options.height);
        }

        for (var i = 0, l = this.titles.length; i < l; i++)
        {
            var title = this.titles[i];
            var description = this.descriptions[i];
            title.setStyle('cursor', 'pointer');
            title.addEvent('click', this.display.bind(this, i));
            description.injectInside(this.content);
        }
        
        if(Cookie.get('ktabs.' + dlist)) {
        	this.options.display = Cookie.get('ktabs.' + dlist);
        }
    
        if ($chk(this.options.display)) {
        	this.display(this.options.display);
        }

        if (this.options.initialize) {
        	this.options.initialize.call(this);
        }
    },

    hideAllBut: function(but)
    {
        for (var i = 0, l = this.titles.length; i < l; i++){
            if (i != but) this.fireEvent('onBackground', [this.titles[i], this.descriptions[i]])
        }
    },

    display: function(i)
    {
    	Cookie.set('ktabs.' + this.dlist.getProperty('id'), i);
    	this.hideAllBut(i);
        this.fireEvent('onActive', [this.titles[i], this.descriptions[i]])
    }
});

KTabs.implement(new Events, new Options);