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
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model' => 'com:categories.model.categories'
        ));

        parent::_initialize($config);
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

        $request->query->table     = $this->getIdentifier()->package;
        $request->query->access    = $this->getUser()->isAuthentic();
        $request->query->published = 1;

	    return $request;
	}
}