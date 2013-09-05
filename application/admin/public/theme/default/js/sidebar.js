/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

var Koowa = Koowa || {};

Koowa.Sidebar = new Class({

    Implements: Options,

    options: {
        sidebar: '.sidebar',
        target: '.sidebar-inner',
        observe: false, //Pass a css selector if the content area isn't the sidebars next sibling DOM element
        affix: false, //If Bootstrap's Affix plugin is loaded, this option will make the sidebar scroll with the view
        minHeight: 200,
        scrollToActive: false,
        setObserveHeight: false
    },

    initialize: function(options){

        this.setOptions(options);

        this.sidebar = document.getElement(this.options.sidebar);

        this.target   = this.sidebar.getElement(this.options.target);
        this.siblings = this.target.getAllNext();

        this.observe = this.options.observe ? document.getElement(this.options.observe) : this.sidebar.getNext();

        if(this.options.setObserveHeight) {
            this.observe.setStyle('height', window.getHeight() - this.observe.getPosition().y);
            this.observe.setStyle('overflow', 'hidden');
        }

        //Setup the inner container
        this.target.setStyle('overflow', 'auto');

        //This offset we can assume is static, so we only calculate it once
        this.offset = this.target.getPosition().y - this.observe.getPosition().y;

        //Take measures against padding
        this.offset += this.target.getStyle('padding-top').toInt() + this.target.getStyle('padding-bottom').toInt();

        //Check if the height is sufficient
        if(this.options.affix) this.options.affix = this.observe.getDimensions().height > window.getHeight();

        if(this.options.affix && this.observe.getDimensions().height) {
            jQuery(this.sidebar).css('left', this.sidebar.getCoordinates().left).affix({
                offset: {
                    top: jQuery.proxy(function(){
                        return this.sidebar.getParent().getCoordinates().top;
                    }, this),
                    bottom: jQuery.proxy(function(){
                        return document.getScrollHeight() - this.observe.getCoordinates().bottom;
                    }, this)
                }
            });
        }

        window.addEvent('resize', this.setHeight.bind(this));

        window.fireEvent('resize');
    },

    setHeight: function(){

        if(this.options.setObserveHeight) {
            this.observe.setStyle('height', window.getHeight() - this.observe.getPosition().y);
        }

        //This offset we can't assume never changes
        var offset = 0;
        if(this.siblings) {
            this.siblings.each(function(sibling){
                offset += sibling.getHeight();
            });
        }

        var height = this.observe.getDimensions().height - this.offset - offset;
        if(this.options.affix) {
            height = Math.min(window.getHeight() - this.offset - offset, height);
        }
        this.target.setStyle('height', Math.max(height, this.options.minHeight));

        if(this.options.scrollToActive) {
            var fx = new Fx.Scroll(this.target),
                selected = this.target.getElement('.active'),
                what = 'toElementEdge' in fx ? 'toElementEdge' : 'toElement';
            if(selected) fx[what](selected);
        }
    }
});


window.addEvent('domready', function(){
    if(document.id('panel-sidebar') && document.id('panel-content')) {
        new Koowa.Sidebar({
            sidebar: '#panel-sidebar',
            observe: '#panel-content',
            target: '.scrollable',
            minHeight: 40,
            scrollToActive: true,
            setObserveHeight: true
        });
    }
    if(document.id('panel-inspector') && document.id('panel-content')) {
        new Koowa.Sidebar({
            sidebar: '#panel-inspector',
            observe: '#panel-content',
            target: '.scrollable',
            minHeight: 40
        });
    }
    if(document.getElement('#panel-content .sidebar') && document.getElement('#panel-content .form-body')) {
        new Koowa.Sidebar({
            sidebar: '#panel-content .sidebar',
            observe: '#panel-content .form-body',
            target: '.scrollable',
            setObserveHeight: true
        });
    }
});