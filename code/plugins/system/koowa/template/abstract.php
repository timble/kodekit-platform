<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

 /**
  * Abstract stream wrapper to convert markup of mostly-PHP templates into PHP prior to include().
  *
  * Based in large part on the example at
  * http://www.php.net/manual/en/function.stream-wrapper-register.php
  * 
  * @author		Johan Janssens <johan@koowa.org>
  * @category	Koowa
  * @package		Koowa_Template
  */
abstract class KTemplateAbstract
{
    /**
     * Current stream position.
     *
     * @var int
     */
    private $_pos = 0;

    /**
     * Data for streaming.
     *
     * @var string
     */
    private $_data;

    /**
     * Stream stats.
     *
     * @var array
     */
    private $_stat;
	
	/**
	 *  Associative array value to be replaced in the stream
	 *  
	 *  @var array
	 */
	protected static $_rules = array();
	
	/**
	 * Adds one or multiple rules for template transformation
	 * 
	 * @param	array	Array of KTemplateRuleInterface objects
	 */
	public static function addRules(array $rules = array()) 
	{
		foreach($rules as $rule)
		{
			$class = get_class($rule);
			if(!($rule instanceof KTemplateFilterInterface)) {
				throw new KTemplateException("Template rule $class does not implement KTemplateFilterInterface");
			}
			self::$_rules[$class] = $rule;
		}
	}
	
	/**
	 * Remove rules 
	 * 
	 * @param $rules array	Associative array of rule class names
	 */
	public static function delRules(array $rules = array())  
	{
		self::$_rules = array_diff_assoc(self::$_rules, array_flip($rules));
	}
	
	
    /**
     * Opens the script file and converts markup.
     */
    public function stream_open($path, $mode, $options, $opened_path) 
	{   
        // get the view script source
        $path = str_replace('tmpl://', '', $path);
        $this->_data = file_get_contents($path);
        
        /**
         * If reading the file failed, update our local stat store
         * to reflect the real stat of the file, then return on failure
         */
        if ($this->_data===false) {
            $this->_stat = stat($path);
            return false;
        }

        /**
         * Pass the data through each registered rule
         */
        foreach(self::$_rules as $rule)  {
        	$rule->parse($this->_data); 
        }
     
        
        /**
         * file_get_contents() won't update PHP's stat cache, so performing
         * another stat() on it will hit the filesystem again. Since the file
         * has been successfully read, avoid this and just fake the stat
         * so include() is happy.
         */
        $this->_stat = array('mode' => 0100777,
                            'size' => strlen($this->_data));

        return true;
    }

    
    /**
     * Reads from the stream.
     */
    public function stream_read($count) 
	{
        $ret = substr($this->_data, $this->_pos, $count);
        $this->_pos += strlen($ret);
        return $ret;
    }

    
    /**
     * Tells the current position in the stream.
     */
    public function stream_tell() {
        return $this->_pos;
    }

    
    /**
     * Tells if we are at the end of the stream.
     */
    public function stream_eof() {
        return $this->_pos >= strlen($this->_data);
    }

    
    /**
     * Stream statistics.
     */
    public function stream_stat() {
        return $this->_stat;
    }

    
    /**
     * Seek to a specific point in the stream.
     */
    public function stream_seek($offset, $whence) 
	{
        switch ($whence) 
		{
            case SEEK_SET:
                if ($offset < strlen($this->_data) && $offset >= 0) {
                $this->_pos = $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->_pos += $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            case SEEK_END:
                if (strlen($this->_data) + $offset >= 0) {
                    $this->_pos = strlen($this->_data) + $offset;
                    return true;
                } else {
                    return false;
                }
                break;

            default:
                return false;
        }
    }
    
    /**
     * Url_stat implementation
     * 
     * Prevents "url_stat is not implemented" messages on some systems 
     * @see	http://be.php.net/manual/en/function.stream-wrapper-register.php
     * @see http://www.mail-archive.com/internals@lists.php.net/msg03887.html
     *
     * @param 	string	Path
     * @param	int		Flags
     * @return 	array
     */
    public function url_stat($path, $flags = 0)
    {
        return $this->stream_stat();
    }
    
}