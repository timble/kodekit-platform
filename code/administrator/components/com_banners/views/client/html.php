<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Banners HTML View Class - Client
 *
 * @author      Cristiano Cucco <cristiano.cucco at gmail dot com>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
 
class ComBannersViewClientHtml extends ComBannersViewHtml
{
    public function display()
    {
        $id = $this->getModel()->getState()->id;
        
        KFactory::get('admin::com.banners.toolbar.client', 
            array('title' => JText::_($id ? 'Edit Client' : 'New Client'))
        ); 
       
        return parent::display();
    }
}