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
 * Default HTML View Class
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
class ComBannersViewHtml extends ComDefaultViewHtml 
{
    public function __construct(KConfig $config)
    {
        $config->views = array(
            'banners'       => JText::_('Banners'),
        );
        
        JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&view=categories&section=com_banner');
        
        parent::__construct($config);
    }
}
