<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-activities for the canonical source repository
 */

namespace Kodekit\Component\Activities;

use Kodekit\Library;

/**
 * Activities JSON View.
 *
 * JSON view has support for the 'stream' layout. If layout is stream (...&layout=stream) the output will be rendered
 * according to the Activity Streams Specification.
 *
 * @link     http://activitystrea.ms/specs/json/1.0/#json
 *
 * @author   Arunas Mazeika <https://github.com/amazeika>
 * @package  Kodekit\Component\Activities
 */
class ViewActivitiesJson extends Library\ViewJson
{
    /**
     * JSON layout [stream].
     *
     * @var mixed
     */
    protected $_layout;

    /**
     * Activities renderer.
     *
     * @var mixed
     */
    protected $_renderer;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config Configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        $this->_layout = $config->layout;

        parent::__construct($config);

        $this->_renderer = $config->renderer;
    }

    /**
     * Initializes the options for the object.
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param Library\ObjectConfig $config Configuration options.
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'renderer'  => 'activity'
        ));

        parent::_initialize($config);
    }

    /**
     * Get the entity data
     *
     * @link http://activitystrea.ms/specs/json/1.0/#json See JSON serialization.
     *
     * @param Library\ModelEntityInterface $entity The model entity.
     * @return array The array with data to be encoded to JSON.
     */
    protected function _getEntity(Library\ModelEntityInterface $entity)
    {
        if ($this->_layout == 'stream')
        {
            $activity = $entity;
            $renderer = $this->getRenderer();

            $item = array(
                'id'        => $activity->getActivityId(),
                'title'     => $renderer->render($activity, array('escaped_urls' => false, 'fqr' => true)),
                'story'     => $renderer->render($activity, array('html' => false)),
                'published' => $activity->getActivityPublished()->format('c'),
                'verb'      => $activity->getActivityVerb(),
                'format'    => $activity->getActivityFormat()
            );

            if ($icon = $activity->getActivityIcon()) {
                $item['icon'] = $this->_getMediaLinkData($icon);
            }

            foreach ($activity->objects as $name => $object) {
                $item[$name] = $this->_getObjectData($object);
            }
        }
        else
        {
            $item = $entity->toArray();
            if (!empty($this->_fields)) {
                $item = array_intersect_key($item, array_flip($this->_fields));
            }
        }

        return $item;
    }

    /**
     * Get the activity renderer.
     *
     * @throws \UnexpectedValueException if renderer has the wrong type.
     * @return ActivityRendererInterface The activity renderer.
     */
    public function getRenderer()
    {
        if (!$this->_renderer instanceof Library\TemplateHelperInterface)
        {
            // Make sure we have an identifier
            if(!($this->_renderer instanceof Library\ObjectIdentifier)) {
                $this->setRenderer($this->_renderer);
            }

            $this->_renderer = $this->getObject($this->_renderer);

            if(!$this->_renderer instanceof ActivityRendererInterface)
            {
                throw new \UnexpectedValueException(
                    'Renderer: '.get_class($this->_renderer).' does not implement ActivityRendererInterface'
                );
            }

            $this->_renderer->getTemplate()->registerFunction('url', array($this, 'getUrl'));
        }

        return $this->_renderer;
    }

    /**
     * Set the activity renderer.
     *
     * @param mixed $renderer An activity renderer instance, identifier object or string.
     * @return ViewActivitiesJson
     */
    public function setRenderer($renderer)
    {
        if(!$renderer instanceof ActivityRendererInterface)
        {
            if(is_string($renderer) && strpos($renderer, '.') === false )
            {
                $identifier         = $this->getIdentifier()->toArray();
                $identifier['path'] = array('template', 'helper');
                $identifier['name'] = $renderer;

                $identifier = $this->getIdentifier($identifier);
            }
            else $identifier = $this->getIdentifier($renderer);

            $renderer = $identifier;
        }

        $this->_renderer = $renderer;

        return $this;
    }

    /**
     * Activity object data getter.
     *
     * @param ActivityObjectInterface $object The activity object.
     * @return array The object data.
     */
    protected function _getObjectData(ActivityObjectInterface $object)
    {
        $data = $object->toArray();

        // Make sure we get fully qualified URLs.
        if ($url = $object->getUrl()) {
            $data['url'] = $this->_getUrl($url);
        }

        $attachments = array();

        // Handle attachments recursively.
        foreach ($object->getAttachments() as $attachment) {
            $attachments[] = $this->_getObjectData($attachment);
        }

        $data['attachments'] = $attachments;

        // Convert date objects to date time strings.
        foreach (array('published', 'updated') as $property)
        {
            $method = 'get' . ucfirst($property);

            if ($date = $object->$method()) {
                $data[$property] = $date->format('M d Y H:i:s');
            }
        }

        foreach ($object as $key => $value)
        {
            if ($value instanceof ActivityObjectInterface) {
                $data[$key] = $this->_getObjectData($value);
            }

            if ($value instanceof ActivityMedialinkInterface) {
                $data[$key] = $this->_getMedialinkData($value);
            }
        }

        return $this->_cleanupData($data);
    }

    /**
     * Activity medialink data getter.
     *
     * @param ActivityMedialinkInterface $medialink The medialink object.
     * @return array The object data.
     */
    protected function _getMedialinkData(ActivityMedialinkInterface $medialink)
    {
        $data = $medialink->toArray();

        $data['url'] = $this->_getUrl($medialink->getUrl());

        return $this->_cleanupData($data);
    }

    /**
     * Removes entries with empty values.
     *
     * @param array $data The data to cleanup.
     * @return array The cleaned up data.
     */
    protected function _cleanupData(array $data = array())
    {
        $clean = array();

        foreach ($data as $key => $value)
        {
            if (!empty($value)) {
                $clean[$key] = $value;
            }
        }

        return $clean;
    }

    /**
     * URL getter.
     *
     * Provides a fully qualified and un-escaped URL provided a URL object.
     *
     * @param Library\HttpUrlInterface $url The URL.
     * @return string The fully qualified un-escaped URL.
     */
    protected function _getUrl(Library\HttpUrlInterface $url)
    {
        if (!$url->getHost() && !$url->getScheme()) {
            $url->setUrl($this->getUrl()->toString(Library\HttpUrl::AUTHORITY));
        }

        return $url->toString(Library\HttpUrl::FULL, false);
    }
}
