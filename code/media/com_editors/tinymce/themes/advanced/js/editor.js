// utility functions
function convertEntities(o) {
	var c, v;
	c = function(s) {
		if (/&[^;]+;/.test(s)) {
			var e = document.createElement("div");
			e.innerHTML = s;
			return !e.firstChild ? s : e.firstChild.nodeValue;
		}
		return s;
	}

	if ( typeof o === 'string' ) {
		return c(o);
	} else if ( typeof o === 'object' ) {
		for (v in o) {
			if ( typeof o[v] === 'string' ) {
				o[v] = c(o[v]);
			}
		}
	}
	return o;
}

var wpCookies = {
// The following functions are from Cookie.js class in TinyMCE, Moxiecode, used under LGPL.

	each : function(o, cb, s) {
		var n, l;

		if (!o)
			return 0;

		s = s || o;

		if (typeof(o.length) != 'undefined') {
			for (n=0, l = o.length; n<l; n++) {
				if (cb.call(s, o[n], n, o) === false)
					return 0;
			}
		} else {
			for (n in o) {
				if (o.hasOwnProperty(n)) {
					if (cb.call(s, o[n], n, o) === false) {
						return 0;
					}
				}
			}
		}
		return 1;
	},

	getHash : function(n) {
		var v = this.get(n), h;

		if (v) {
			this.each(v.split('&'), function(v) {
				v = v.split('=');
				h = h || {};
				h[v[0]] = v[1];
			});
		}
		return h;
	},

	setHash : function(n, v, e, p, d, s) {
		var o = '';

		this.each(v, function(v, k) {
			o += (!o ? '' : '&') + k + '=' + v;
		});

		this.set(n, o, e, p, d, s);
	},

	get : function(n) {
		var c = document.cookie, e, p = n + "=", b;

		if (!c)
			return;

		b = c.indexOf("; " + p);

		if (b == -1) {
			b = c.indexOf(p);

			if (b != 0)
				return null;

		} else {
			b += 2;
		}

		e = c.indexOf(";", b);

		if (e == -1)
			e = c.length;

		return decodeURIComponent(c.substring(b + p.length, e));
	},

	set : function(n, v, e, p, d, s) {
		document.cookie = n + "=" + encodeURIComponent(v) +
			((e) ? "; expires=" + e.toGMTString() : "") +
			((p) ? "; path=" + p : "") +
			((d) ? "; domain=" + d : "") +
			((s) ? "; secure" : "");
	},

	remove : function(n, p) {
		var d = new Date();

		d.setTime(d.getTime() - 1000);

		this.set(n, '', d, p, d);
	}
};

// Returns the value as string. Second arg or empty string is returned when value is not set.
function getUserSetting( name, def ) {
	var o = getAllUserSettings();

	if ( o.hasOwnProperty(name) )
		return o[name];

	if ( typeof def != 'undefined' )
		return def;

	return '';
}

// Both name and value must be only ASCII letters, numbers or underscore
// and the shorter, the better (cookies can store maximum 4KB). Not suitable to store text.
function setUserSetting( name, value, del ) {

	if ( 'object' !== typeof userSettings )
		return false;

	var c = 'wp-settings-' + userSettings.uid, o = wpCookies.getHash(c) || {}, d = new Date(), p,
	n = name.toString().replace(/[^A-Za-z0-9_]/, ''), v = value.toString().replace(/[^A-Za-z0-9_]/, '');

	if ( del ) {
		delete o[n];
	} else {
		o[n] = v;
	}

	d.setTime( d.getTime() + 31536000000 );
	p = userSettings.url;

	wpCookies.setHash(c, o, d, p);
	wpCookies.set('wp-settings-time-'+userSettings.uid, userSettings.time, d, p);

	return name;
}

function deleteUserSetting( name ) {
	return setUserSetting( name, '', 1 );
}

// Returns all settings as js object.
function getAllUserSettings() {
	if ( 'object' !== typeof userSettings )
		return {};

	return wpCookies.getHash('wp-settings-' + userSettings.uid) || {};
}

