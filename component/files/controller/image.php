<?php
/**
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Image Controller Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/terryvisser>
 * @package     Nooku_Server
 * @subpackage  Comments
 */
abstract class ControllerImage extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {

        $config->append(array(
            'model' => 'com:files.model.images',

        ));

        parent::_initialize($config);
    }

    protected function _actionRender(Library\CommandContext $context)
    {
        $view = $this->getView();

        //Alias the view layout
        if($view instanceof Library\ViewTemplate)
        {
            $layout = clone $view->getIdentifier();
            $layout->name  = $view->getLayout();

            $alias = clone $layout;
            $alias->package = 'files';

            $this->getObject('manager')->registerAlias($layout, $alias);
        }

        return parent::_actionRender($context);
    }

    public function getRequest()
    {
        $request = parent::getRequest();

        //Force set the 'container' in the request if it isn't set
        if(!$request->query->container){
            $request->query->container = 'files-files';
        }


        return $request;
    }


}
