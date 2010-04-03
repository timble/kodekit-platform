<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_View
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Export a view as a CSV file
 * 
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_View 
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
	 * Initializes the options for the object
	 *
	 * Called from {@link __construct()} as a first step of object instantiation.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 * @return  void
	 */
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'mimetype'	  => 'text/csv',
			'disposition' => 'inline',
			'quote'		  => '"',
			'separator'	  => ',',
			'eol'		  => '\n'
       	));
       	
       	parent::_initialize($config);
    }
	
	/**
	 * Renders and echo's the views output
 	 *
	 * @return KViewCsv
	 */
	public function display()
	{
		//Get the rowset
		$rowset = KFactory::get($this->getModel())->getList();
		
		// Header
		$this->output = $this->_renderRow(array_keys($rowset->getColumns()));
		
		// Data
		foreach($rowset as $row) {
			$this->output .= $this->_arrayToString($row->getData());
		}
	 	
		return parent::display();
	}
		
	/**
     * Render 
     * 
     * @param	string	Value
     * return 	boolean
     */
	protected function _arrayToString($data)
    {
    	$fields = array();
        foreach($data as $value)
        {
            if ($this->_quoteValue($value)) 
            {
                // Escape the quote character within the field (e.g. " becomes "")
                $quoted_value = str_replace($this->quote, $this->quote.$this->quote, $value);
                $fields[] 	  = $this->quote . $quoted_value . $this->quote;
            } 
            else $fields[] = $value;
        }

        return  implode($this->separator, $fields).$this->eol;
    }
	
    /**
     * Check if the value should be quoted
     * 
     * @param	string	Value
     * return 	boolean
     */
    protected function _quoteValue($value)
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