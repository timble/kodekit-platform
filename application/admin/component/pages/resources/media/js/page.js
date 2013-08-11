/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

if (!Pages) {
    var Pages = {};
}

Pages.Page = new Class({

	Implements: Options,

	options: {
		
		sidebar: 'components-inner',
		panel: 'types',
		active: '',
		type: ''
		
	},

	initialize: function(options){

		this.setOptions(options);

		this.togglers  = $(this.options.sidebar).getElements('a');
		this.types     = $(this.options.panel).getChildren();
		this.active    = this.options.active;
		this.type      = this.options.type;
		this.parents   = [];

		this.togglers.each(function(toggle){
			
			var component_name = toggle.getProperty('data-component'), type = this.types.filter(function(type){
				return type.getProperty('data-component') == this;
			}, component_name)[0];
			
			if(type.getProperty('data-component') != this.active) {
				type.setStyle('display', 'none');
			} else {
				toggle.addClass('active selected');
			}

			toggle.addEvent('click', function(event){

				new Event(event).stop();

				this.types.setStyle('display', 'none');
				type.setStyle('display', 'block');
				toggle.getParent().getParent().getElements('a.active').removeClass('active');
				toggle.addClass('active');

			}.bindWithEvent(this));

		}, this);
		
		if(this.type == 'redirect') {
		    var page = $('page-link-id');
            var url  = $('page-link-url');
            var type = $('page-link-type').getElement('input[name=link_type]:checked');
            
            page.setStyle('display', type.value == 'id' ? 'block' : 'none');
            url.setStyle('display', type.value == 'url' ? 'block' : 'none');
            
            $('page-link-type').getElements('input[name=link_type]').each(function(input) {
		        input.addEvent('click', function() {
		            page.setStyle('display', input.value == 'id' ? 'block' : 'none');
		            url.setStyle('display', input.value == 'url' ? 'block' : 'none');
		        });
		    }.bind(this));
		}
		
		var menu = $$('select[name=pages_menu_id]');
		if(menu) {
		    menu = menu.shift();
		    menu.addEvent('change', this.updateParent.bind(this));
		    
		    this.parents[menu.value] = $('pages-parent').get('html');
		}
	},
	
	updateParent: function(event) {
	    if(!(event.target.value in this.parents)) {
	        var url = $('page-form').get('action').replace(/([?&])menu=\d+/g, '$1menu='+event.target.value).replace(/[&?]id=\d+/g, '');
	        var req = new Request.HTML({
	            async: false,
	            evalScripts: false,
	            onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript) {
	                var response = responseElements.filter('#pages-parent').shift();
                    this.parents[event.target.value] = response.get('html');
                }.bind(this)
	        }).get(url);
	    }
	    
	    $('pages-parent').set('html', this.parents[event.target.value]);
	}
});