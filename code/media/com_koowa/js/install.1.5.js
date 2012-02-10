/*
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Koowa
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

Ajax.Install = Ajax.extend({

	initiated: false,

	options: {
		url:		'?option=com_koowa&view=packages&format=json',
		update:		'install',
		method:		'get'	},

	initialize: function(options){
		this.setOptions(options);
		this.parent(this.options.url, this.options);
	},

	install: function(){
		this.options.log = $(this.options.update).hasClass('debug') ? new Element('div', {'class': 'log'}).injectAfter($(this.options.update)) : false;

		this
			.addEvent('onComplete', function(){
				if(this.options.update && !this.response.text) $(this.options.update).getElement('h2').setProperty('class', 'failed').setText('Failed!');

				if (this.options.update && this.response.data.html) $(this.options.update).empty().setHTML(this.response.data.html);
				(function(){
					this.packages = new Chain;

					//if ($(this.options.update).hasClass('debug')) alert(this.response.data.toSource());
					
					this.response.data.packages.each(function(package, i){	

						var install = new Ajax.Install({
							
							data: {
								file: package,
								i: i+1
							},
							
							layout: this.response.data.layouts[i],
							
							update: this.options.update,
							
							log: this.options.log,
							
							packages: this.packages,

							onRequest: function(){								
								$(this.options.update)
									.setHTML(this.options.layout)
									.getElement('h2')
									.setProperty('title', (new Date).toLocaleString());
								if(this.options.log)
								{
									this.options.log
										.setHTML(this.options.layout + this.options.log.innerHTML)
										.getElement('h2')
										.setProperty('title', (new Date).toLocaleString());
								}
							},

							onComplete: function(){
								if(!this.unpacked){
									this.unpacked = true;
									this.options.layout = this.response.data.html;
									this.request({folder: this.response.data.package});
									row = ($$('#tasks tr').getLast().getProperty('class').substring(3,4) == '1') ? 0 : 1;

									$('tasks').adopt(
										new Element('tr', {'class': 'row'+row}).adopt([
											new Element('td', {'class': 'key'}).setText(this.response.data.package_name),
											new Element('td').setHTML(this.response.data.package_version)
										])
									);

								} else {
									this.packages.callChain();
									$(this.options.update).fireEvent('complete').getElement('h2').setProperty('class', 'finished').setText('Done!');
								}
								
							}
						});
						this.packages.chain(function(){
							this.request();
						}.bind(install));

						install.packages = this.packages;
					}.bind(this));
					this.packages.callChain();
				}).bind(this).delay(1000);
			})
			.request();
	},

	onComplete: function(){
		if (this.options.evalScripts || this.options.evalResponse) this.evalScripts();
		this.response.data = Json.evaluate(this.response.text, this.options.secure);
		this.fireEvent('onComplete', [this.response.data, this.response.text, this.response.xml], 20);
	}

});

window.addEvent('domready', function(){
	(new Ajax.Install).install();
});