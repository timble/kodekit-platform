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
 * Modules Toolbar Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */
class ComModulesControllerToolbarModules extends ComDefaultControllerToolbarDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
       
        $this->insert('divider')
			 ->insert('enable')
			 ->insert('disable');
    }
    
    protected function _commandNew(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs' => array(
                'class' => array('modal'),
                'rel'   => '{handler: \'url\', ajaxOptions:{method:\'get\'}}',
                'href'	=> 'index.php?option=com_modules&view=modules&layout=list&installed=1&tmpl=component'
            )
        ));
    }
}