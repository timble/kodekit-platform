<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Toolbar Button
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 */
class ComLanguagesToolbarDefault extends ComDefaultToolbarDefault
{
    protected function _commandSet(KToolbarCommand $command)
    {
        $command->text = JText::_('Make Default');
        
        $command->append(array(
        	'attribs' => array(
                'data-action' => 'edit',
                'data-data'   => '{default:1}'
            )
        ));
    }
}