<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract User Provider
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\User
 */
class UserProviderAbstract extends Object implements UserProviderInterface
{
    /**
     * The list of users
     *
     * @var array
     */
    protected $_users = array();

    /**
     * Constructor
     *
     * The user array is a hash where the keys are user identifier and the values are an array of attributes:
     * 'password', 'enabled', and 'roles' etc. The user identifiers should be unique.
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return UserProviderAbstract
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Create the users
        foreach($config->users as $identifier => $data) {
            $this->setUser($this->create($data));
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation
     *
     * @param   ObjectConfig $object An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'users' => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Load the user for the given username or identifier, fetching it from data store if it doesn't exist yet.
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @param bool  $refresh     If TRUE and the user has already been loaded it will be re-loaded.
     * @return UserInterface Returns a UserInterface object.
     */
    public function getUser($identifier, $refresh = false)
    {
        $result = null;

        //Fetch a user from the backend
        if($refresh || !$this->isLoaded($identifier))
        {
            $this->fetch($identifier, $refresh);

            if($this->isLoaded($identifier)) {
                $result = $this->_users[$identifier];
            }
        }

        return  $result;
    }

    /**
     * Store user object in the provider
     *
     * @param UserInterface $user
     * @return UserProviderAbstract
     */
    public function setUser(UserInterface $user)
    {
        $this->_users[$user->getId()] = $user;
        return $this;
    }

    /**
     * Fetch the user for the given user identifier from the data store
     *
     * @param string|array $identifier A unique user identifier, (i.e a username or email address)
     *                                 or an array of identifiers
     * @param bool  $refresh     If TRUE and the user has already been fetched it will be re-fetched.
     * @return boolean
     */
    public function fetch($identifier, $refresh = false)
    {
        $identifiers = (array) $identifier;

        foreach($identifiers as $identifier)
        {
            $data = array(
                'id'         => $identifier,
                'authentic'  => false
            );

            $this->setUser($this->create($data));
        }

        return true;
    }

    /**
     * Create a user object
     *
     * @param array $data An associative array of user data
     * @return UserInterface     Returns a UserInterface object
     */
    public function create($data)
    {
        $user = $this->getObject('user.default', array('data' => $data));
        return $user;
    }

    /**
     * Check if a user has already been loaded for a given user identifier
     *
     * @param $identifier
     * @return boolean TRUE if a user has already been loaded. FALSE otherwise
     */
    public function isLoaded($identifier)
    {
        return isset($this->_users[$identifier]);
    }
}