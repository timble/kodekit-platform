<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Captcha configuration class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersConfigCaptcha extends KConfig implements KServiceInstantiatable, KObjectServiceable
{
    /**
     * The service identifier
     *
     * @var KServiceIdentifier
     */
    private $__service_identifier;

    /**
     * The service container
     *
     * @var KService
     */
    private $__service_container;

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config) {
        //Set the service container
        if (isset($config->service_container)) {
            $this->__service_container = $config->service_container;
        }

        //Set the service identifier
        if (isset($config->service_identifier)) {
            $this->__service_identifier = $config->service_identifier;
        }

        parent::__construct($config);
    }

    final public function getService($identifier, array $config = array()) {
        if (!isset($this->__service_container)) {
            throw new KObjectException("Failed to call " . get_class($this) . "::getService(). No service_container object defined.");
        }

        return $this->__service_container->get($identifier, $config);
    }

    /**
     * Gets the service identifier.
     *
     * @throws    KObjectException if the service container has not been defined.
     * @return    KServiceIdentifier
     * @see     KObjectServiceable
     */
    final public function getIdentifier($identifier = null) {
        if (isset($identifier)) {
            if (!isset($this->__service_container)) {
                throw new KObjectException("Failed to call " . get_class($this) . "::getIdentifier(). No service_container object defined.");
            }

            $result = $this->__service_container->getIdentifier($identifier);
        }
        else  $result = $this->__service_identifier;

        return $result;
    }

    static public function getInstance(KConfigInterface $config, KServiceInterface $container) {

        $instance = new self($config);

        // Force singleton behavior.
        $container->set($config->service_identifier, $instance);

        return $instance;
    }
}