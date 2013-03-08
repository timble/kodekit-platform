<?php
/**
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Framework;

/**
 * Template filter to parse tags
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage	Filter
 */
abstract class TemplateFilterTag extends TemplateFilterAbstract implements TemplateFilterWrite
{
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional Config object with configuration options
     * @return void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
            'priority'   => Command::PRIORITY_LOW,
        ));

        parent::_initialize($config);
    }

	/**
	 * Find any virtual tags and render them
     *
     * This function will pre-pend the tags to the content
	 *
	 * @param string Block of text to parse
	 * @return TemplateFilterTag
	 */
	public function write(&$text)
	{
		//Parse the tags
		$tags = $this->_parseTags($text);

		//Prepend the tags again to the text
		$text = $tags.$text;

		return $this;
	}

	/**
	 * Parse the text for the tags
	 *
	 * @param string Block of text to parse
	 * @return string
	 */
	abstract protected function _parseTags(&$text);

    /**
     * Render the tag
     *
     * @param 	array	Associative array of attributes
     * @param 	string	The element content
     * @return string
     */
	abstract protected function _renderTag($attribs = array(), $content = null);
}