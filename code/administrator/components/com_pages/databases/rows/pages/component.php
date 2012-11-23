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
 * Component Page Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesDatabaseRowPageComponent extends ComPagesDatabaseRowPageAbstract
{
    protected $_type;
    
    protected $_component_xml;
    
    public function __construct($config)
    {
        parent::__construct($config);
        
        if($config->state && $config->state->type) {
            $this->_type = $config->state->type;
        }
    } 
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'properties' => array('type_description', 'type_title', 'link', 'params_page', 'params_component', 'params_url')
        ));
        
        parent::_initialize($config);
    }
    
    public function setProperty($property)
    {
        switch($property)
        {
            case 'type_description':
            {
                $query       = $this->link->query;
                $description = $this->component_name ? ucfirst(substr($this->component_name, 4)) : ucfirst(substr($query['option'], 4));

                if(isset($query['view'])) {
                    $description .= ' &raquo; '.JText::_(ucfirst($query['view']));
                }

                if(isset($query['layout'])) {
                    $description .= ' / '.JText::_(ucfirst($query['layout']));
                }

                $this->type_description = $description;

            } break;

            case 'type_title':
                $this->type_title = JText::_('Component');
                break;

            case 'link':
                $this->link = $this->getService('koowa:http.url', array('url' => $this->link_url));
                break;

            case 'params_page':
            {
                $file = dirname($this->getIdentifier()->filepath).'/component.xml';
                
                $xml = JFactory::getXMLParser('simple');
                $xml->loadFile($file);

                $params = new JParameter($this->params);
                $params->setXML($xml->document->getElementByPath('state/params'));

                $this->params_page = $params;

            } break;

            case 'params_component':
            {
                // TODO: Clean this up.
                $params = new JParameter($this->params);
                $xml = $this->getComponentXml();

                // If hide is set, don't show the component configuration.
                $menu = $xml->document->attributes('menu');

                if(isset($menu) && $menu == 'hide') {
                    return null;
                }

                // Don't show hidden elements.
                if (isset($xml->document->params[0]->param))
                {
                    // Collect hidden elements.
                    $hidden = array();

                    for($i = 0, $n = count($xml->document->params[0]->param); $i < $n; $i++)
                    {
                        if($xml->document->params[0]->param[$i]->attributes('menu') == 'hide') {
                            $hidden[] = $xml->document->params[0]->param[$i];
                        }
                        elseif($xml->document->params[0]->param[$i]->attributes('type') == 'radio'
                            || $xml->document->params[0]->param[$i]->attributes('type') == 'list')
                        {
                            $xml->document->params[0]->param[$i]->addAttribute('default', '');
                            $xml->document->params[0]->param[$i]->addAttribute('type', 'list');
                            $child = $xml->document->params[0]->param[$i]->addChild('option', array('value' => ''));
                            $child->setData('Use Global');
                        }
                    }

                    // Remove hidden elements.
                    for($i = 0, $n = count($hidden); $i < $n; $i++) {
                        $xml->document->params[0]->removeChild($hidden[$i]);
                    }
                }

                $params->setXML($xml->document->params[0]);

                $this->params_component = $params;

            } break;

            case 'params_url':
            {
                $state  = $this->getPageXml()->document->getElementByPath('state');
                $params = new JParameter(null);

                if($state instanceof JSimpleXMLElement)
                {
                    $params->setXML($state->getElementByPath('url'));

                    if($this->link_url) {
                        $params->loadArray($this->link->query);
                    }
                }

                $this->params_url = $params;

            } break;
        }
        
        return parent::setProperty($property);
    }
    
    public function save()
    {
        if($this->isModified('link_url'))
        {
            // Set link.
            parse_str($this->link_url, $query);

            if($this->urlparams) {
                $query += $this->urlparams;
            }

            $this->link_url = 'index.php?'.http_build_query($query);

            // Set component id.
            $component = $this->getService('com://admin/extensions.database.table.components')
                ->select(array('name' => $query['option']), KDatabase::FETCH_ROW);

            $this->extensions_component_id = $component->id;
        }
        
        return parent::save();
    }
    
    public function getComponentXml()
    {
        if(!isset($this->_component_xml))
        {
            $xml  = JFactory::getXMLParser('simple');
            $path = $this->getIdentifier()->getApplication('admin').'/components/'.$this->_type['option'].'/config.xml';

            if(file_exists($path)) {
                $xml->loadFile($path);
            }

            $this->_component_xml = $xml;
        }

        return $this->_component_xml;
    }

    public function getPageXml()
    {
        if(!isset($this->_page_xml))
        {
            $xml  = JFactory::getXMLParser('simple');
            $path = $this->getIdentifier()->getApplication('site').'/components/'.$this->_type['option'].'/views/'.$this->_type['view'].'/tmpl/'.$this->_type['layout'].'.xml';

            if(file_exists($path)) {
                $xml->loadFile($path);
            }

            $this->_page_xml = $xml;
        }

        return $this->_page_xml;
    }
}