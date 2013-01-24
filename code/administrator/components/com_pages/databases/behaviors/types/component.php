<?php
class ComPagesDatabaseBehaviorTypeComponent extends KDatabaseBehaviorAbstract
{
    protected $_type_description;

    protected $_type_title;

    protected $_page_params;

    protected $_component_params;

    protected $_url_params;

    protected $_page_xml;

    public function getTypeDescription()
    {
        if(!isset($this->_description))
        {
            $query       = $this->getLink()->query;
            $description = $this->component_name ? ucfirst(substr($this->component_name, 4)) : ucfirst(substr($query['option'], 4));

            if(isset($query['view'])) {
                $description .= ' &raquo; '.JText::_(ucfirst($query['view']));
            }

            if(isset($query['layout'])) {
                $description .= ' / '.JText::_(ucfirst($query['layout']));
            }

            $this->_type_description = $description;
        }

        return $this->_type_description;
    }

    public function getTypeTitle()
    {
        if(!isset($this->_type_title)) {
            $this->_type_title = JText::_('Component');
        }

        return $this->_type_title;
    }

    public function getLink()
    {
        $link = $this->getService('koowa:http.url', array('url' => $this->link_url));

        return $link;
    }

    public function getPageParams()
    {
        if(!isset($this->_page_params))
        {
            $file = __DIR__.'/component.xml';

            $xml = JFactory::getXMLParser('simple');
            $xml->loadFile($file);

            $params = new JParameter($this->params);
            $params->setXML($xml->document->getElementByPath('state/params'));

            $this->_page_params = $params;
        }

        return $this->_page_params;
    }

    public function getComponentParams()
    {
        if(!isset($this->_component_params))
        {
            // TODO: Clean this up.
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
            $this->_component_params = $params;
        }

        return $this->_component_params;
    }

    public function getUrlParams()
    {
        if(!isset($this->_url_params))
        {
            $state  = $this->_getPageXml()->document->getElementByPath('state');
            $params = new JParameter(null);

            if($state instanceof JSimpleXMLElement)
            {
                $params->setXML($state->getElementByPath('url'));

                if($this->link_url) {
                    $params->loadArray($this->getLink()->query);
                }
            }

            $this->_url_params = $params;
        }

        return $this->_url_params;
    }

    protected function _getComponentXml()
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

    protected  function _getPageXml()
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

    protected function _setLinkBeforeSave(KCommandContext $context)
    {
        $data = $context->data;
        if($data->isModified('link_url'))
        {
            // Set link.
            parse_str($data->link_url, $query);

            if($data->urlparams) {
                $query += $data->urlparams;
            }

            $data->link_url = 'index.php?'.http_build_query($query);

            // TODO: Get component from application.component.
            // Set component id.
            $component = $this->getService('com://admin/extensions.database.table.components')
                ->select(array('name' => $query['option']), KDatabase::FETCH_ROW);

            $data->extensions_component_id = $component->id;
        }
    }

    protected function _beforeTableInsert(KCommandContext $context)
    {
        $this->_setLinkBeforeSave($context);
    }

    protected function _beforeTableUpdate(KCommandContext $context)
    {
        $this->_setLinkBeforeSave($context);
    }
}