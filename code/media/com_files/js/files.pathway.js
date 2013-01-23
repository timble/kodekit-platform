/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

if (!Files) Files = {};

Files.Pathway = new Class({
	Implements: [Options],
	initialize: function(element, options) {
		this.element = document.id(element);
		
		if (this.element) {
			this.setupElements();
			this.attachEvents();
		}
	},
	wrap: function(title, path, icon, app){
		var result = new Element('li', {
	            text: title,
	            title: title,
	            events: {
	                click: function(){
	                    app.navigate(path);
	                }
	            }
	        });
		
        if(icon) {
            result.grab(new Element('span', {
            	'class': 'divider',
            	html: ''
            }), 'top');
        }
        
        return result;
    },
	setupElements: function() {
		this.element.getParent().setStyle('position', 'relative');
	    
		this.element.setStyles({
	        left: (this.element.getPrevious() ? this.element.getPrevious().getSize().x : 0) + 18,
	        right: this.element.getNext().getSize().x + 18,
	    });
		
		this.element.empty();
		
		this.list = new Element('ul', {'class': 'breadcrumb breadcrumb-resizable'});
		
	    this.element.adopt(this.list);
	    
	    
	},
	attachEvents: function() {
        //Whenever the path changes, the buffer used in the resize handler is outdated, so have to be reattached
        if(this.pathway_resizer) {
            window.removeEvent('resize', this.pathway_resizer);

            this.pathway_resizer = false;
        }

        if(this.list.getChildren().length > 2) {

            var widths = {}, ceil = 0, offset = this.list.getFirst().getSize().x + this.list.getLast().getSize().x;
            this.list.getChildren().each(function(item, i){
                if(item.match(':first-child') || item.match(':last-child')) return;
                var x = item.getSize().x;
                widths[i] = {key: i, value: x};
                ceil += x;
            });

            //Create resize buffer
            var buffer = {}, queue = ceil;
            buffer[ceil] = buffer.max = widths;
            while(queue > 0) {
                --queue;

                var max = {key: null, value: 0}, sizelist = {};
                for (var key in widths){
                    if (widths.hasOwnProperty(key)) {
                        var item = widths[key];
                        if(item.value > max.value) max = item;
                        sizelist[key] = {key: item.key, value: item.value};
                    }
                }
                --sizelist[max.key].value;

                buffer[queue] = sizelist;
                widths = sizelist;
            }

            this.update(buffer, this.element.getSize().x, offset);
            this.element.setStyle('visibility', 'visible');

            this.pathway_resizer = function(){
            	this.update(buffer, this.element.getSize().x, offset)
            }.bind(this);
            
            window.addEvent('resize', this.pathway_resizer);

        } else {
            this.element.setStyle('visibility', 'visible');
        }
	},
	update: function(buffer, width, offset) {
        var index = width - offset, sizes = buffer[index] || buffer.max, last = this.list.getChildren().length - 1;

        this.list.getChildren().each(function(folder, index, siblings) {
            if(index > 0 && index < last) {
                folder.setStyle('width', sizes[index].value);
                if(sizes[index].value <= 48) {
                    folder.removeClass('overflow-ellipsis');
                } else {
                    folder.addClass('overflow-ellipsis');
                }
            }
        });

    }
});
