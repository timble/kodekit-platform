<?php
/**
 * @package     Koowa_View
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * View JSON Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_View
 */
class ViewJson extends ViewAbstract
{
    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'version' => '1.0'
        ))->append(array(
            'mimetype' => 'application/json; version=' . $config->version,
        ));

        parent::_initialize($config);
    }

    /**
     * Render and return the views output
     *
     * If the view 'output' variable is empty the output will be generated based on the model data, if it set it will
     * be returned instead.
     *
     * @return string     The output of the view
     */
    public function render()
    {
        if (empty($this->_content)) {
            $this->_content = StringInflector::isPlural($this->getName()) ? $this->_getRowset() : $this->_getRow();
        }

        if (!is_string($this->_content))
        {
            // Root should be JSON object, not array
            if (is_array($this->_content) && 0 === count($this->_content)) {
                $this->_content = new \ArrayObject();
            }

            // Encode <, >, ', &, and " for RFC4627-compliant JSON, which may also be embedded into HTML.
            $this->_content = json_encode($this->_content, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
        }

        return parent::render();
    }

    /**
     * Get the list data
     *
     * @return array The array with data to be encoded to json
     */
    protected function _getRowset()
    {
        //Get the model
        $model = $this->getModel();

        //Get the route
        $route = $this->getRoute();

        //Get the model state
        $state = $model->getState();

        //Get the model paginator
        $paginator = $model->getPaginator();

        $vars = array();
        foreach ($state->toArray() as $var)
        {
            if (!$var->unique) {
                $vars[] = $var->name;
            }
        }

        $data = array(
            'version' => '1.0',
            'href' => (string)$route->setQuery($state->getValues(), true),
            'url' => array(
                'type' => 'application/json',
                'template' => (string)$route->toString(HttpUrl::BASE) . '?{&' . implode(',', $vars) . '}',
            ),
            'offset' => (int)$paginator->offset,
            'limit' => (int)$paginator->limit,
            'total' => 0,
            'items' => array(),
            'queries' => array()
        );

        if ($list = $model->getRowset())
        {
            $vars = array();
            foreach ($state->toArray() as $var)
            {
                if ($var->unique)
                {
                    $vars[] = $var->name;
                    $vars = array_merge($vars, $var->required);
                }
            }

            $name = StringInflector::singularize($this->getName());

            $items = array();
            foreach ($list as $item)
            {
                $id = $item->getIdentityColumn();

                $items[] = array(
                    'href' => (string)$this->getRoute('view=' . $name . '&id=' . $item->{$id}),
                    'url' => array(
                        'type' => 'application/json',
                        'template' => (string)$this->getRoute('view=' . $name) . '?{&' . implode(',', $vars) . '}',
                    ),
                    'data' => $item->toArray()
                );
            }

            $queries = array();
            foreach (array('first', 'prev', 'next', 'last') as $offset)
            {
                $page = $paginator->pages->{$offset};
                if ($page->active) {
                    $queries[] = array(
                        'rel' => $page->rel,
                        'href' => (string)$this->getRoute('limit=' . $page->limit . '&offset=' . $page->offset)
                    );
                }
            }

            $data = array_merge($data, array(
                'total' => $paginator->total,
                'items' => $items,
                'queries' => $queries
            ));
        }

        return $data;
    }

    /**
     * Get the item data
     *
     * @return array     The array with data to be encoded to json
     */
    protected function _getRow()
    {
        //Get the model
        $model = $this->getModel();

        //Get the route
        $route = $this->getRoute();

        //Get the model state
        $state = $model->getState();

        $vars = array();
        foreach ($state->toArray() as $var)
        {
            if ($var->unique)
            {
                $vars[] = $var->name;
                $vars = array_merge($vars, $var->required);
            }
        }

        $data = array(
            'version' => '1.0',
            'href' => (string)$route->setQuery($state->getValues(true)),
            'url' => array(
                'type' => 'application/json',
                'template' => (string)$route->toString(HttpUrl::BASE) . '?{&' . implode(',', $vars) . '}',
            ),
            'item' => array()
        );

        if ($item = $model->getRow()) {
            $data = array_merge($data, array(
                'item' => $item->toArray()
            ));
        }
        ;

        return $data;
    }

    /**
     * Get a route based on a full or partial query string.
     *
     * This function force the route to be not fully qualified and not escaped
     *
     * @param   string  $route   The query string used to create the route
     * @param   boolean $fqr     If TRUE create a fully qualified route. Default FALSE.
     * @param   boolean $escape  If TRUE escapes the route for xml compliance. Default FALSE.
     * @return  string  The route
     */
    public function getRoute($route = '', $fqr = null, $escape = null)
    {
        //If not set force to false
        if ($escape === null) {
            $escape = false;
        }

        return parent::getRoute($route, $fqr, $escape);
    }

}