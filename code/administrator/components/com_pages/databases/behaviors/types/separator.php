<?php
class ComPagesDatabaseBehaviorTypeSeparator extends ComPagesDatabaseBehaviorTypeAbstract
{
    protected $_type_title;

    protected $_type_description;

    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        $instance = parent::getInstance($config, $container);

        if(!$container->has($config->service_identifier)) {
            $container->set($config->service_identifier, $instance);
        }

        return $container->get($config->service_identifier);
    }

    public function getTypeTitle()
    {
        if(!isset($this->_type_title)) {
            $this->_type_title = JText::_('Separator');
        }

        return $this->_type_title;
    }

    public function getTypeDescription()
    {
        if(!isset($this->_type_description)) {
            $this->_type_description = JText::_('Separator');
        }

        return $this->_type_description;
    }
}