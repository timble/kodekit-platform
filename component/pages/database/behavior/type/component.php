<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Component Typable Database Behavior
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseBehaviorTypeComponent extends DatabaseBehaviorTypeAbstract
{
    protected $_title;

    public static function getInstance(Library\ObjectConfig $config, Library\ObjectManagerInterface $manager)
    {
        $instance = parent::getInstance($config, $manager);

        if (!$manager->isRegistered($config->object_identifier)) {
            $manager->setObject($config->object_identifier, $instance);
        }

        return $manager->getObject($config->object_identifier);
    }

    public function getTitle()
    {
        if(!isset($this->_type_title)) {
            $this->_type_title = $this->getObject('translator')->translate('Component');
        }

        return $this->_title;
    }

    public function getDescription()
    {
        $query       = $this->getLink()->query;
        $description = $this->component ? ucfirst($this->component) : substr($query['component']);

        $translator = $this->getObject('translator');

        if(isset($query['view'])) {
            $description .= ' &raquo; '. $translator(ucfirst($query['view']));
        }

        if(isset($query['layout'])) {
            $description .= ' / '. $translator(ucfirst($query['layout']));
        }

        return $description;
    }

    public function getLink()
    {
        $link                  = $this->getObject('lib:http.url', array('url' => '?' . $this->link_url));
        $link->query['Itemid'] = $this->id;

        return $link;
    }

    public function getParams($group)
    {
        return $this->{'_get' . ucfirst($group) . 'Params'}();
    }

    protected function _getPageParams()
    {
        $file = __DIR__ . '/component.xml';

        $xml = \JFactory::getXMLParser('simple');
        $xml->loadFile($file);

        $params = new \JParameter($this->params);
        $params->setXML($xml->document->getElementByPath('state/params'));

        return $params;
    }

    protected function _getUrlParams()
    {
        $state  = $this->_getPageXml()->document->getElementByPath('state');
        $params = new \JParameter(null);

        if ($state instanceof \JSimpleXMLElement) {
            $params->setXML($state->getElementByPath('url'));

            if ($this->link_url) {
                $params->loadArray($this->getLink()->query);
            }
        }

        return $params;
    }

    protected function _getLayoutParams()
    {
        $state  = $this->_getPageXml()->document->getElementByPath('state');
        $params = new \JParameter(null);

        if ($state instanceof \JSimpleXMLElement) {
            $params->setXML($state->getElementByPath('params'));

            if ($this->link_url) {
                $params->loadArray($this->getLink()->query);
            }
        }

        return $params;
    }

    protected function _getPageXml()
    {
        $type  = $this->getLink();
        $query = $type->getQuery(true);

        $component = $query['component'];
        $view      = $query['view'];
        $layout    = isset($query['layout']) ? $query['layout'] : 'default';

        $path = $this->getObject('object.bootstrapper')->getApplicationPath('site') . '/' . $component . '/view/' . $view . '/templates/' . $layout . '.xml';

        $xml = \JFactory::getXMLParser('simple');
        if (file_exists($path)) {
            $xml->loadFile($path);
        }

        return $xml;
    }

    protected function _setLinkBeforeSave(Library\DatabaseContext $context)
    {
        if ($this->isModified('link_url'))
        {
            // Set link.
            parse_str($this->link_url, $query);

            if ($this->urlparams) {
                $query += $this->urlparams;
            }

            $this->link_url  = http_build_query($query);
            $this->component = $query['component'];
        }
    }

    protected function _beforeInsert(Library\DatabaseContext $context)
    {
        $this->_setLinkBeforeSave($context);
    }

    protected function _beforeUpdate(Library\DatabaseContext $context)
    {
        $this->_setLinkBeforeSave($context);
    }
}
