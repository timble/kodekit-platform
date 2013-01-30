<?php
class ComPagesDatabaseRowPage extends KDatabaseRowTable
{
    protected $_type;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        if(isset($config->state) && $config->state->type) {
            $this->setType($config->state->type);
        }
    }

    public function setType(array $type)
    {
        $this->_type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->_type;
    }
}