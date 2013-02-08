<?php
/**
 * @version     $Id: page.php 3216 2011-11-28 15:33:44Z kotuha $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Pages Toolbar Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesControllerToolbarPage extends ComDefaultControllerToolbarDefault
{
    public function onAfterControllerBrowse(KEvent $event)
    {
        parent::onAfterControllerBrowse($event);

        $this->addSeparator();
        $this->addEnable(array('label' => 'publish'));
        $this->addDisable(array('label' => 'unpublish'));
        $this->addSeparator();
        $this->addDefault();
    }

    protected function _commandDefault(KControllerToolbarCommand $command)
    {
        $command->label = JText::_('Make Default');

        $command->append(array(
            'attribs' => array(
                'data-action' => 'edit',
                'data-data'   => '{home:1}'
            )
        ));
    }

    protected function _commandRestore(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs' => array(
                'data-action' => 'edit',
            )
        ));
    }
    
    protected function _commandNew(KControllerToolbarCommand $command)
    {
        $menu = $this->getController()->getModel()->menu;
        $command->href = 'option=com_pages&view=page&menu='.$menu;
    }
}
