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
 * Banner HTML View Class
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
 
class ComBannersViewBannerHtml extends ComBannersViewHtml 
{
    public function display()
    {
        $title = $this->getModel()->get('id') ?  'Edit Banner' : 'New Banner';
		$this->getToolbar()->setTitle($title);
       
        return parent::display();
    }
}