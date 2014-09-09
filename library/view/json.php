<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Json View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\View
 */
class ViewJson extends ViewAbstract
{
    /**
     * JSON API version
     *
     * @var string
     */
    protected $_version;

    /**
     * A list of fields to use in the response. Blank for all.
     *
     * Comes from the comma separated "fields" value in the request
     *
     * @var array
     */
    protected $_fields = array();

    /**
     * A list of text fields in the row
     *
     * URLs will be converted to fully qualified ones in these fields.
     *
     * @var string
     */
    protected $_text_fields;

    /**
     * Constructor
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_version = $config->version;
        $this->_plural  = $config->plural;

        $this->_text_fields = ObjectConfig::unbox($config->text_fields);
        $this->_fields      = ObjectConfig::unbox($config->fields);

        $query = $this->getUrl()->getQuery(true);
        if (!empty($query['fields']))
        {
            $fields = explode(',', rawurldecode($query['fields']));
            $this->_fields = array_merge($this->_fields, $fields);
        }
    }

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
            'version'     => '1.0',
            'fields'      => array(),
            'text_fields' => array('description', 'introtext'), // Links are converted to absolute ones in these fields
        ))->append(array(
            'mimetype' => 'application/json; version=' . $config->version,
        ));

        parent::_initialize($config);
    }

    /**
     * Render and return the views output
     *
     * If the view 'content'  is empty the output will be generated based on the model data, if it set it will
     * be returned instead.
     *
     * @param ViewContext	$context A view context object
     * @return string A RFC4627-compliant JSON string, which may also be embedded into HTML.
     */
    protected function _actionRender(ViewContext $context)
    {
        if (empty($this->_content))
        {
            $this->_content = $this->_renderData();
            $this->_processLinks($this->_content);
        }

        //Serialise
        if (!is_string($this->_content))
        {
            // Root should be JSON object, not array
            if (is_array($this->_content) && count($this->_content) === 0) {
                $this->_content = new \ArrayObject();
            }

            // Encode <, >, ', &, and " for RFC4627-compliant JSON, which may also be embedded into HTML.
            $this->_content = json_encode($this->_content, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
        }

        return parent::_actionRender($context);
    }

    /**
     * Force the route to fully qualified and not escaped by default
     *
     * @param   string|array    $route   The query string used to create the route
     * @param   boolean         $fqr     If TRUE create a fully qualified route. Default TRUE.
     * @param   boolean         $escape  If TRUE escapes the route for xml compliance. Default FALSE.
     * @return 	DispatcherRouterRoute 	The route
     */
    public function getRoute($route = '', $fqr = true, $escape = false)
    {
        return parent::getRoute($route, $fqr, $escape);
    }

    /**
     * Returns the JSON data
     *
     * It converts relative URLs in the content to relative before returning the result
     *
     * @return array
     */
    protected function _renderData()
    {
        $model  = $this->getModel();
        $data   = $this->_getCollection($model->fetch());
        $output = array(
            'version' => $this->_version,
            'links' => array(
                'self' => array(
                    'href' => $this->_getPageUrl(),
                    'type' => $this->mimetype
                )
            ),
            'entities' => $data
        );

        if ($this->isCollection())
        {
            $total  = $model->count();
            $limit  = (int) $model->getState()->limit;
            $offset = (int) $model->getState()->offset;

            $output['meta'] = array(
                'offset'   => $offset,
                'limit'    => $limit,
                'total'	   => $total
            );

            if ($limit && $total-($limit + $offset) > 0)
            {
                $output['links']['next'] = array(
                    'href' => $this->_getPageUrl(array('offset' => $limit+$offset)),
                    'type' => $this->mimetype
                );
            }

            if ($limit && $offset && $offset >= $limit)
            {
                $output['links']['previous'] = array(
                    'href' => $this->_getPageUrl(array('offset' => max($offset-$limit, 0))),
                    'type' => $this->mimetype
                );
            }
        }

        return $output;
    }

    /**
     * Returns the JSON representation of an entity collection
     *
     * @param  ModelEntityInterface $collection
     * @return array
     */
    protected function _getCollection(ModelEntityInterface $collection)
    {
        $result = array();

        foreach ($collection as $row) {
            $result[] = $this->_getEntity($row);
        }

        return $result;
    }

    /**
     * Get the item data
     *
     * @param ModelEntityInterface  $entity   Document entity
     * @return array The array with data to be encoded to json
     */
    protected function _getEntity(ModelEntityInterface $entity)
    {
        $method = '_get'.ucfirst($entity->getIdentifier()->name);

        if ($method !== '_getEntity' && method_exists($this, $method)) {
            $data = $this->$method($entity);
        } else {
            $data = $entity->toArray();
        }

        if (!empty($this->_fields)) {
            $data = array_intersect_key($data, array_flip($this->_fields));
        }

        if (!isset($data['links'])) {
            $data['links'] = array();
        }

        if (!isset($data['links']['self']))
        {
            $data['links']['self'] = array(
                'href' => $this->_getEntityRoute($entity),
                'type' => $this->mimetype
            );
        }

        return $data;
    }

    /**
     * Get the entity link
     *
     * @param ModelEntityInterface  $entity
     * @return string
     */
    protected function _getEntityRoute(ModelEntityInterface $entity)
    {
        $package = $this->getIdentifier()->package;
        $view    = $entity->getIdentifier()->name;

        return $this->getRoute(sprintf('component=%s&view=%s&slug=%s&format=json', $package, $view, $entity->slug));
    }

    /**
     * Get the page link
     *
     * @param  array  $query Additional query parameters to merge
     * @return string
     */
    protected function _getPageUrl(array $query = array())
    {
        $url = $this->getUrl();

        if ($query) {
            $url->setQuery(array_merge($url->getQuery(true), $query));
        }

        return (string) $url;
    }

    /**
     * Converts links in an array from relative to absolute
     *
     * @param array $array Source array
     */
    protected function _processLinks(array &$array)
    {
        $base = $this->getUrl()->toString(HttpUrl::AUTHORITY);

        foreach ($array as $key => &$value)
        {
            if (is_array($value)) {
                $this->_processLinks($value);
            }
            elseif ($key === 'href')
            {
                if (substr($value, 0, 4) !== 'http') {
                    $array[$key] = $base.$value;
                }
            }
            elseif (in_array($key, $this->_text_fields)) {
                $array[$key] = $this->_processText($value);
            }
        }
    }

    /**
     * Convert links in a text from relative to absolute and runs them through JRoute
     *
     * @param string $text The text processed
     * @return string Text with converted links
     */
    protected function _processText($text)
    {
        $matches = array();

        preg_match_all("/(href|src)=\"(?!http|ftp|https|mailto|data)([^\"]*)\"/", $text, $matches, PREG_SET_ORDER);

        foreach ($matches as $match)
        {
            $route = $this->getObject('lib:dispatcher.router.route', array(
                'url'    => $match[2],
                'escape' => false
            ));

            //Add the host and the schema
            $route->scheme = $this->getUrl()->scheme;
            $route->host   = $this->getUrl()->host;

            $text = str_replace($match[0], $match[1].'="'.$route.'"', $text);
        }

        return $text;
    }
}