var switchEditors = {

	mode : '',
	
	codemirror: false,

	I : function(e) {
		return document.getElementById(e);
	},

	_wp_Nop : function(content) {
		var blocklist1, blocklist2;

		// Protect pre|script tags
		content = content.replace(/<(pre|script)[^>]*>[\s\S]+?<\/\1>/g, function(a) {
			a = a.replace(/<br ?\/?>[\r\n]*/g, '<wp_temp>');
			return a.replace(/<\/?p( [^>]*)?>[\r\n]*/g, '<wp_temp>');
		});

		// Pretty it up for the source editor
		blocklist1 = 'blockquote|ul|ol|li|table|thead|tbody|tfoot|tr|th|td|div|h[1-6]|p|fieldset';
		content = content.replace(new RegExp('\\s*</('+blocklist1+')>\\s*', 'g'), '</$1>\n');
		content = content.replace(new RegExp('\\s*<(('+blocklist1+')[^>]*)>', 'g'), '\n<$1>');

		// Mark </p> if it has any attributes.
		content = content.replace(/(<p [^>]+>.*?)<\/p>/g, '$1</p#>');

		// Sepatate <div> containing <p>
		content = content.replace(/<div([^>]*)>\s*<p>/gi, '<div$1>\n\n');

		// Remove <p> and <br />
		content = content.replace(/\s*<p>/gi, '');
		content = content.replace(/\s*<\/p>\s*/gi, '\n\n');
		content = content.replace(/\n[\s\u00a0]+\n/g, '\n\n');
		content = content.replace(/\s*<br ?\/?>\s*/gi, '\n');

		// Fix some block element newline issues
		content = content.replace(/\s*<div/g, '\n<div');
		content = content.replace(/<\/div>\s*/g, '</div>\n');
		content = content.replace(/\s*\[caption([^\[]+)\[\/caption\]\s*/gi, '\n\n[caption$1[/caption]\n\n');
		content = content.replace(/caption\]\n\n+\[caption/g, 'caption]\n\n[caption');

		blocklist2 = 'blockquote|ul|ol|li|table|thead|tbody|tfoot|tr|th|td|h[1-6]|pre|fieldset';
		content = content.replace(new RegExp('\\s*<(('+blocklist2+') ?[^>]*)\\s*>', 'g'), '\n<$1>');
		content = content.replace(new RegExp('\\s*</('+blocklist2+')>\\s*', 'g'), '</$1>\n');
		content = content.replace(/<li([^>]*)>/g, '\t<li$1>');

		if ( content.indexOf('<object') != -1 ) {
			content = content.replace(/<object[\s\S]+?<\/object>/g, function(a){
				return a.replace(/[\r\n]+/g, '');
			});
		}

		// Unmark special paragraph closing tags
		content = content.replace(/<\/p#>/g, '</p>\n');
		content = content.replace(/\s*(<p [^>]+>[\s\S]*?<\/p>)/g, '\n$1');

		// Trim whitespace
		content = content.replace(/^\s+/, '');
		content = content.replace(/[\s\u00a0]+$/, '');

		// put back the line breaks in pre|script
		content = content.replace(/<wp_temp>/g, '\n');

		return content;
	},

	go : function(id, mode) {
		id = id || 'content';
		mode = mode || this.mode || '';

		var ed, qt = this.I('quicktags'), H = this.I('edButtonHTML'), P = this.I('edButtonPreview'), ta = this.I(id);

		try { ed = tinyMCE.get(id); }
		catch(e) { ed = false; }

		if ( 'tinymce' == mode ) {
			//@TODO With CodeMirror in play, we might not need this code anymore, so commenting out for now
			//if ( ed && ! ed.isHidden() )
				//return false;

			setUserSetting( 'editor', 'tinymce' );
			this.mode = 'html';

			P.className = 'active';
			H.className = '';
			edCloseAllTags(); // :-(


			//@TODO destroy codemirror instance here, or hide it
			qt.style.display = 'none';

			ta.style.color = '#FFF';
			//ta.value = this.wpautop(ta.value);
			if(this.codemirror) ta.value = this.wpautop(this.codemirror.getCode());

			try {
				if ( ed )
					ed.show();
				else
					tinyMCE.execCommand("mceAddControl", false, id);
			} catch(e) {}
			
			if(this.codemirror) this.codemirror.wrapping.setStyle('display', 'none');
			ta.style.color = '#000';
		} else {
			setUserSetting( 'editor', 'html' );
			ta.style.color = '#000';
			this.mode = 'tinymce';
			H.className = 'active';
			P.className = '';

			
			
			
			//@TODO create codemirror instance here
			qt.style.display = 'block';
			if(this.codemirror) {
				this.codemirror.wrapping.setStyle('display', 'block');
				this.codemirror.setCode(ed.getContent());
				(function(){this.codemirror.reindent()}.bind(this)).delay(100);
			} else {
				ta.value = ed.getContent(); 
			}
			if(!this.codemirror) this.codemirror = CodeMirror.fromTextArea(ta.id);

			// Reindent everything, if editor is defined
			if(this.codemirror.editor) {
			
				
			}
			
			//if ( ed && !ed.isHidden() ) {
				//this.codemirror.frame.style.height = ed.getContentAreaContainer().offsetHeight + 24 + 'px';
				//ta.style.height = ed.getContentAreaContainer().getSize().x + 24 + 'px';

				ed.hide();
			//}

			ta.setStyle('display', 'none');
		}
		return false;
	},

	_wp_Autop : function(pee) {
		var blocklist = 'table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|address|math|p|h[1-6]|fieldset|legend';

		if ( pee.indexOf('<object') != -1 ) {
			pee = pee.replace(/<object[\s\S]+?<\/object>/g, function(a){
				return a.replace(/[\r\n]+/g, '');
			});
		}

		pee = pee.replace(/<[^<>]+>/g, function(a){
			return a.replace(/[\r\n]+/g, ' ');
		});

		pee = pee + '\n\n';
		pee = pee.replace(/<br \/>\s*<br \/>/gi, '\n\n');
		pee = pee.replace(new RegExp('(<(?:'+blocklist+')[^>]*>)', 'gi'), '\n$1');
		pee = pee.replace(new RegExp('(</(?:'+blocklist+')>)', 'gi'), '$1\n\n');
		pee = pee.replace(/\r\n|\r/g, '\n');
		pee = pee.replace(/\n\s*\n+/g, '\n\n');
		pee = pee.replace(/([\s\S]+?)\n\n/g, '<p>$1</p>\n');
		pee = pee.replace(/<p>\s*?<\/p>/gi, '');
		pee = pee.replace(new RegExp('<p>\\s*(</?(?:'+blocklist+')[^>]*>)\\s*</p>', 'gi'), "$1");
		pee = pee.replace(/<p>(<li.+?)<\/p>/gi, '$1');
		pee = pee.replace(/<p>\s*<blockquote([^>]*)>/gi, '<blockquote$1><p>');
		pee = pee.replace(/<\/blockquote>\s*<\/p>/gi, '</p></blockquote>');
		pee = pee.replace(new RegExp('<p>\\s*(</?(?:'+blocklist+')[^>]*>)', 'gi'), "$1");
		pee = pee.replace(new RegExp('(</?(?:'+blocklist+')[^>]*>)\\s*</p>', 'gi'), "$1");
		pee = pee.replace(/\s*\n/gi, '<br />\n');
		pee = pee.replace(new RegExp('(</?(?:'+blocklist+')[^>]*>)\\s*<br />', 'gi'), "$1");
		pee = pee.replace(/<br \/>(\s*<\/?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)>)/gi, '$1');
		pee = pee.replace(/(?:<p>|<br ?\/?>)*\s*\[caption([^\[]+)\[\/caption\]\s*(?:<\/p>|<br ?\/?>)*/gi, '[caption$1[/caption]');

		pee = pee.replace(/(<(?:div|th|td|form|fieldset|dd)[^>]*>)(.*?)<\/p>/g, function(a, b, c) {
			if ( c.match(/<p( [^>]+)?>/) )
				return a;

			return b + '<p>' + c + '</p>';
		});

		// Fix the pre|script tags
		pee = pee.replace(/<(pre|script)[^>]*>[\s\S]+?<\/\1>/g, function(a) {
			a = a.replace(/<br ?\/?>[\r\n]*/g, '\n');
			return a.replace(/<\/?p( [^>]*)?>[\r\n]*/g, '\n');
		});

		return pee;
	},

	pre_wpautop : function(content) {
		var t = this, o = { o: t, data: content, unfiltered: content };
		$E('body').fireEvent('beforePreWpautop', [o]);
		o.data = t._wp_Nop(o.data);
		$E('body').fireEvent('afterPreWpautop', [o]);
		return o.data;
	},

	wpautop : function(pee) {
		var t = this, o = { o: t, data: pee, unfiltered: pee };

		$E('body').fireEvent('beforeWpautop', [o]);
		o.data = t._wp_Autop(o.data);
		$E('body').fireEvent('afterWpautop', [o]);
		return o.data;
	}
};

/**
 * WordPress plugin.
 */

(function() {
	var DOM = tinymce.DOM;

	tinymce.create('tinymce.plugins.WordPress', {
		mceTout : 0,

		init : function(ed, url) {
			var t = this, tbId = ed.getParam('wordpress_adv_toolbar', 'toolbar2'), last = 0, moreHTML, nextpageHTML;
			moreHTML = '<img src="' + url + '/img/trans.gif" class="mceWPmore mceItemNoResize" title="'+ed.getLang('wordpress.wp_more_alt')+'" />';
			nextpageHTML = '<img src="' + url + '/img/trans.gif" class="mceWPnextpage mceItemNoResize" title="'+ed.getLang('wordpress.wp_page_alt')+'" />';

			if ( getUserSetting('hidetb', '0') == '1' )
				ed.settings.wordpress_adv_hidden = 0;

			// Hides the specified toolbar and resizes the iframe
			ed.onPostRender.add(function() {
				var adv_toolbar = ed.controlManager.get(tbId);
				if ( ed.getParam('wordpress_adv_hidden', 1) && adv_toolbar ) {
					DOM.hide(adv_toolbar.id);
					t._resizeIframe(ed, tbId, 28);
				}
			});

			// Register commands
			ed.addCommand('WP_More', function() {
				ed.execCommand('mceInsertContent', 0, moreHTML);
			});

			ed.addCommand('WP_Page', function() {
				ed.execCommand('mceInsertContent', 0, nextpageHTML);
			});

			ed.addCommand('WP_Help', function() {
					ed.windowManager.open({
						url : tinymce.baseURL + '/wp-mce-help.php',
						width : 450,
						height : 420,
						inline : 1
					});
				});

			ed.addCommand('WP_Adv', function() {
				var cm = ed.controlManager, id = cm.get(tbId).id;

				if ( 'undefined' == id )
					return;

				if ( DOM.isHidden(id) ) {
					cm.setActive('wp_adv', 1);
					DOM.show(id);
					t._resizeIframe(ed, tbId, -28);
					ed.settings.wordpress_adv_hidden = 0;
					setUserSetting('hidetb', '1');
				} else {
					cm.setActive('wp_adv', 0);
					DOM.hide(id);
					t._resizeIframe(ed, tbId, 28);
					ed.settings.wordpress_adv_hidden = 1;
					setUserSetting('hidetb', '0');
				}
			});

			// Register buttons
			ed.addButton('wp_more', {
				title : 'wordpress.wp_more_desc',
				image : url + '/img/more.gif',
				cmd : 'WP_More'
			});

			ed.addButton('wp_page', {
				title : 'wordpress.wp_page_desc',
				image : url + '/img/page.gif',
				cmd : 'WP_Page'
			});

			ed.addButton('wp_help', {
				title : 'wordpress.wp_help_desc',
				image : url + '/img/help.gif',
				cmd : 'WP_Help'
			});

			ed.addButton('wp_adv', {
				title : 'wordpress.wp_adv_desc',
				image : url + '/img/toolbars.gif',
				cmd : 'WP_Adv'
			});

			// Add Media buttons
			ed.addButton('add_media', {
				title : 'wordpress.add_media',
				image : url + '/img/media.gif',
				onclick : function() {
					tb_show('', tinymce.DOM.get('add_media').href);
					tinymce.DOM.setStyle( ['TB_overlay','TB_window','TB_load'], 'z-index', '999999' );
				}
			});

			ed.addButton('add_image', {
				title : 'wordpress.add_image',
				image : url + '/img/image.gif',
				onclick : function() {
					tb_show('', tinymce.DOM.get('add_image').href);
					tinymce.DOM.setStyle( ['TB_overlay','TB_window','TB_load'], 'z-index', '999999' );
				}
			});

			ed.addButton('add_video', {
				title : 'wordpress.add_video',
				image : url + '/img/video.gif',
				onclick : function() {
					tb_show('', tinymce.DOM.get('add_video').href);
					tinymce.DOM.setStyle( ['TB_overlay','TB_window','TB_load'], 'z-index', '999999' );
				}
			});

			ed.addButton('add_audio', {
				title : 'wordpress.add_audio',
				image : url + '/img/audio.gif',
				onclick : function() {
					tb_show('', tinymce.DOM.get('add_audio').href);
					tinymce.DOM.setStyle( ['TB_overlay','TB_window','TB_load'], 'z-index', '999999' );
				}
			});

			// Add Media buttons to fullscreen
			ed.onBeforeExecCommand.add(function(ed, cmd, ui, val) {
				var DOM = tinymce.DOM;
				if ( 'mceFullScreen' != cmd ) return;
				if ( 'mce_fullscreen' != ed.id && DOM.get('add_audio') && DOM.get('add_video') && DOM.get('add_image') && DOM.get('add_media') )
					ed.settings.theme_advanced_buttons1 += ',|,add_image,add_video,add_audio,add_media';
			});

			// Add class "alignleft", "alignright" and "aligncenter" when selecting align for images.
			ed.addCommand('JustifyLeft', function() {
				var n = ed.selection.getNode();

				if ( n.nodeName != 'IMG' )
					ed.editorCommands.mceJustify('JustifyLeft', 'left');
				else ed.plugins.wordpress.do_align(n, 'alignleft');
			});

			ed.addCommand('JustifyRight', function() {
				var n = ed.selection.getNode();

				if ( n.nodeName != 'IMG' )
					ed.editorCommands.mceJustify('JustifyRight', 'right');
				else ed.plugins.wordpress.do_align(n, 'alignright');
			});

			ed.addCommand('JustifyCenter', function() {
				var n = ed.selection.getNode(), P = ed.dom.getParent(n, 'p'), DL = ed.dom.getParent(n, 'dl');

				if ( n.nodeName == 'IMG' && ( P || DL ) )
					ed.plugins.wordpress.do_align(n, 'aligncenter');
				else ed.editorCommands.mceJustify('JustifyCenter', 'center');
			});

			// Word count if script is loaded
			if ( 'undefined' != typeof wpWordCount ) {
				ed.onKeyUp.add(function(ed, e) {
					if ( e.keyCode == last ) return;
					if ( 13 == e.keyCode || 8 == last || 46 == last ) wpWordCount.wc( ed.getContent({format : 'raw'}) );
					last = e.keyCode;
				});
			};

			ed.onSaveContent.add(function(ed, o) {
				if ( typeof(switchEditors) == 'object' ) {
					if ( ed.isHidden() )
						o.content = o.element.value;
					else
						o.content = switchEditors.pre_wpautop(o.content);
				}
			});

			/* disable for now
			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = t._setEmbed(o.content);
			});

			ed.onPostProcess.add(function(ed, o) {
				if ( o.get )
					o.content = t._getEmbed(o.content);
			});
			*/

			// Add listeners to handle more break
			t._handleMoreBreak(ed, url);

			// Add custom shortcuts
			ed.addShortcut('alt+shift+c', ed.getLang('justifycenter_desc'), 'JustifyCenter');
			ed.addShortcut('alt+shift+r', ed.getLang('justifyright_desc'), 'JustifyRight');
			ed.addShortcut('alt+shift+l', ed.getLang('justifyleft_desc'), 'JustifyLeft');
			ed.addShortcut('alt+shift+j', ed.getLang('justifyfull_desc'), 'JustifyFull');
			ed.addShortcut('alt+shift+q', ed.getLang('blockquote_desc'), 'mceBlockQuote');
			ed.addShortcut('alt+shift+u', ed.getLang('bullist_desc'), 'InsertUnorderedList');
			ed.addShortcut('alt+shift+o', ed.getLang('numlist_desc'), 'InsertOrderedList');
			ed.addShortcut('alt+shift+d', ed.getLang('striketrough_desc'), 'Strikethrough');
			ed.addShortcut('alt+shift+n', ed.getLang('spellchecker.desc'), 'mceSpellCheck');
			ed.addShortcut('alt+shift+a', ed.getLang('link_desc'), 'mceLink');
			ed.addShortcut('alt+shift+s', ed.getLang('unlink_desc'), 'unlink');
			ed.addShortcut('alt+shift+m', ed.getLang('image_desc'), 'mceImage');
			ed.addShortcut('alt+shift+g', ed.getLang('fullscreen.desc'), 'mceFullScreen');
			ed.addShortcut('alt+shift+z', ed.getLang('wp_adv_desc'), 'WP_Adv');
			ed.addShortcut('alt+shift+h', ed.getLang('help_desc'), 'WP_Help');
			ed.addShortcut('alt+shift+t', ed.getLang('wp_more_desc'), 'WP_More');
			ed.addShortcut('alt+shift+p', ed.getLang('wp_page_desc'), 'WP_Page');
			ed.addShortcut('ctrl+s', ed.getLang('save_desc'), function(){if('function'==typeof autosave)autosave();});

			if ( tinymce.isWebKit ) {
				ed.addShortcut('alt+shift+b', ed.getLang('bold_desc'), 'Bold');
				ed.addShortcut('alt+shift+i', ed.getLang('italic_desc'), 'Italic');
			}

			ed.onInit.add(function(ed) {
				tinymce.dom.Event.add(ed.getWin(), 'scroll', function(e) {
					ed.plugins.wordpress._hideButtons();
				});
				tinymce.dom.Event.add(ed.getBody(), 'dragstart', function(e) {
					ed.plugins.wordpress._hideButtons();
				});
			});

			ed.onBeforeExecCommand.add(function(ed, cmd, ui, val) {
				ed.plugins.wordpress._hideButtons();
			});

			ed.onSaveContent.add(function(ed, o) {
				ed.plugins.wordpress._hideButtons();
			});

			ed.onMouseDown.add(function(ed, e) {
				if ( e.target.nodeName != 'IMG' )
					ed.plugins.wordpress._hideButtons();
			});
		},

		getInfo : function() {
			return {
				longname : 'WordPress Plugin',
				author : 'WordPress', // add Moxiecode?
				authorurl : 'http://wordpress.org',
				infourl : 'http://wordpress.org',
				version : '3.0'
			};
		},

		// Internal functions
		_setEmbed : function(c) {
			return c.replace(/\[embed\]([\s\S]+?)\[\/embed\][\s\u00a0]*/g, function(a,b){
				return '<img width="300" height="200" src="' + tinymce.baseURL + '/plugins/wordpress/img/trans.gif" class="wp-oembed mceItemNoResize" alt="'+b+'" title="'+b+'" />';
			});
		},

		_getEmbed : function(c) {
			return c.replace(/<img[^>]+>/g, function(a) {
				if ( a.indexOf('class="wp-oembed') != -1 ) {
					var u = a.match(/alt="([^\"]+)"/);
					if ( u[1] )
						a = '[embed]' + u[1] + '[/embed]';
				}
				return a;
			});
		},

		_showButtons : function(n, id) {
			var ed = tinyMCE.activeEditor, p1, p2, vp, DOM = tinymce.DOM, X, Y;

			vp = ed.dom.getViewPort(ed.getWin());
			p1 = DOM.getPos(ed.getContentAreaContainer());
			p2 = ed.dom.getPos(n);

			X = Math.max(p2.x - vp.x, 0) + p1.x;
			Y = Math.max(p2.y - vp.y, 0) + p1.y;

			DOM.setStyles(id, {
				'top' : Y+5+'px',
				'left' : X+5+'px',
				'display' : 'block'
			});

			if ( this.mceTout )
				clearTimeout(this.mceTout);

			this.mceTout = setTimeout( function(){ed.plugins.wordpress._hideButtons();}, 5000 );
		},

		_hideButtons : function() {
			if ( !this.mceTout )
				return;

			if ( document.getElementById('wp_editbtns') )
				tinymce.DOM.hide('wp_editbtns');

			if ( document.getElementById('wp_gallerybtns') )
				tinymce.DOM.hide('wp_gallerybtns');

			clearTimeout(this.mceTout);
			this.mceTout = 0;
		},

		do_align : function(n, a) {
			var P, DL, DIV, cls, c, ed = tinyMCE.activeEditor;

			if ( /^(mceItemFlash|mceItemShockWave|mceItemWindowsMedia|mceItemQuickTime|mceItemRealMedia)$/.test(n.className) )
				return;

			P = ed.dom.getParent(n, 'p');
			DL = ed.dom.getParent(n, 'dl');
			DIV = ed.dom.getParent(n, 'div');

			if ( DL && DIV ) {
				cls = ed.dom.hasClass(DL, a) ? 'alignnone' : a;
				DL.className = DL.className.replace(/align[^ '"]+\s?/g, '');
				ed.dom.addClass(DL, cls);
				c = (cls == 'aligncenter') ? ed.dom.addClass(DIV, 'mceIEcenter') : ed.dom.removeClass(DIV, 'mceIEcenter');
			} else if ( P ) {
				cls = ed.dom.hasClass(n, a) ? 'alignnone' : a;
				n.className = n.className.replace(/align[^ '"]+\s?/g, '');
				ed.dom.addClass(n, cls);
				if ( cls == 'aligncenter' )
					ed.dom.setStyle(P, 'textAlign', 'center');
				else if (P.style && P.style.textAlign == 'center')
					ed.dom.setStyle(P, 'textAlign', '');
			}

			ed.execCommand('mceRepaint');
		},

		// Resizes the iframe by a relative height value
		_resizeIframe : function(ed, tb_id, dy) {
			var ifr = ed.getContentAreaContainer().firstChild;

			DOM.setStyle(ifr, 'height', ifr.clientHeight + dy); // Resize iframe
			ed.theme.deltaHeight += dy; // For resize cookie
		},

		_handleMoreBreak : function(ed, url) {
			var moreHTML, nextpageHTML;

			moreHTML = '<img src="' + url + '/img/trans.gif" alt="$1" class="mceWPmore mceItemNoResize" title="'+ed.getLang('wordpress.wp_more_alt')+'" />';
			nextpageHTML = '<img src="' + url + '/img/trans.gif" class="mceWPnextpage mceItemNoResize" title="'+ed.getLang('wordpress.wp_page_alt')+'" />';

			// Load plugin specific CSS into editor
			ed.onInit.add(function() {
				ed.dom.loadCSS(url + '/css/content.css');
			});

			// Display morebreak instead if img in element path
			ed.onPostRender.add(function() {
				if (ed.theme.onResolveName) {
					ed.theme.onResolveName.add(function(th, o) {
						if (o.node.nodeName == 'IMG') {
							if ( ed.dom.hasClass(o.node, 'mceWPmore') )
								o.name = 'wpmore';
							if ( ed.dom.hasClass(o.node, 'mceWPnextpage') )
								o.name = 'wppage';
						}

					});
				}
			});

			// Replace morebreak with images
			ed.onBeforeSetContent.add(function(ed, o) {
				o.content = o.content.replace(/<!--more(.*?)-->/g, moreHTML);
				o.content = o.content.replace(/<!--nextpage-->/g, nextpageHTML);
			});

			// Replace images with morebreak
			ed.onPostProcess.add(function(ed, o) {
				if (o.get)
					o.content = o.content.replace(/<img[^>]+>/g, function(im) {
						if (im.indexOf('class="mceWPmore') !== -1) {
							var m, moretext = (m = im.match(/alt="(.*?)"/)) ? m[1] : '';
							im = '<!--more'+moretext+'-->';
						}
						if (im.indexOf('class="mceWPnextpage') !== -1)
							im = '<!--nextpage-->';

						return im;
					});
			});

			// Set active buttons if user selected pagebreak or more break
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('wp_page', n.nodeName === 'IMG' && ed.dom.hasClass(n, 'mceWPnextpage'));
				cm.setActive('wp_more', n.nodeName === 'IMG' && ed.dom.hasClass(n, 'mceWPmore'));
			});
		}
	});

	// Register plugin
	tinymce.PluginManager.add('wordpress', tinymce.plugins.WordPress);
})();