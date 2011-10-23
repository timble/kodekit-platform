<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Contact Toolbar Class
 *
 * @author      Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts   
 */
class ComContactsControllerToolbarContact extends ComDefaultControllerToolbarDefault
{
    public function onAfterControllerBrowse(KEvent $event)
    {    
        parent::onAfterControllerBrowse($event);
        
        $this->addSeparator()     
			 ->addEnable(array('label' => 'publish'))
			 ->addDisable(array('label' => 'unpublish'));
    }  
}