<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;
use Kodekit\Component\Categories;

/**
 * Category Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Articles
 */
class ControllerCategory extends Categories\ControllerCategory
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

    public function setDefaultAttachment(Library\ControllerContext $context)
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