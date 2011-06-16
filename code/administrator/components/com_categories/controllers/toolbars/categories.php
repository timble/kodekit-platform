<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Categories Toolbar Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories   
 */
class ComCategoriesControllerToolbarCategories extends ComDefaultControllerToolbarDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
       
        $this->insert('divider')     
			 ->insert('enable', array('text' => 'publish'))
			 ->insert('disable', array('text' => 'unpublish'));
    }
    
    protected function _commandNew(KControllerToolbarCommand $command)
    {
        $option  = KRequest::get('get.option', 'cmd');
		$view	 = KInflector::singularize(KRequest::get('get.view', 'cmd'));
		$section = KRequest::get('get.section','string');
		
        $command->append(array(
            'attribs' => array(
                'href'     => JRoute::_('index.php?option='.$option.'&view='.$view.'&section='.$section )
            )
        ));
    }
}