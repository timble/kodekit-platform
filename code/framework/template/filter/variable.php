<?php
/**
* @package      Koowa_Template
* @subpackage	Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

/**
 * Template read filter to convert @ to $this->
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage	Filter
 */
class KTemplateFilterVariable extends KTemplateFilterAbstract implements KTemplateFilterRead
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority'   => KCommand::PRIORITY_HIGH,
        ));

        parent::_initialize($config);
    }

    /**
	 * Convert '@' to '$this->', unless when they are escaped '\@'
	 *
	 * @param string
	 * @return KTemplateFilterVariable
	 */
	public function read(&$text)
	{
        /**
         * We could make a better effort at only finding @ between <?php ?>
         * but that's probably not necessary as @ doesn't occur much in the wild
         * and there's a significant performance gain by using str_replace().
         */

		// Replace \@ with \$
		$text = str_replace('\@', '\$', $text);

        // Now replace non-eescaped @'s
         $text = str_replace(array('@$'), '$', $text);

        // Replace \$ with @
		$text = str_replace('\$', '@', $text);

        // Replace <ktml:variable /> with the contents of the variable if it exists
        $matches = array();
        if(preg_match_all('#<ktml:variable\ name="([^"]+)"(.*)\/>#iU', $text, $matches))
        {
            foreach($matches[1] as $key => $match)
            {
                $attribs = $this->_parseAttributes( $matches[2][$key]);

                if($this->getTemplate()->getView()->$match) {
                    $text = str_replace($matches[0][$key], $this->getTemplate()->getView()->$match, $text);
                }
            }
        }

		return $this;
	}
}
