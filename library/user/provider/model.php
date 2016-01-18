<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * User Provider
 *
 * The user provider will load users by their email address or user id through a model. Once a user object is
 * loaded it is cached in memory. The model entities need to implement the UserInterface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\User
 */
class UserProviderModel extends UserProviderAbstract
{
    /**
     * The list of user emails
     *
     * @var array
     */
    protected $_emails = array();

    /**
     * Model object or identifier
     *
     * @var    string|object
     */
    private $__model;

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

        //Set the model
        $this->__model = $config->model;
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
            'model' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the user modem
     *
     * @throws	\UnexpectedValueException	If the model doesn't implement the ModelInterface
     * @return	ModelInterface
     */
    public function getModel()
    {
        if(!$this->__model instanceof ModelInterface)
        {
            $this->__model = $this->getObject($this->__model);

            if(!$this->__model instanceof ModelInterface)
            {
                throw new \UnexpectedValueException(
                    'Model: '.get_class($this->__model).' does not implement ModelInterface'
                );
            }
        }

        return $this->__model;
    }

    /**
     * Set the user model
     *
     * @param  ModelInterface  $model The user model
     * @return UserProviderModel
     */
    public function setModel(ModelInterface $model)
    {
        $this->__model = $model;
        return $this;
    }

    /**
     * Set a user in the provider
     *
     * @param UserInterface $user
     * @return boolean
     */
    public function setUser(UserInterface $user)
    {
        parent::setUser($user);

        //Store the user by email
        if($email = $user->getEmail()) {
            $this->_emails[$email] = $user;
        }

        return $this;
    }

    /**
     * Find a user for the given identifier
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @return UserInterface|null Returns a UserInterface object or NULL if the user hasn't been loaded yet
     */
    public function findUser($identifier)
    {
        $user = null;
        if($this->isLoaded($identifier))
        {
            if (!is_numeric($identifier)) {
                $user = $this->_emails[$identifier];
            } else {
                $user = parent::findUser($identifier);
            }
        }

        return $user;
    }

    /**
     * Fetch the user for the given user identifier from the data store
     *
     * @param string $identifier A unique user identifier, (i.e a username or email address)
     * @param bool   $lazyload  Lazyload the $identifier(s) on the following call to getUser()
     * @return boolean
     */
    public function fetch($identifier, $lazyload = false)
    {
        $identifiers = array_merge((array) $identifier, $this->_fetch);

        //Only fetch identifiers that haven't been loaded yet.
        foreach($identifiers as $key => $value)
        {
            if($this->isLoaded($value)) {
                unset($identifiers[$key]);
            }
        }

        if(!empty($identifiers))
        {
            if (!$lazyload)
            {
                if (!is_numeric($identifiers[0])) {
                    $users = $this->getModel()->email($identifiers)->fetch();
                } else {
                    $users = $this->getModel()->id($identifiers)->fetch();
                }

                if(count($users))
                {
                    foreach($users as $user) {
                        $this->setUser($user);
                    }

                    return true;
                }
            }
            else $this->_fetch = $identifiers;
        }

        return false;
    }

    /**
     * Check if a user has already been loaded for a given user identifier
     *
     * @param $identifier
     * @return boolean TRUE if a user has already been loaded. FALSE otherwise
     */
    public function isLoaded($identifier)
    {
        if (!is_numeric($identifier)) {
            $result = isset($this->_emails[$identifier]);
        } else {
            $result = parent::isLoaded($identifier);
        }

        return $result;
    }
}