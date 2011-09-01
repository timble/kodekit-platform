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
 * User HTML View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersViewUserHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $this->parameters = $this->getParameters();
		
        KFactory::get('joomla:document')->setTitle($this->parameters->get('page_title')); 
        return parent::display();
    }
    
    public function getParameters()
    {
       return KFactory::get('joomla:application')->getParams();
    }
}