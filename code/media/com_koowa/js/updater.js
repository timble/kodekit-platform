/*
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Koowa
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

window.addEvent('domready', function(){
	$('update').addEvent('submit', function(event){
		var event = new Event(event);
		event.stop();

		this
			.addClass('updating')
			.getElement('button')
			.setProperty('disabled', true);

		var status = this.getElement('.status'), ajax = new Ajax(this.getProperty('action'), {
						method: 'get',
						onSuccess: function(){
							status.setText('Update success!');
						},
						onFailure: function(){
							this.fireEvent('onComplete');
							status.setText('Update failed!');
						},
						onComplete: function(){
							this
								.removeClass('updating')
								.getElement('button')
								.setProperty('disabled', false);
						}.bind(this)
					})
					.request();
	});
});