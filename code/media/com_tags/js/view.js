/** $Id$ */

/** Requires */
new Asset.javascript('../media/com_tags/js/ajax.js', {id: 'TagsAjax'});

/** Tagging Singleton */
var Tags = {
    url: "index.php?option=com_tags&view=tags&layout=ajax",

    // It seems like the problem is, that this call here happens
    // after the ajax call is made (and the user is already redirected)
    addBehaviors: function(){
  		$('tags_tags_form').addEvent('submit', function(e) {alert();
			// Prevent regular submit
			new Event(e).stop();
 		    // send the delete request
    		new TagsAjax(Tags.url, {
				method: 'post',
				data: $('tags_tags_form').toQueryString()+'&'+this.getAttribute('rel'),
				update: 'tags_panel',
				onComplete: Tags.addBehaviors,
				statusElem: 'tags_panel'
			}).request();
 		});
    }
}

/** Main execution flow */
window.addEvent('domready', function() {

	// get the html with the list of tags and add behavior
	new TagsAjax(
		Tags.url+"&layout=ajax&format=ajax&row_id="+row_id+"&table_name="+table_name,
		{
			method: 'get',
			update: 'tags_panel',
			onComplete: Tags.addBehaviors,
			statusElem: 'tags_panel'
		}
	).request();
});