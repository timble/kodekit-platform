<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Database Parameterizable Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
class DatabaseBehaviorParameterizable extends DatabaseBehaviorAbstract
{
    /**
     * The parameters
     *
     * @var ObjectConfigInterface
     */
    protected $_parameters;

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'row_mixin' => true,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the parameters
     *
     * Requires an 'parameters' table column
     *
     * @return ObjectConfigInterface
     */
    public function getParameters()
    {
        if($this->hasProperty('parameters') && !isset($this->_parameters))
        {
            $type = $this->getTable()->getColumn('parameters')->filter->getIdentifier()->name;
            $data = trim($this->getProperty('parameters'));

            //Create the parameters object
            if(empty($data)) {
                $config = $this->getObject('object.config.factory')->createFormat($type);
            } else {
                $config = $this->getObject('object.config.factory')->fromString($type, $data);
            }

            $this->_parameters = $config;
        }

        return $this->_parameters;
    }

    /**
     * Merge the parameters
     *
     * @param $value
     */
    public function setPropertyParameters($value)
    {
        if(!empty($value))
        {
            if(!is_string($value)) {
                $value = $this->getParameters()->add($value)->toString();
            }
        }

        return $value;
    }

    /**
     * Check if the behavior is supported
     *
     * Behavior requires a 'parameters' table column
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $mixer = $this->getMixer();
        $table = $mixer instanceof DatabaseRowInterface ?  $mixer->getTable() : $mixer;

        if($table->hasColumn('parameters'))  {
            return true;
        }

        return false;
    }

    /**
     * Insert the parameters
     *
     * @param DatabaseContext	$context A database context object
     * @return void
     */
    protected function _beforeInsert(DatabaseContext $context)
    {
        if($context->data->getParameters() instanceof ObjectConfigInterface) {
            $context->data->setProperty('parameters', $context->data->getParameters()->toString());
        }
    }

    /**
     * Update the parameters
     *
     * @param DatabaseContext	$context A database context object
     * @return void
     */
    protected function _beforeUpdate(DatabaseContext $context)
    {
        if($context->data->getParameters() instanceof ObjectConfigInterface) {
            $context->data->setProperty('parameters', $context->data->getParameters()->toString());
        }
    }
}