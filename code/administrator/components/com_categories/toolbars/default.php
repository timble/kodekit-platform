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
 * Default Toolbar Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories   
 */
class ComCategoriesToolbarDefault extends ComDefaultToolbarDefault
{
    protected function _commandNew(KToolbarCommand $command)
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