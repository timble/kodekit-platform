<?php
class ComPagesDatabaseBehaviorTypeComponent extends ComPagesDatabaseBehaviorTypeAbstract
{
    protected $_type_title;

    public static function getInstance(KConfigInterface $config, KServiceManagerInterface $manager)
    {
        $instance = parent::getInstance($config, $manager);

        if(!$manager->has($config->service_identifier)) {
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

    public function getTypeTitle()
    {
        if(!isset($this->_type_title)) {
            $this->_type_title = JText::_('Component');
        }

        return $this->_type_title;
    }

    public function getTypeDescription()
    {
        $query       = $this->getLink()->query;
        $description = $this->component_name ? ucfirst(substr($this->component_name, 4)) : ucfirst(substr($query['option'], 4));

        if(isset($query['view'])) {
            $description .= ' &raquo; '.JText::_(ucfirst($query['view']));
        }

        if(isset($query['layout'])) {
            $description .= ' / '.JText::_(ucfirst($query['layout']));
        }

        return $description;
    }

    public function getLink()
    {
        $link = $this->getService('lib://nooku/http.url', array('url' => '?'.$this->link_url));
        $link->query['Itemid'] = $this->id;

        return $link;
    }

    public function getParams($group)
    {
        return $this->{'_get'.ucfirst($group).'Params'}();
    }

    protected function _getPageParams()
    {
        $file = __DIR__.'/component.xml';

        $xml = JFactory::getXMLParser('simple');
        $xml->loadFile($file);

        $params = new JParameter($this->params);
        $params->setXML($xml->document->getElementByPath('state/params'));

        return $params;
    }

    protected function _getComponentParams()
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

        return $params;
    }

    protected function _getUrlParams()
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

        return $params;
    }

    protected function _getLayoutParams()
    {
        $state  = $this->_getPageXml()->document->getElementByPath('state');
        $params = new JParameter(null);

        if($state instanceof JSimpleXMLElement)
        {
            $params->setXML($state->getElementByPath('params'));

            if($this->link_url) {
                $params->loadArray($this->getLink()->query);
            }
        }

        return $params;
    }

    protected function _getComponentXml()
    {
        $xml  = JFactory::getXMLParser('simple');
        $type = $this->getType();
        $path = $this->getIdentifier()->getNamespace('admin').'/component/'.substr($type['option'], 4).'/config.xml';

        if(file_exists($path)) {
            $xml->loadFile($path);
        }

        return $xml;
    }

    protected function _getPageXml()
    {
        $xml  = JFactory::getXMLParser('simple');
        $type = $this->getType();
        $path = $this->getIdentifier()->getNamespace('site').'/component/'.substr($type['option'], 4).'/views/'.$type['view'].'/templates/'.$type['layout'].'.xml';

        if(file_exists($path)) {
            $xml->loadFile($path);
        }

        return $xml;
    }

    protected function _setLinkBeforeSave(KCommandContext $context)
    {
        if($this->isModified('link_url'))
        {
            // Set link.
            parse_str($this->link_url, $query);

            if($this->urlparams) {
                $query += $this->urlparams;
            }

            $this->link_url = http_build_query($query);

            // TODO: Get component from application.component.
            // Set component id.
            $component = $this->getService('com://admin/extensions.database.table.components')
                ->select(array('name' => $query['option']), KDatabase::FETCH_ROW);

            $this->extensions_component_id = $component->id;
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