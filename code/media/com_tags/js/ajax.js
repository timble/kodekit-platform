/** $Id$ */

/**
 * Adds a loading icon in statusElem
 */
var TagsAjax = Ajax.extend({

	options: {
		data: null,
		update: null,
		onComplete: Class.empty,
		evalScripts: false,
		evalResponse: false,
		statusElem: null
	},
	
    initialize: function(url, options){
    	//this.statusElem = statusElem;
        this.parent(url, options); //will call the previous initialize;
    },
    
    request: function(data){
    	if(null !== this.options.statusElem) {
    		new Element('div', { 
    			'class': 'ajax-status'
   			}).injectTop(this.options.statusElem);
    	}
    	this.parent(data);
    },
    
    onComplete: function(){
    	$(this.options.statusElem).getElement('.ajax-status').remove();
    	this.parent();
    }
});
