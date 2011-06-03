/**
 * @version		$Id$
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Template
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
  
/**
 * MooTools port of chromatable.js. Make a "sticky" header at the top of the table, 
 * so it stays put while the table scrolls. Enhanced to support table footers as well.
 *
 * Inspiration: chromatable.js by Zachary Siswick
 *   
 * @author    	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Template
 */

var ChromaTable = new Class({

	Implements: [Options, Events],

	options: {
		width: '100%',
		height: '300px'
	},

	initialize: function(table, options){

		this.setOptions(options);
		this.table = table;

		var $uniqueID = this.table.getProperty('id') + 'wrapper', outer = new Element('div', {'class': 'scrolling_outer'}), inner = new Element('div', {id: $uniqueID, 'class': 'scrolling_inner'});

		//Add dimentsions from user or default parameters to the DOM elements
		this.table.setStyles({'width': this.options.width}).addClass("_scrolling");

		this.table.getParent().adopt(
			outer.adopt(
				inner.adopt(this.table)
			)
		);

		//@TODO position relative makes the table overlap the toolbar
		outer.setStyle('position', 'relative');
		inner.setStyles({
			paddingRight:	'17px',
			overflowX:		'hidden',
			overflowY:		'auto',
			height:			this.options.height,
			width:			this.options.width
		});
		
		//Workaround for WebKit bug where borders on <td>s inside hidden parent <tfoot> still show the border
		inner.getElements('tfoot tr > *').setStyle('border-color', 'transparent');


		inner.getElements('tr').each(function(tr){
			var checkbox = tr.getElement('input[type=checkbox]');
			if(!checkbox) return;
			checkbox.addEvent('change', function(tr){
				this.getProperty('checked') ? tr.addClass('selected') : tr.removeClass('selected');
			}.pass(tr, checkbox));
			tr.addEvents({
				dblclick: function(event){
					window.location.href = this.getElement('a').get('href');
				},
				contextmenu: function(event){
					var modal = this.getElement('a.modal');
					if(modal) {
						event.preventDefault();	
						modal.fireEvent('click');
					}
				}
			});
		});
		
        this.thead = inner.getElement('thead');
        this.tfoot = inner.getElement('tfoot');
        
        var styles = {
                position: 'absolute'
            },
        	elements = new Elements,
        	tfoot,
        	thead;
        
        if(this.thead) {
			var thead = this.table.clone()
										.setStyles(styles)
										.empty()
										.addClass('_thead')
										.injectBefore(inner)
										.adopt(
											this.thead.setStyle('position', 'absolute')
										);

            var cloned = this.thead.clone();
            //Disable form elements that can mess up GET and POST requests
            cloned.getElements('input, select, button').set('disabled', 'disabled').removeProperty('name');
            
            elements.include(cloned);
		}
        
		if(this.tfoot) {	
			var tfoot = this.table.clone()
										.setStyle('position', 'absolute')
										.empty()
										.addClass('_tfoot')
										.injectAfter(inner)
										.setStyle(
											'bottom',
											this.tfoot.getSize().y
										).adopt(
											this.tfoot.setStyle('position', 'absolute')
										);
			
			var cloned = this.tfoot.clone();
			//Disable form elements that can mess up GET and POST requests
			cloned.getElements('input, select, button').set('disabled', 'disabled').removeProperty('name');
			
			elements.include(cloned);
		}

		if(elements.length) {
			var styles = {
				position: 'static',
				opacity:  0
			};
			
			this.table.adopt(elements.setStyles(styles));
			$$(thead, tfoot).setStyle('height', '');
		}
		
		// If the width is auto, we need to remove padding-right on scrolling container	
		if (this.options.width == "100%" || this.options.width == "auto") {
			inner.setStyle('padding-right','0px');
		}
		
		this.inner = inner;
		
		//For the zebras to work, there must be at least 2 rows
		if(this.inner.getElements('tbody tr').length < 2) {
		    var fake = new Element('tr', {
		        styles: {visibility: 'hidden'},
		        html: '<td colspan="'+this.inner.getElements('thead tr th').length+'">&nbsp;</td>'
		    });
		    this.inner.getElement('tbody').adopt([fake, fake.clone()]);
		}
		
    	//check to see if the width is set to auto, if not, we don't need to call the resizer function
    	if (this.options.width == "100%" || "auto") {
    		window.addEvent('resize', this.resizer.bind(this, [thead, tfoot]));
    	}
    	
    	//Fire resize twice to make the thead width right
    	window.fireEvent('resize').fireEvent('resize');
	},
	
	resizer: function(thead, tfoot) {

		//Fix for chrome, and in some cases webkit
		if(thead.length > 1) {
			tfoot = thead[1];
			thead = thead[0];
		}
		var height = window.getHeight(), top = this.table.getParent().getTop();//, debug = $('debug');
		
		//this.table.getParent().setStyle('height', height-top);
		var parent = this.table.getParent().getParent().getParent().getParent(), 
			height = parent.getSize().y, 
			offset = this.table.getParent().getTop() - parent.getTop();
		this.table.getParent().setStyle('height', height-offset);
//		console.log(window.getWidth(), this.table.getCoordinates().right, window.getWidth() - this.table.getCoordinates().right, thead.getCoordinates().right, window.getWidth() - thead.getCoordinates().right);
		
//		$$(thead, tfoot).setStyle('right', window.getWidth() - this.table.getCoordinates().right);

		if(!this.table.getElement('tr')) return;
		
		if(thead) {
		    thead.setStyle('width', this.getComputedWidth(this.table.getElement('thead')));
			this.table.getElement('thead').getElements('td, th').each(function(td, i){
				thead.getElement('thead').getElements('td, th')[i].setStyle('width', this.getComputedWidth(td));
			}, this);
		}
		
		/*this.table.getElement('tr').getChildren().each(function(td, i){
			if(!thead.getElement('thead') || !thead.getElement('thead').getElement('tr')) return;
			var th = thead.getElement('thead').getElement('tr').getChildren()[i];
			$$(th, td).setStyle('width', '');
			var size = {th: this.getComputedWidth(th, td), td: this.getComputedWidth(td, th)};
			size.th > size.td ? td.setStyle('width', size.th) : th.setStyle('width', size.td);
		}, this);*/
		
		if(tfoot) {
		    tfoot.setStyle('width', this.getComputedWidth(this.table.getElement('tfoot')));
			this.table.getElement('tfoot').getElements('td').each(function(td, i){
				tfoot.getElement('tfoot').getElements('td')[i].setStyle('width', this.getComputedWidth(td));
			}, this);
		}
		
		//Background magic
		var backgroundHeight = this.inner.getElement('tbody tr').offsetHeight*2,
		    backgroundOffset = this.inner.getElement('tbody').offsetTop,
		    backgrounds = {
		        odd: this.inner.getElement('tbody tr:odd').getStyle('background-color'),
		        even: this.inner.getElement('tbody tr:even').getStyle('background-color')
		    };
		this.inner.setStyles({
		    backgroundSize: '100% '+backgroundHeight+'px',
		    backgroundPosition: '0px '+(backgroundOffset-2)+'px',
		    backgroundImage: '-webkit-gradient(linear, left top, left bottom, color-stop(0, '+backgrounds.even+'), color-stop(0.5, '+backgrounds.even+'), color-stop(0.5, '+backgrounds.odd+'), color-stop(1, '+backgrounds.odd+'))'
		});
	},
	
	getComputedWidth: function(el, del){
	    var width = el.clientWidth - el.getStyle('padding-left').toInt() - el.getStyle('padding-right').toInt() + el.getStyle('border-left-width').toInt() + el.getStyle('border-right-width').toInt();
	    if(del) width = width - del.getStyle('border-left-width').toInt() - del.getStyle('border-right-width').toInt();
	    return width;
	}

});

Element.implement({

	chromatable: function(options){
		new ChromaTable(this, options);
		return this;
	}

});