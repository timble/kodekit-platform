<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sections Toolbar Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */
class ComSectionsControllerToolbarSections extends ComDefaultControllerToolbarDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
       
        $this->insert('divider')     
			 ->insert('enable', array('label' => 'publish')))
			 ->insert('disable', array('label' => 'unpublish')));
    }
    
    protected function _commandNew(KControllerToolbarCommand $command)
    {
        $option  = KRequest::get('get.option', 'cmd');
		$view	 = KInflector::singularize(KRequest::get('get.view', 'cmd'));
	
        $command->append(array(
            'attribs' => array(
                'href'     => JRoute::_('index.php?option='.$option.'&view='.$view.'&scope=content' )
            )
        ));
    }
}