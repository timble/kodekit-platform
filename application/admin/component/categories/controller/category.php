<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Category Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Categories
 */
abstract class CategoriesControllerCategory extends Library\ControllerModel
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('after.save'  , array($this, 'setDefaultAttachment'));
        $this->registerCallback('after.apply'  , array($this, 'setDefaultAttachment'));
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
        	'behaviors' => array(
                'editable',
                'com:activities.controller.behavior.loggable',
                'com:attachments.controller.behavior.attachable',
                'com:languages.controller.behavior.translatable'
            ),
            'model' => 'com:categories.model.categories'
        ));
        
        parent::_initialize($config);
        
        //Force the toolbars
        $config->toolbars = array('menubar', 'com:categories.controller.toolbar.category');
    }
    
    protected function _actionRender(Library\CommandContext $context)
    {
        $view = $this->getView();
        
	    //Set the layout
        if($view instanceof Library\ViewTemplate)
	    {
	        $layout = clone $view->getIdentifier();
            $layout->name  = $view->getLayout();

            $alias = clone $layout;
            $alias->package = 'categories';

	        $this->getObject('manager')->registerAlias($layout, $alias);
	    }
	        
        return parent::_actionRender($context);
    }
    
    public function getRequest()
	{
		$request = parent::getRequest();

        $request->query->table  = $this->getIdentifier()->package;

	    return $request;
	}

    public function setDefaultAttachment(Library\CommandContext $context)
    {
        if(!$this->isAttachable()) {
            return;
        }

        $row = $context->result;

        $attachments = $this->getObject('com:attachments.model.attachments')
            ->row($row->id)
            ->table($row->getTable()->getBase())
            ->getRowset();

        // If attachments have been linked to this row but there's no default attachment ID is still empty, set the first one as default.
        if(!$row->attachments_attachment_id && count($attachments))
        {
            $top = $attachments->top();

            $row->attachments_attachment_id = $top->id;
            $row->save();
        }

        return;
    }
}