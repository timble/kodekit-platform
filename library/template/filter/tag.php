<?php
/**
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Template filter to parse tags
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage	Filter
 */
abstract class TemplateFilterTag extends TemplateFilterAbstract implements TemplateFilterRenderer
{
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority'   => TemplateFilterChain::PRIORITY_LOW,
        ));

        parent::_initialize($config);
    }

	/**
	 * Find any virtual tags and render them
     *
     * This function will pre-pend the tags to the content
	 *
	 * @param string $text  The text to parse
	 */
	public function render(&$text)
	{
		//Parse the tags
		$tags = $this->_parseTags($text);

		//Prepend the tags again to the text
		$text = $tags.$text;
	}

	/**
	 * Parse the text for the tags
	 *
	 * @param string $text  The text to parse
	 * @return string
	 */
	abstract protected function _parseTags(&$text);

    /**
     * Render the tag
     *
     * @param 	array	$attribs Associative array of attributes
     * @param 	string	$content The element content
     * @return string
     */
	abstract protected function _renderTag($attribs = array(), $content = null);
}