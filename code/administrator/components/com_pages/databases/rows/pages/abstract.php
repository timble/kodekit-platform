<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Abstract Page Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
abstract class ComPagesDatabaseRowPageAbstract extends KObjectDecorator implements ComPagesDatabaseRowPageInterface
{
    protected $_page_xml;
    
    protected $_properties = array();
    
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        if($config->properties) {
            $this->_properties = KConfig::unbox($config->properties);
        }
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'properties' => array('params_advanced', 'params_state')
        ));
        
        parent::_initialize($config);
    }
    
    public function hasProperty($property)
    {
        return in_array($property, $this->_properties);
    }
    
    public function save()
    {
        // Set home.
        if($this->isModified('home') && $this->home == 1)
        {
            $page = $this->getService('com://admin/pages.database.table.pages')
                ->select(array('home' => 1), KDatabase::FETCH_ROW);

            $page->home = 0;
            $page->save();
        }
        
        // Update child pages if menu has been changed.
        if(!$this->isNew() && $this->isModified('pages_menu_id'))
        {
            $descendants = $this->getDescendants();
            if(count($descendants)) {
                $descendants->setData(array('pages_menu_id' => $this->pages_menu_id))->save();
            }
        }
    }
    
    public function getPageXml()
    {
        if(!isset($this->_page_xml))
        {
            $xml  = JFactory::getXMLParser('simple');
            $path = dirname($this->getIdentifier()->filepath).'/'.$this->getIdentifier()->name.'.xml';

            if(file_exists($path)) {
                $xml->loadFile($path);
            }

            $this->_page_xml = $xml;
        }

        return $this->_page_xml;
    }

    public function __get($name)
    {
        if($this->hasProperty($name))
        {
            switch($name)
            {
                case 'params_advanced':
                {
                    $params = new JParameter($this->params);
                    $state  = $this->getPageXml()->document->getElementByPath('state');

                    if($state instanceof JSimpleXMLElement) {
                        $params->setXML($state->getElementByPath('advanced'));
                    }

                    $this->params_advanced = $params;
                    $result = $params;
                } break;

                case 'params_state':
                {
                    $params = new JParameter($this->params);
                    $state  = $this->getPageXml()->document->getElementByPath('state');

                    if($state instanceof JSimpleXMLElement) {
                        $params->setXML($state->getElementByPath('params'));
                    }

                    $this->params_state = $params;
                    $result = $params;
                } break;
            }
        }
        else $result = parent::__get($name);

        return $result;
    }
}