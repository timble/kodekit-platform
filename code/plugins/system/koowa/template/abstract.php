<?php
/**
 * @version		$Id$
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

 /**
 * Abstract stream wrapper to convert markup of mostly-PHP templates into PHP prior to include().
 *
 * Based in large part on the example at
 * http://www.php.net/manual/en/function.stream-wrapper-register.php
 * 
 * @author		Johan Janssens <johan@joomlatools.org>
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
	 * Adds one or multiple rules to be converted
	 * 
	 * @param $rules array	Associative array of rules(es) to be replaced.
	 */
	public static function addRules($rules = array()) 
	{
		self::$_rules = array_merge(self::$_rules, $rules);
	}
	
	/**
	 * Adds one or multiple rules to be converted
	 * 
	 * @param $rules array	Associative array of rules(es) to be replaced.
	 */
	public static function delRules($rules = array()) 
	{
		self::$_rules = array_diff_assoc(self::$_rules, $rules);
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
         * Convert <?= ?> to long-form <?php echo ?>
         * 
         * We could also convert <%= like the real T_OPEN_TAG_WITH_ECHO
         * but that's not necessary.
         * 
         * It might be nice to also convert PHP code blocks <? ?> but 
         * let's quit while we're ahead.  It's probably better to keep 
         * the <?php for larger code blocks but that's your choice.  If
         * you do go for it, explicitly check for <?xml as this will
         * probably be the biggest headache.
         */
        if (! ini_get('short_open_tag')) 
		{
			// convert "<?=" to "<?php echo"
	        $find = '/\<\?\=\s?(.*?)\?>/';
	        $replace = "<?php echo \$1 ?>";
	        $this->_data = preg_replace($find, $replace, $this->_data);
	        
	        // convert "<?" to "<?php"
	        $find = '/\<\?\s(.*?)\?>/';
	        $replace = "<?php \$1 ?>";
	        $this->_data = preg_replace($find, $replace, $this->_data);
        }
		
		/**
         * Convert user defined rules
         * 
         * This is done before the standard replacements are done to offer
         * the most flexibility.
         */
		$this->_data = str_replace(array_keys(self::$_rules), array_values(self::$_rules), $this->_data);
		        
        /**
         * Convert @$ to $this->
         * 
         * We could make a better effort at only finding @$ between <?php ?>
         * but that's probably not necessary as @$ doesn't occur much in the wild
         * and there's a significant performance gain by using str_replace().
         */
        $this->_data = str_replace(array('@$', '@'), '$this->', $this->_data);
        
        /**
         * file_get_contents() won't update PHP's stat cache, so performing
         * another stat() on it will hit the filesystem again.  Since the file
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
}