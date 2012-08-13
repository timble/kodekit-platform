<?php
/**
 * @version     $Id: component.php 3209 2011-11-09 20:06:21Z kotuha $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Database Row Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesDatabaseRowComponent extends ComPagesDatabaseRowPage
{
    protected $_component_xml;
    
    public function save()
    {
        $type = $this->getType();
        
        // Set component id.
        $query = $this->getService('koowa:database.query.select')
            ->where('link <> :link')
            ->where('option = :option')
            ->bind(array('link' => '', 'option' => $type->option));
        
        $component = $this->getService('com://admin/extensions.database.table.components')
            ->select($query, KDatabase::FETCH_ROW);

        $this->component_id = $component->id;
        
        // Set link.
        if($type->option && $type->view)
        {
            $query = array(
                'option' => $type->option,
                'view' => $type->view
            );

            if($type->layout && $type->layout != 'default') {
                $query['layout'] = $type->layout;
            }

            if($this->urlparams) {
                $query += $this->urlparams;
            }

            $this->link_url = 'index.php?'.http_build_query($query);
        }
        
        return parent::save();
    }

    protected function _getComponentXml()
    {
        if(!isset($this->_component_xml))
        {
            $xml  = JFactory::getXMLParser('simple');
            $path = JPATH_APPLICATION.'/components/'.$this->getType()->option.'/config.xml';

            if(file_exists($path)) {
                $xml->loadFile($path);
            }

            $this->_component_xml = $xml;
        }

        return $this->_component_xml;
    }

    public function __get($name)
    {
        switch($name)
        {
            case 'type_description':
            {
                if(!isset($this->_data['type_description']))
                {
                    $query       = $this->link->query;
                    $description = $this->component_name ? $this->component_name : ucfirst(substr($query['option'], 4));

                    if(isset($query['view'])) {
                        $description .= ' &raquo; '.JText::_(ucfirst($query['view']));
                    }

                    if(isset($query['layout'])) {
                        $description .= ' / '.JText::_(ucfirst($query['layout']));
                    }

                    $this->_data['type_description'] = $description;
                }

            } break;

            case 'type_title':
            {
                if(!isset($this->_data['type_title'])) {
                    $this->_data['type_title'] = JText::_('Component');
                }

            } break;

            case 'link':
            {
                if(!isset($this->_data['link']) || !$this->_data['link'] instanceof KHttpUri) {
                    $this->_data['link'] = $this->getService('koowa:http.url', array('url' => $this->_data['link_url']));
                }

            } break;

            case 'params_page':
            {
                if(!isset($this->_data['params_page']))
                {
                    $file = JPATH_APPLICATION.'/components/com_pages/databases/rows/component.xml';

                    $xml = JFactory::getXMLParser('simple');
                    $xml->loadFile($file);

                    $params = new JParameter($this->_data['params']);
                    $params->setXML($xml->document->getElementByPath('state/params'));

                    $this->_data['params_page'] = $params;
                }

            } break;

            case 'params_component':
            {
                // TODO: Clean this up.
                if(!isset($this->_data['params_component']))
                {
                    $params = new JParameter($this->params);
                    $xml = $this->_getComponentXml();

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

                    $this->_data['params_component'] = $params;
                }

            } break;

            case 'params_url':
            {
                if(!isset($this->_data['params_url']))
                {
                    $state  = $this->_getPageXml()->document->getElementByPath('state');
                    $params = new JParameter(null);

                    if($state instanceof JSimpleXMLElement)
                    {
                        $params->setXML($state->getElementByPath('url'));

                        if($this->link_url) {
                            $params->loadArray($this->link->query);
                        }
                    }

                    $this->_data['params_url'] = $params;
                }

            } break;
        }

        return parent::__get($name);
   }
}
