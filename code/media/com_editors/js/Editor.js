/*
---

name: Editor

description: A Nooku MooTools 1.12 class for extending tinyMCE, CodeMirror and act as an negotiator between them

license: MIT-style license.

author: Stian Didriksen <stian@nooku.org>

requires: [Class]

provides: Editor

...
*/

var Editors = new Hash, Editor = new Class({
	
	Implements: [Options, Events],
	
	identifier: false,
	
	options: {
		visible: true,
		toggle: false,
		cookie: new Hash.Cookie('nooku-editor', {duration: 7}),
		tinyMCE: false,
		codemirror: false,
		buttons: [{
		    "display": "b",
		    "tagStart": "<strong>",
		    "tagEnd": "</strong>",
		    "access": "b"
		},
		{
		    "display": "i",
		    "tagStart": "<em>",
		    "tagEnd": "</em>",
		    "access": "i"
		},
		{
		    "display": "link",
		    "tagStart": "",
		    "tagEnd": "</a>",
		    "access": "a"
		},
		{
		    "display": "b-quote",
		    "tagStart": "\n\n<blockquote>",
		    "tagEnd": "</blockquote>\n\n",
		    "access": "q"
		},
		{
		    "display": "del",
		    "tagStart": "<del datetime=\""+(new Date).toUTCString()+"\">",
		    "tagEnd": "</del>",
		    "access": "d"
		},
		{
		    "display": "ins",
		    "tagStart": "<ins datetime=\""+(new Date).toUTCString()+"\">",
		    "tagEnd": "</ins>",
		    "access": "s"
		},
		{
		    "display": "img",
		    "tagStart": "",
		    "tagEnd": "",
		    "access": "m",
		    "open": -1
		},
		{
		    "display": "ul",
		    "tagStart": "<ul>\n",
		    "tagEnd": "</ul>\n\n",
		    "access": "u"
		},
		{
		    "display": "ol",
		    "tagStart": "<ol>\n",
		    "tagEnd": "</ol>\n\n",
		    "access": "o"
		},
		{
		    "display": "li",
		    "tagStart": "\t<li>",
		    "tagEnd": "</li>\n",
		    "access": "l"
		},
		{
		    "display": "code",
		    "tagStart": "<code>",
		    "tagEnd": "</code>",
		    "access": "c"
		},
		{
		    "display": "more",
		    "tagStart": "<hr id=\"system-readmore\" />",
		    "tagEnd": "",
		    "access": "t",
		    "open": -1
		}]
	},

	//Used for checking isDirty state
	startContent: '',

	
	initialize: function(editor, options, settings){

		//If return editor instance if found
		if(Editors.has(editor)) return Editors.get(editor);

		//Stores the id so the getters works
		this.identifier = editor;

		//Function to be called when getting tinyMCE using this.tinyMCE
		this.__defineGetter__('tinyMCE', function(){
			return tinyMCE.get(this.identifier);
		});
	
		//Sets the options
		this.setOptions(options);

		//@TODO cleanup
		this.settings = settings;

		window.addEvent('domready', function(){

			//Stores an editor reference, and prevents from setting up the html multiple times
			this.editor = $(editor);

			var self = this;
			settings.setup =  function(ed) {
				ed.onBeforeRenderUI.add(function(ed) {
					var editor = self.create.call(self, ed.id, options), dirty = false, isDirty = function(ed){
						var startContent = self.startContent, edContent = ed.getContent({format : 'raw', no_events : 1});

						if(!dirty && ed.isDirty() && ed.getContent()) {
							editor.fireEvent('isDirty');
							dirty = true;
						} else if(dirty && (edContent === startContent || !ed.getContent())) {
							editor.fireEvent('isNotDirty');
							dirty = false;
						}
					};
					ed.onChange.add(isDirty);
					ed.onKeyUp.add(isDirty);
				});
				//TinyMCE often performs formatting on the content on init, so need to make sure that's set as the default value
				ed.onInit.add(function(ed){
					self.startContent = ed.getContent({format : 'raw', no_events : 1});
				});
			}

			//When toggle is active, do not initialize TinyMCE until it's requested
			if(this.options.toggle) {
				this.initializeToggle(settings);
			} else {
				tinyMCE.init(settings);
			}
			
		}.bind(this));

		// Store this Editor instance in the Editors hash
		Editors.set(editor, this);
	},

	create: function(editor, options){

		this.wrap = new Element('div', {'class': 'editor-wrap'})
			.injectBefore(this.editor)
			.adopt([
				new Element('div', {'class': 'editor-container'})
					.adopt([
						new Element('div', {'class': 'quicktags', 'style': 'display:none'})
							.adopt(this.createQuicktags()),
						this.editor
					]),
				new Element('div', {'class': 'editor-toolbar', 'style': (this.options.codemirror ? '' : 'display:none')})
					.adopt([
						new Element('div', {'class': 'editor-switch'})
							.adopt([
								new Element('a', {
									'class': 'editor-mode-html',
									'events': {
										'click': function(){
											this.go('html');
										}.bind(this)
									}
								}).set('text', this.options.lang.html),
								new Element('a', {
									'class': 'active editor-mode-tinymce',
									'events': {
										'click': function(){
											this.go('tinymce');
										}.bind(this)
									}
								}).set('text', this.options.lang.visual)
							])
					])
			]);
		
		this.tinyMCE.addButton('image', {
			title : 'Image',
			image : this.tinyMCE.baseURI.relative + '/themes/advanced/skins/nooku/img/image.png',
			cmd : 'image'
		});	
		
		this.tinyMCE.addCommand('image', function() {
			SqueezeBox.open('?option=com_files&view=images&tmpl=component&e_name='+this.identifier, {handler: 'iframe', size: {x: 570, y: 400}});
		}.bind(this));
		
		this.tinyMCE.addButton('readmore', {
			title : 'Read more…',
			image : this.tinyMCE.baseURI.relative + '/themes/advanced/skins/nooku/img/more.gif',
			cmd : 'Readmore'
		});	
		
		this.tinyMCE.addCommand('Readmore', function() {
			var content = this.tinyMCE.getContent();
			if (content.match(/<hr\s+id=(\"|')system-readmore(\"|')\s*\/*>/i)) {
				alert('Read more already exist!');
				return false;
			} else {
				this.setText('<hr id="system-readmore" />');
			}
		}.bind(this));
		
		/*this.tinyMCE.addButton('article', {
			title : 'Article',
			image : this.tinyMCE.baseURI.relative + '/themes/advanced/skins/nooku/img/article.png',
			cmd : 'Article'
		});	
		
		this.tinyMCE.addCommand('Article', function() {
			SqueezeBox.open('?option=com_articles&view=articles&layout=link&tmpl=component&e_name='+this.identifier, {handler: 'iframe', size: {x: 800, y: 600}});
		}.bind(this));*/

		if(this.tinyMCE.settings.theme_advanced_buttons2) {
			this.tinyMCE.addButton('advanced', {
				title : 'Kitchen Sink',
				image : this.tinyMCE.baseURI.relative + '/themes/advanced/skins/nooku/img/toolbars.gif',
				cmd : 'Advanced'
			});	
			
			this.tinyMCE.addCommand('Advanced', function(cookie) {
				var cm = this.controlManager, tbId = this.getParam('adv_toolbar', 'toolbar2'),  id = cm.get(tbId).id, ifr = this.getContentAreaContainer().firstChild;

				if ( 'undefined' == id )
					return;

				if ( tinymce.DOM.isHidden(id) ) {
					cm.setActive('advanced', 1);
					tinymce.DOM.show(id);	
					tinymce.DOM.setStyle(ifr, 'height', ifr.clientHeight - 28);
					cookie.set('advanced', 1);
				} else {
					cm.setActive('advanced', 0);
					tinymce.DOM.hide(id);
					tinymce.DOM.setStyle(ifr, 'height', ifr.clientHeight + 28);
					cookie.set('advanced', 0);
				}
			}.pass(this.options.cookie, this.tinyMCE));
			
			if(this.options.cookie.get('advanced')) {
				this.tinyMCE.onInit.add(function(ed) {
					ed.controlManager.setActive('advanced', 1);
				}.bind(this));
			} else {
				this.tinyMCE.onInit.add(function(ed) {
					var cm = ed.controlManager, tbId = ed.getParam('adv_toolbar', 'toolbar2'),  id = cm.get(tbId).id, ifr = ed.getContentAreaContainer().firstChild;
					tinymce.DOM.hide(id);
					tinymce.DOM.setStyle(ifr, 'height', ifr.clientHeight + 28);
				}.bind(this));
			}
		}

		
		if ( this.getUserSetting( 'editor' ) == 'html' && this.options.codemirror ) {
			//Prevent the tinyMCE editor to flash on the screen for a split second before the codemirror ui loads
			this.wrap.hide();
			
			this.tinyMCE.onInit.add(function() {
				this.go('html');

				//Done initializing, lets show the editor
				this.wrap.show();
			}.bind(this));
		}

		
		
		// Store this Editor instance in the Editors hash
		Editors.set(editor, this);
		
		// Fire an onEditorInit to allow adding buttons and like
		$(editor).fireEvent('onEditorInit', this);

		//Optional form validation support
		/*
		if(this.editor.form && window.Form && Form.Validator) {
			this.editor.form.addEvent('validate', function(){
				if(!Editors.get(editor).getText().trim().length) {
					this.editor.form.fireEvent();
					return false;
				}
			});
		}
		//*/

		return this;
	},
	
	createQuicktags: function() {
		var buttons = this.options.buttons
			.map(this.createQuicktagButton.bind(this))
			.include(new Element('input', {
				type: 'button',
				id: 'ed_close',
				'class': 'ed_button',
				value: 'close tags',
				title: 'Close all open tags',
				events: {
					click: function(){
						this.edCloseAllTags();
					}.bind(this)
				}
			}));

		return new Element('div', {'class': 'quicktags-toolbar'}).adopt(buttons);
	},
	
	createQuicktagButton: function(button, i) {
		if(button.display == 'img') {
			var event = function(){
				this.edInsertImage();
			}.bind(this);
		} else if(button.display == 'link') {
			var event = function(i){
				this.edInsertLink(i);
			}.pass(i, this);
		} else {
			var event = function(i){
				this.edInsertTag(i);
			}.pass(i, this);
		}
		
		return new Element('input', {
			type: 'button',
			accesskey: button.access,
			'class': 'ed_button',
			value: button.display,
			events: {
				click: event
			}
		});
	},
	
	edInsertImage: function() {
	    var myValue = prompt(quicktagsL10n.enterImageURL, 'http://');
	    if (myValue) {
	        myValue = '<img src="' + myValue + '" alt="' + prompt(quicktagsL10n.enterImageDescription, '') + '" />';
	        this.insertText(myValue);
	    }
	},

	edAddTag: function(button) {
	    if (this.options.buttons[button].tagEnd != '') {
	        this.edOpenTags[this.edOpenTags.length] = button;
	        document.getElementById(this.options.buttons[button].id).value = '/' + document.getElementById(this.options.buttons[button].id).value;
	    }
	},

	edRemoveTag: function(button) {
	    for (var i = 0; i < this.edOpenTags.length; i++) {
	        if (this.edOpenTags[i] == button) {
	            this.edOpenTags.splice(i, 1);
	            document.getElementById(this.options.buttons[button].id).value = document.getElementById(this.options.buttons[button].id).value.replace('/', '');
	        }
	    }
	},
	
	edInsertTag: function(i) {
	    var codemirror = this.editor.codemirror,
	        selection = codemirror.selection();

	    if (selection) {
	        codemirror.replaceSelection(this.options.buttons[i].tagStart + selection + this.options.buttons[i].tagEnd);
	    } else {
	        if (!this.edCheckOpenTags(i) || this.options.buttons[i].tagEnd == '') {
	            codemirror.replaceSelection(this.options.buttons[i].tagStart + selection);
	            this.edAddTag(i);
	        } else {
	            codemirror.replaceSelection(this.options.buttons[i].tagEnd + selection);
	            this.edRemoveTag(i);
	        }
	    }
	},
	
	edInsertLink: function(i, defaultValue) {
	    if (!defaultValue) {
	        defaultValue = 'http://';
	    }
	    if (!this.edCheckOpenTags(i)) {
	        var URL = prompt(quicktagsL10n.enterURL, defaultValue);
	        if (URL) {
	            this.options.buttons[i].tagStart = '<a href="' + URL + '">';
	            this.edInsertTag(i);
	        }
	    }
	    else {
	        this.edInsertTag(i);
	    }
	},

	edOpenTags: [],

	edCloseAllTags: function() {
	    var count = this.edOpenTags.length,
	        o;
	    for (o = 0; o < count; o++) {
	        this.edInsertTag(this.edOpenTags[this.edOpenTags.length - 1]);
	    }
	},

	edCheckOpenTags: function(button) {
	    var tag = 0,
	        i;
	    for (i = 0; i < this.edOpenTags.length; i++) {
	        if (this.edOpenTags[i] == button) {
	            tag++;
	        }
	    }
	    if (tag > 0) {
	        return true; // tag found
	    }
	    else {
	        return false; // tag not found
	    }
	},
	
	getUserSetting: function(key){
		return this.options.cookie.get(key);
	},
	
	setUserSetting: function(key, value){
		return this.options.cookie.set(key, value);
	},
	
	insertText: function(text){
		if(this.options.codemirror) {
			var editor = this.options.cookie.get('editor') || 'tinymce';
			if(editor == 'tinymce') return tinyMCE.execInstanceCommand(this.identifier, 'mceInsertContent',false,text);
			this.editor.codemirror.replaceSelection(text);
		} else {
			tinyMCE.execInstanceCommand(this.identifier, 'mceInsertContent',false,text);
		}
	},

	setText: function(text){
		if(this.options.codemirror) {
			tinyMCE.execInstanceCommand(this.identifier, 'mceSetContent',false,text);
			this.editor.codemirror.setCode(text);
		} else {
			tinyMCE.execInstanceCommand(this.identifier, 'mceSetContent',false,text);
		}
	},

	getText: function(text){
		if(this.options.codemirror) {
			var editor = this.getUserSetting('editor');
			if(editor == 'tinymce') return this.tinyMCE.getContent();
			else return this.editor.codemirror.getCode();
		} else {
			return this.tinyMCE.getContent();
		}
	},
	
	mode : '',
	
	codemirror: false,

	go : function(mode) {
		mode = mode || this.mode || '';

		var ed, qt = this.wrap.getElement('.quicktags'), H = this.wrap.getElement('.editor-mode-html'), P = this.wrap.getElement('.editor-mode-tinymce'), ta = this.editor;

		try { ed = tinyMCE.get(this.editor.getProperty('id')); }
		catch(e) { ed = false; }

		if ( 'tinymce' == mode ) {
			//@TODO With CodeMirror in play, we might not need this code anymore, so commenting out for now
			//if ( ed && ! ed.isHidden() )
				//return false;

			this.setUserSetting( 'editor', 'tinymce' );
			this.options.cookie.set('mode', 'html');

			P.addClass('active');
			H.removeClass('active');
			this.edCloseAllTags();


			//@TODO destroy codemirror instance here, or hide it
			qt.hide();

			ta.style.color = '#FFF';
			if(this.editor.codemirror) ta.value = this.editor.codemirror.getCode();

			try {
				if ( ed )
					/*
					 * We're calling this instead of ed.show() as MooTools' Element.show() retrieves
					 * the original display value as stored by Element.hide().
					 */
					ed.getContainer().show();
				else
					tinyMCE.execCommand("mceAddControl", false, this.editor.getProperty('id'));
			} catch(e) {}
			
			if(this.editor.codemirror) {
				this.editor.codemirror.wrapping.hide();
				ed.setContent(this.editor.codemirror.getCode());
			}
			ta.style.color = '#000';
		} else {
			this.setUserSetting( 'editor', 'html' );
			ta.style.color = '#000';
			this.options.cookie.set('mode', 'tinymce');
			H.addClass('active');
			P.removeClass('active');

			
			
			
			//@TODO create codemirror instance here
			qt.show();
			if(this.editor.codemirror) {
				this.editor.codemirror.wrapping.show();
				this.editor.codemirror.setCode(ed.getContent());
				(function(){this.editor.codemirror.reindent()}.bind(this)).delay(100);
			} else {
				ta.value = ed.getContent();
			}
			if(!this.editor.codemirror && this.options.codemirror) {
				this.editor.codemirror = CodeMirror.fromTextArea(ta.id, {
					lineNumbers: true,
					reindentOnLoad: true,
					parserfile: ['parsexml.js', 'parsecss.js', 'tokenizejavascript.js', 'parsejavascript.js', 'parsehtmlmixed.js']
				});
			}
			
			if ( ed && !ed.isHidden() && this.options.codemirror) {
				var updateNumbers = function(){
				     var active = this.lineNumbers.getElement('.active'), line = this.lineNumber(this.cursorLine());
				     
					if(active) {
						if(active.get('text') == line) return;
						active.removeClass('active');
					}

					var lines = this.lineNumbers.getElements('.CodeMirror-line-numbers div').filter(function(line){
						line.removeClass('active');
						return line.get('text').match(/[0-9]/);
					}), active = lines[line-1];

					if(active) active.addClass('active');
				}.bind(this.editor.codemirror);
				
				var win = this.editor.codemirror.win;
				win.addEventListener('keyup', updateNumbers);
				win.addEventListener('select', updateNumbers);
				win.addEventListener('click', updateNumbers);
				//*/ 
				
				/*
				*document.querySelector('iframe').contentDocument.addEventListener('input', function(){
				     console.log(new Date, arguments[0].type);
				});
				 * We're calling this instead of ed.hide() as MooTools' Element.hide stores 
				 * a reference of the current display value ensuring Element.show
				 * sets it to the right value. Important for CSS3 flex layouts
				 */
				ed.getContainer().hide();
			}
		}

		return false;
	},

	addButtons: function(buttons){
		var names = [];
		buttons.each(function(button){
			names.include(button.name);
			this.addButton(button.name, {
				title: button.text,
				onclick: button.onclick,
				cmd: button.name,
				image : this.baseURI.relative + '/themes/advanced/skins/nooku/img/toolbars.gif',
			});
		}, this.tinyMCE);
	},
	
	initializeToggle: function(settings){
		var self = editor = this, defaultText = this.editor.get('text'), initialized = false;
		this.toggler = new Fx.Toggle(this.editor, {
			wrap: this.wrap,
			onEdit: function(){
				if(!initialized) {
					tinyMCE.init(settings);
					initialized = true;
				}
			},
			onOK: function(){
				this.preview.getElement('.toggle-preview').set('html', editor.getText());
				//Set the text for tinyMCE as well if CodeMirror is active
				editor.setText(editor.getText());

				//Make this configurable later
				editor.editor.form.submit();
			}, onClose: function(){
				editor.setText(defaultText);
			}, onIsDirty: function(){
				this.controls.getElement('.toggle-ok').removeProperty('disabled');
			}, onIsNotDirty: function(){
				this.controls.getElement('.toggle-ok').setProperty('disabled', 'disabled');
			}
		});

		this.toggler.controls.getElement('.toggle-ok').setProperty('disabled', 'disabled');

		this.addEvent('onIsDirty', function(){
			this.toggler.fireEvent('onIsDirty');
		}.bind(this));
		this.addEvent('onIsNotDirty', function(){
			this.toggler.fireEvent('onIsNotDirty');
		}.bind(this));
	}
});



/*
---

@TODO update our mootools 1.2 build to include the following:

script: Element.Shortcuts.js

name: Element.Shortcuts

description: Extends the Element native object to include some shortcut methods.

license: MIT-style license

authors:
  - Aaron Newton

requires:
  - Core/Element.Style
  - /MooTools.More

provides: [Element.Shortcuts]

...
*/

Element.implement({

	isDisplayed: function(){
		return this.getStyle('display') != 'none';
	},

	isVisible: function(){
		var w = this.offsetWidth,
			h = this.offsetHeight;
		return (w == 0 && h == 0) ? false : (w > 0 && h > 0) ? true : this.style.display != 'none';
	},

	hide: function(){
		var d;
		try {
			//IE fails here if the element is not in the dom
			d = this.getStyle('display');
		} catch(e){}
		if (d == "none") return this;
		return this.store('element:_originalDisplay', d || '').setStyle('display', 'none');
	},

	show: function(display){
		if (!display && this.isDisplayed()) return this;
		display = display || this.retrieve('element:_originalDisplay') || 'block';
		return this.setStyle('display', (display == 'none') ? 'block' : display);
	},

	swapClass: function(remove, add){
		return this.removeClass(remove).addClass(add);
	}
});

Document.implement({
	clearSelection: function(){
		if (document.selection && document.selection.empty) {
			document.selection.empty();
		} else if (window.getSelection) {
			var selection = window.getSelection();
			if (selection && selection.removeAllRanges) selection.removeAllRanges();
		}
	}
});