<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Behavior Mixin
 *
 * Behaviors are attached in FIFO order during construction. Behaviors are added by name and, at runtime behaviors
 * cannot be overridden by attaching a behaviors with the same.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Behavior
 */
class BehaviorMixin extends CommandMixin implements BehaviorMixinInterface
{
    /**
     * List of behaviors
     *
     * The key holds the behavior name and the value the behavior object
     *
     * @var    array
     */
    private $__behaviors = array();

    /**
     * Constructor
     *
     * @param ObjectConfig $object An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Add the behaviors in FIFO order
        $behaviors = (array)ObjectConfig::unbox($config->behaviors);

        foreach ($behaviors as $key => $value) {
            if (is_numeric($key)) {
                $this->addBehavior($value);
            } else {
                $this->addBehavior($key, $value);
            }
        }
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options.
     *
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        parent::_initialize($config);

        $config->append(array(
            'behaviors' => array(),
        ));
    }

    /**
     * Add a behavior
     *
     * @param   mixed $behavior   An object that implements BehaviorInterface, an ObjectIdentifier
     *                            or valid identifier string
     * @param   array $config     An optional associative array of configuration settings
     *
     * @return  Object The mixer object
     */
    public function addBehavior($behavior, $config = array())
    {
        //Create the complete identifier if a partial identifier was passed
        if (is_string($behavior) && strpos($behavior, '.') === false)
        {
            $identifier         = $this->getIdentifier()->toArray();
            $identifier['path'] = array($identifier['path'][0], 'behavior');
            $identifier['name'] = $behavior;

            $identifier = $this->getIdentifier($identifier);
        }
        else
        {
            if ($behavior instanceof BehaviorInterface) {
                $identifier = $behavior->getIdentifier();
            } else {
                $identifier = $this->getIdentifier($behavior);
            }
        }

        //Attach the behavior if it doesn't exist yet
        if (!$this->hasCommandHandler($identifier))
        {
            //Create the behavior object
            if (!($behavior instanceof BehaviorInterface))
            {
                $config['mixer'] = $this->getMixer();
                $behavior        = $this->getObject($identifier, $config);
            }

            if (!($behavior instanceof BehaviorInterface)) {
                throw new \UnexpectedValueException("Behavior $identifier does not implement BehaviorInterface");
            }

            if(!$this->hasBehavior($behavior->getName()))
            {
                //Force set the mixer
                $behavior->setMixer($this->getMixer());

                //Add the behavior
                $this->addCommandHandler($behavior);

                //Mixin the behavior
                $this->mixin($behavior);

                //Store the behavior to allow for named lookups
                $this->__behaviors[$behavior->getName()] = $behavior;
            }
        }

        return $this->getMixer();
    }

    /**
     * Check if a behavior exists
     *
     * @param   string $name The name of the behavior
     *
     * @return  boolean TRUE if the behavior exists, FALSE otherwise
     */
    public function hasBehavior($name)
    {
        return isset($this->__behaviors[$name]);
    }

    /**
     * Get a behavior by name
     *
     * @param  string $name The behavior name
     *
     * @return BehaviorInterface
     */
    public function getBehavior($name)
    {
        $result = null;

        if (isset($this->__behaviors[$name])) {
            $result = $this->__behaviors[$name];
        }

        return $result;
    }

    /**
     * Gets the behaviors of the table
     *
     * @return array An associative array of table behaviors, keys are the behavior names
     */
    public function getBehaviors()
    {
        return $this->__behaviors;
    }
}