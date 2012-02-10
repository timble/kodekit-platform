/*
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Koowa
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

Request.Install = new Class({

	Extends: Request.JSON,

	initiated: false,

	options: {
		url:		'?option=com_koowa&view=packages&format=json',
		update:		'install'
	},

	request: function(){

		this.options.log = $(this.options.update).hasClass('debug') ? new Element('div', {'class': 'log'}).inject($(this.options.update), 'after') : false;

		this.addEvent('onComplete', function(){
			if(this.options.update && !this.response.text) $(this.options.update).getElement('h2').set('class', 'failed').set('text', 'Failed!');

			if (this.options.update && this.response.json.html) $(this.options.update).empty().set('html', this.response.json.html);
			(function(){
				this.packages = new Chain;

				//if ($(this.options.update).hasClass('debug')) alert(this.response.json.toSource());
				
				this.response.json.packages.each(function(package, i){	

					var install = new Request.Install({
						
						url: this.options.url+'&file='+package,
						
						data: {
							file: package,
							i: i+1
						},
						
						layout: this.response.json.layouts[i],
						
						update: this.options.update,
						
						log: this.options.log,
						
						packages: this.packages,

						onRequest: function(){								
							$(this.options.update)
								.set('html', this.options.layout)
								.getElement('h2')
								.set('title', (new Date).toLocaleString());
							if(this.options.log)
							{
								this.options.log
									.set('html', this.options.layout + this.options.log.get('html'))
									.getElement('h2')
									.set('title', (new Date).toLocaleString());
							}
						},

						onComplete: function(){
							if(!this.unpacked){
								this.unpacked = true;
								this.options.layout = this.response.json.html;
								this.get({folder: this.response.json.package});
								row = ($$('#tasks tr').getLast().get('class').substring(3,4) == '1') ? 0 : 1;

								$('tasks').adopt(
									new Element('tr', {'class': 'row'+row}).adopt([
										new Element('td', {'class': 'key'}).set('text', this.response.json.package_name),
										new Element('td').set('html', this.response.json.package_version)
									])
								);

							} else {
								this.packages.callChain();
								$(this.options.update).fireEvent('complete').getElement('h2').set('class', 'finished').set('text', 'Done!');
							}
							
						}
					});
					this.packages.chain(function(){
						this.get();
					}.bind(install));

					install.packages = this.packages;
				}.bind(this));
				this.packages.callChain();
			}).bind(this).delay(1000);
		})
		.get();
	}

});

window.addEvent('domready', function(){
	(new Request.Install).request();
});