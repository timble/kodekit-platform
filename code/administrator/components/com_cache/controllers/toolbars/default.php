<?php
/**
 * @version     $Id: sections.php 592 2011-03-16 00:30:35Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Toolbar Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
class ComCacheControllerToolbarDefault extends ComDefaultControllerToolbarDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
       
        $this->setTitle('Cache Manager')
		     ->addDelete('delete')
		     ->addPurge();
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'auto_defaults' => false
        ));
        
        parent::_initialize($config);
    }
    
    protected function _commandPurge(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs' => array(
                'data-novalidate'   => 'novalidate'
            )
        ));
    }
}