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
 * Export to CSV button for a toolbar
 * 
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 * @subpackage  Button
 */
class ComDefaultToolbarButtonCsv extends KToolbarButtonGet
{
 	/**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'attribs' => array(
                'href' => $this->getLink()
            )
        ));
        
        parent::_initialize($config);
    }
    
    public function getLink()
    {
        // Unset limit and offset
        $url = clone KRequest::url();
        $query = parse_str($url->getQuery(), $vars);
        
        unset($vars['limit']);
        unset($vars['offset']);
        
        $vars['format'] = 'csv';
        $url->setQuery(http_build_query($vars));
        
        return (string) $url;
    }
}