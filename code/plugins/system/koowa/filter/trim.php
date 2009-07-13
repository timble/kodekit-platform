<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPL <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.koowa.org
*/

/**
 * Trim filter.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterTrim extends KFilterAbstract
{
	/**
     * List of characters provided to the trim() function
     *
     * If this is null, then trim() is called with no specific character list,
     * and its default behavior will be invoked, trimming whitespace.
     *
     * @var string|null
     */
    protected $_charList = null;

	/**
	 * Constructor
	 *
	 * @param	array	Options array
	 */
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		
		// List of user-defined tags
		if(isset($options['char_list'])) {
			$this->_charList = $options['char_list'];
		}
	}
    
    /**
     * Returns the charList option
     *
     * @return string|null
     */
    public function getCharList()
    {
        return $this->_charList;
    }

    /**
     * Sets the charList option
     *
     * @param  string|null $charList
     * @return this
     */
    public function setCharList($charList)
    {
        $this->_charList = $charList;
        return $this;
    }

	/**
	 * Validate a value
	 *
	 * @param	scalar	Value to be validated
	 * @return	bool	True when the variable is valid
	 */
	protected function _validate($value)
	{
		return (is_string($value));
	}
	
	/**
	 * Sanitize a value
	 * 
	 * Returns the variable with characters stripped from the beginning and end
	 *
	 * @param	mixed	Value to be sanitized
	 * @return	string
	 */
	protected function _sanitize($value)
	{
	 	if (null === $this->_charList) {
            return trim((string) $value);
        } else {
            return trim((string) $value, $this->_charList);
        }
	}
}