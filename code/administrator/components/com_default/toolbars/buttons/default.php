<?php
/**
* @version      $Id: post.php 2876 2011-03-07 22:19:20Z johanjanssens $
* @category		Koowa
* @package		Koowa_Toolbar
* @subpackage	Button
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * POST button class for a toolbar
 * 
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 * @subpackage  Button
 */
class ComDefaultToolbarButtonDefault extends KToolbarButtonPost
{
   /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'token_value' => JUtility::getToken(),	
        ));

        parent::_initialize($config);
    }
    
    public function getOnClick()
    {
        $url  = KRequest::url();
        $name = $this->_identifier->name;
        
        $json = "{method:'post', url:'$url', element:'adminForm', params:{action:'$name', '$this->_token_name':'$this->_token_value'}}";

        return 'new Koowa.Form('.$json.').submit();';
    }
}