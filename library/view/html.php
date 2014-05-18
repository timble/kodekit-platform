<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\View
 */
class ViewHtml extends ViewTemplate
{
    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'mimetype'         => 'text/html',
            'template_filters' => array('form'),
        ));

        parent::_initialize($config);
    }

    /**
     * Return the views output
     *
     * This function will always assign the model state to the template. Model data will only be assigned if the
     * auto_assign property is set to TRUE.
     *
     * @return string The output of the view
     */
    public function render()
    {
        $model = $this->getModel();

        //Auto-assign the state to the view
        $this->state = $model->getState();

        //Auto-assign the data from the model
        if ($this->_auto_assign)
        {
            //Get the view name
            $name = $this->getName();

            //Assign the data of the model to the view
            if (StringInflector::isPlural($name))
            {
                $this->$name = $model->getRowset();
                $this->total = $model->getTotal();
            }
            else $this->$name = $model->getRow();
        }

        return parent::render();
    }

    /**
     * Get a route based on a full or partial query string.
     *
     * This function force the route to be not fully qualified and not escaped
     *
     * @param string    $route  The query string used to create the route
     * @param boolean   $fqr    If TRUE create a fully qualified route. Default FALSE.
     * @param boolean   $escape If TRUE escapes the route for xml compliance. Default FALSE.
     * @return string The route
     */
    public function getRoute($route = '', $fqr = null, $escape = null)
    {
        //If not set force to false
        if ($fqr === null) {
            $fqr = false;
        }

        return parent::getRoute($route, $fqr, $escape);
    }
}