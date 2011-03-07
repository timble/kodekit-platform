<?php
/**
* @version      $Id$
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
abstract class KToolbarButtonPost extends KToolbarButtonAbstract
{
    protected $_fields = array();
    
    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        $this->setMethod('post');
    }
    
    public function getOnClick()
    {
        $js = '';
        foreach($this->_fields as $name => $value) {
            $js .= "Koowa.Form.addField('$name', '$value');";
        }
        $js .= "Koowa.Form.submit('{$this->_method}');";
        return $js;
    }
    
    public function setField($name, $value)
    {
        $this->_fields[$name] = $value;
        return $this;
    }
}