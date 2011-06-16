<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Articles Toolbar Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesControllerToolbarArticles extends ComDefaultControllerToolbarDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
       
        $this->insert('divider')
             ->insert('publish')
             ->insert('unpublish')
             ->insert('divider')
             ->insert('archive')
             ->insert('unarchive')
             ->insert('divider')
             ->insert('preferences');
    }
    
    /*public function getToolbar()
    {
        $name = $this->getName();

        $identifier       = clone $this->_identifier;
        $identifier->path = array('controller', 'toolbar');

        if($this->getModel()->getState()->deleted) {
            $identifier = 'admin::com.versions.controller.toolbar.default';
        } else {
            $identifier->name = $name;
        }

        return KFactory::get($identifier);
    }*/
    
    protected function _commandPublish(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'  => array(
                'data-action' => 'edit',
                'data-data'   => '{state:1}'
            )
        )); 
    }
    
    protected function _commandUnpublish(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'  => array(
                'data-action' => 'edit',
                'data-data'   => '{state:0}'
            )
        )); 
    }
    
    protected function _commandArchive(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'  => array(
                'data-action' => 'edit',
                'data-data'   => '{state:-1}'
            )
        )); 
    }
    
    protected function _commandUnarchive(KControllerToolbarCommand $command)
    {
        $command->append(array(
            'attribs'  => array(
                'data-action' => 'edit',
                'data-data'   => '{state:0}'
            )
        )); 
    }
}