/** $Id$ */

/** Tagging Singleton */
var Tags = {
   
	initialize: function(options) 
	{
		if (this.options) {
			return this;
		}
		
		this.presets = $merge(this.presets, options)
		this.setOptions(this.presets);
		this.build();
		
		return this;
	},
		
	build: function()
    {
		$('tags_tags_form').addEvent('submit', function(e) {
  			new Event(e).stop();
  			this.add;
 		});
    },
    
    add   : function() 
    {
    	form   = $('tags_tags_form');
    	action = 'add';
    	data   = form.toQueryString();
    	
    	new Ajax(form.getAttribute('action'), {
			method: 'post',
			data: data+'&row_id='+row_id+'&table_name='+table_name+'&action='+action,
			update: 'tags_panel',
			onComplete: this.build,
			statusElem: 'tags-tags-overlay'
		}).request();
    },
    
    delete: function(element)
    {
    	form   = $('tags_tags_form');
    	data   = element.rel.cleanQueryString();
    	action = 'delete'; 
    	
    	new Ajax(form.getAttribute('action'), {
			method: 'post',
			data: data+'&row_id='+row_id+'&table_name='+table_name+'&action='+action,
			update: 'tags_panel',
			onComplete: this.build,
			statusElem: 'tags-tags-overlay'
		}).request();
    }
}

Tags.implement(new Events, new Options);