/** $Id$ */

/** Requires */
new Asset.javascript('../media/com_tags/js/ajax.js', {id: 'TagsAjax'});

/** Tagging Singleton */
Tags = {
   
	setup: function()
    {
		$('tags_tags_form').addEvent('submit', function(e) {
  			new Event(e).stop();
  			Tags.add();
 		});
    },
    
    add   : function() 
    {
    	form   = $('tags_tags_form');
    	action = 'add';
    	data   = form.toQueryString();
    	
    	new Tags.Ajax(form.getAttribute('action'), {
			method: 'post',
			data: data+'&row_id='+row_id+'&table_name='+table_name+'&action='+action,
			update: 'tags_panel',
			onComplete: Tags.setup,
			statusElem: 'tags_panel'
		}).request();
    },
    
    delete: function(element)
    {
    	form   = $('tags_tags_form');
    	data   = element.rel.cleanQueryString();
    	action = 'delete'; 
    	
    	new Tags.Ajax(form.getAttribute('action'), {
			method: 'post',
			data: data+'&row_id='+row_id+'&table_name='+table_name+'&action='+action,
			update: 'tags_panel',
			onComplete: Tags.setup,
			statusElem: 'tags_panel'
		}).request();
    },
    
    browse: function()
    {
    	new Tags.Ajax('index.php?option=com_tags&view=tags&layout=ajax&format=ajax&row_id='+row_id+'&table_name='+table_name, {
    			method: 'get',
    			update: 'tags_panel',
    			onComplete: Tags.setup,
    			statusElem: 'tags_panel'
    	}).request();
    }
}

/** Main execution flow */
window.addEvent('domready', function() {
	Tags.browse();
});