/** $Id$ */

/** Requires */
new Asset.javascript('../media/com_tags/js/ajax.js', {id: 'TagsAjax'});

/** Tagging Singleton */
Koowa.Tags = {
   
	setup: function()
    {
		$('tags_tags_form').addEvent('submit', function(e) {
  			new Event(e).stop();
  			Koowa.Tags.add();
 		});
    },
    
    add   : function() 
    {
    	form   = $('tags_tags_form');
    	action = 'add';
    	data   = form.toQueryString();
    	
    	new Koowa.Ajax(form.getAttribute('action'), {
			method: 'post',
			data: data+'&row_id='+row_id+'&table_name='+table_name+'&action='+action,
			update: 'tags_panel',
			onComplete: Koowa.Tags.setup,
			statusElem: 'tags_panel'
		}).request();
    },
    
    delete: function(element)
    {
    	form   = $('tags_tags_form');
    	data   = element.rel.cleanQueryString();
    	action = 'delete'; 
    	
    	new Koowa.Ajax(form.getAttribute('action'), {
			method: 'post',
			data: data+'&row_id='+row_id+'&table_name='+table_name+'&action='+action,
			update: 'tags_panel',
			onComplete: Koowa.Tags.setup,
			statusElem: 'tags_panel'
		}).request();
    },
    
    browse: function()
    {
    	new Koowa.Ajax('index.php?option=com_tags&view=tags&layout=ajax&format=ajax&row_id='+row_id+'&table_name='+table_name, {
    			method: 'get',
    			update: 'tags_panel',
    			onComplete: Koowa.Tags.setup,
    			statusElem: 'tags_panel'
    	}).request();
    }
}

/** Main execution flow */
window.addEvent('domready', function() {
	new Koowa.Tags.browse();
});