/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Javascript loader
 *   
 * @author    	Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Template
 */

window.addEvent('domready', function(){
	if(Element.chromatable) {
	    $$('form.-koowa-grid table').chromatable();
	    
	    // If debug bar present, add chromatable support to it
	    if($('debug')) $$('#debug .adminlist').chromatable();
	}
	
	var sidebar = $('sidebar');
	if(sidebar) {
	    sidebar.getElements('li > a').addEvent('click', function(e){
	        this.getParent('ul').getElements('.active').removeClass('active');
	        
	        $$(this, this.getParent()).addClass('active');
	    });
	}
	
	//This is not the Konami code
	var b = [], a = "38,38,40,40,37,39,37,39,66,65";
	window.addEvent('keydown', function(c){
	    b.push(c.code);
        if (b.toString().indexOf(a) >= 0) {
            b = [];
            document.body.setStyles({WebkitTransform: 'scaleX(-1)', MozTransform: 'scaleX(-1)', transform: 'scaleX(-1)'});
        }
	});
});