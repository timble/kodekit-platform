<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_View
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Export a nested array as a CSV file
 * 
 * @example
 * // in child view class
 * public function display()
 * {
 * 		$this->assign('data', $nested_array);
 * 		$this->assign('filename', 'my_data.csv'); 
 * 		return parent::display();
 * }
 * 
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_View 
 * @see			ComDefaultViewCsv
 */
class KViewCsv extends KViewFile
{
	/**
	 * Character used for quoting
	 * 
	 * @var string
	 */
	public $quote = '"';
    
	/**
	 * Character used for separating fields
	 * 
	 * @var string
	 */
	public $separator = ',';
	
	/**
	 * End of line
	 * 
	 * @var string
	 */
	public $eol = "\n";
	
	/**
	 * Renders and echo's the views output
 	 *
	 * @return KViewCsv
	 */
	public function display()
	{
		$body = '';
		foreach($this->data as $row)
		{
			$body .= $this->_renderRow($row);
		}
		
		$this->assign('mimetype', 'text/csv')
		 	->assign('disposition', 'inline')
		 	->assign('body', $body);
		 	
		return parent::display();
	}
	
	protected function _renderRow($row)
    {
    	$fields = array();
        foreach($row as $value)
        {
            if ($this->_quote($value)) 
            {
                // Escape the quote character within the field (e.g. " becomes "")
                $quoted_value 	= str_replace($this->quote, $this->quote.$this->quote, $value);
                $fields[] 		= $this->quote . $quoted_value . $this->quote;
            } else {
                $fields[] 		= $value;
            }
        }

        return  implode($this->separator, $fields).$this->eol;
    }
	
    /**
     * Check if the value should be quoted
     * 
     * @param	string	Value
     * return 	boolean
     */
    protected function _quote($value)
    {
    	if(is_numeric($value)) {
    		return false;
    	}
    	
        if(strpos($value, $this->separator) !== false) { // Separator is present in field
        	return true;
        }
        
        if(strpos($value, $this->quote) !== false) { // Quote character is present in field
        	return true;
        }
        
        if (strpos($value, "\n") !== false || strpos($value, "\r") !== false ) { // Newline is present in field
        	return true;
        }
        
        if(substr($value, 0, 1) == " " || substr($value, -1) == " ") {  // Space found at beginning or end of field value
        	return true;
        }
        
        return false;
    }
	
}