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
 * New button class for a toolbar
 * 
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 * @subpackage  Button
 */
class ComDefaultToolbarButtonNew extends KToolbarButtonGet
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
            'attribs' => array(
                'href' => JRoute::_($this->getLink())
            )
        ));

        parent::_initialize($config);
    }

    public function getLink()
    {
        $option = KRequest::get('get.option', 'cmd');
        $view   = KInflector::singularize(KRequest::get('get.view', 'cmd'));
        
        return 'index.php?option='.$option.'&view='.$view;
    }
}