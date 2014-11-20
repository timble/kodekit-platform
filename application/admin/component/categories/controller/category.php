<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Categories;

/**
 * Category Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Categories
 */
abstract class CategoriesControllerCategory extends Categories\ControllerCategory
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addCommandCallback('after.save'  , 'setDefaultAttachment');
        $this->addCommandCallback('after.apply' , 'setDefaultAttachment');
    }
    
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
        	'behaviors' => array(
                'editable', 'persistable',
                'com:activities.controller.behavior.loggable',
                'com:attachments.controller.behavior.attachable',
            ),
        ));
        
        parent::_initialize($config);

        //Force the toolbars
        $config->toolbars = array('menubar', 'com:categories.controller.toolbar.category');
    }

    public function setDefaultAttachment(Library\CommandContext $context)
    {
        if($this->isAttachable()) 
        {
            $entity = $context->result;

            $attachment = $this->getObject('com:attachments.model.attachments')
                ->row($entity->id)
                ->table($entity->getTable()->getBase())
                ->fetch();

            // If attachments have been linked to this row but there's no default attachment ID is still empty, set the first one as default.
            if(!$entity->attachments_attachment_id && count($attachment))
            {
                $entity->attachments_attachment_id = $entity->id;
                $entity->save();
            }
        }
    }
}