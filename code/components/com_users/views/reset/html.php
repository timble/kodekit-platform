<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Reset HTML View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersViewResetHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $this->parameters = $this->getParameters();
		
        KFactory::get('lib.joomla.document')->setTitle($this->parameters->get('page_title')); 
        return parent::display();
    }
    
    public function getParameters()
    {
         return KFactory::get('lib.joomla.application')->getParams();
    }
}