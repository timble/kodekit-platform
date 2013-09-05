<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Accordion Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateHelperAccordion extends TemplateHelperBehavior
{
	/**
	 * Creates a pane and creates the javascript object for it
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return 	string	Html
	 */
	public function startPane( $config = array() )
	{
		$config = new ObjectConfigJson($config);

		$config->append(array(
			'id'	=> 'accordions',
			'options'	=> array(
				'duration'		=> 300,
				'opacity'		=> false,
				'alwaysHide'	=> true,
				'scroll'		=> false
			),
			'attribs'	=> array(),
			'events'	=> array()
		));

		$html  = '';

		// Load the necessary files if they haven't yet been loaded
		if (!isset($this->_loaded['accordion'])) {
			$this->_loaded['accordion'] = true;
		}

		$id      = strtolower($config->id);
		$attribs = $this->buildAttributes($config->attribs);

		$events			= '';
		$onActive 		= 'function(e){e.addClass(\'jpane-toggler-down\');e.removeClass(\'jpane-toggler\');}';
		$onBackground	= 'function(e){e.addClass(\'jpane-toggler\');e.removeClass(\'jpane-toggler-down\');}';

		if($config->events) {
			$events = '{onActive:'.$onActive.',onBackground:'.$onBackground.'}';
		}

		$scroll = $config->options->scroll ? ".addEvent('onActive', function(toggler){
			new Fx.Scroll(window, {duration: this.options.duration, transition: this.transition}).toElement(toggler);
		})" : '';

		/*
		 * Until we find a solution that let us pass a string into json_encode without it being quoted,
		 * we have to use the mootools $merge method to merge events and regular settings back into one
		 * options object.
		*/
		$html .= '
			<script>
				window.addEvent(\'domready\', function(){
					new Accordion($$(\'.panel h3.jpane-toggler\'),$$(\'.panel div.jpane-slider\'),$merge('.$events.','.$config->options.'))'.$scroll.';
				});
			</script>';

		$html .= '<div id="'.$id.'" class="pane-sliders" '.$attribs.'>';
		return $html;
	}

	/**
	 * Ends the pane
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return 	string	Html
	 */
	public function endPane($config = array())
	{
		return '</div>';
	}

	/**
	 * Creates a tab panel with title and starts that panel
	 *
	 * @param 	array 	$config An optional array with configuration options
     * @return 	string	Html
	 */
	public function startPanel($config = array())
	{
		$config = new ObjectConfigJson($config);

		$config->append(array(
			'title'		=> 'Slide',
			'attribs'	=> array(),
			'translate'	=> true
		));

		$title   = $config->translate ? $this->translate($config->title) : $config->title;
		$attribs = $this->buildAttributes($config->attribs);

		$html = '<div class="panel"><h3 class="jpane-toggler title" '.$attribs.'><span>'.$title.'</span></h3><div class="jpane-slider content">';
		return $html;
	}

	/**
	 * Ends a tab page
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return 	string	Html
	 */
	public function endPanel($config = array())
	{
		return '</div></div>';
	}
}