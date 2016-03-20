<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-users for the canonical source repository
 */

namespace Kodekit\Component\Users;

use Kodekit\Library;

/**
 * User Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Users
 */
class ModelEntityUser extends Library\ModelEntityRow implements Library\UserInterface
{
    /**
     * The User Groups
     *
     *  @var Library\ModelEntityInterface
     */
    protected $_groups;

    /**
     * Returns the id of the user
     *
     * @return int The id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the email of the user
     *
     * @return string The email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the name of the user
     *
     * @return string The name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the users language
     *
     * Should return a properly formatted IETF language tag, eg xx-XX
     * @link https://en.wikipedia.org/wiki/IETF_language_tag
     * @link https://tools.ietf.org/html/rfc5646
     *
     * @return string The language tag
     */
    public function getLanguage()
    {
        return $this->get('language');
    }

    /**
     * Returns the users timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->get('timezone');
    }

    /**
     * Returns the roles of the user
     *
     * @return array The role names
     */
    public function getRoles()
    {
        return array($this->role);
    }

    /**
     * Checks if the user has a role.
     *
     * @param  mixed|array $role A role name or an array containing role names.
     * @return bool True if the user has at least one of the provided roles, false otherwise.
     */
    public function hasRole($role)
    {
        $roles = (array) $role;
        return (bool) array_intersect($this->getRoles(), $roles);
    }

    /**
     * Returns the groups the user is part of
     *
     * @return array An array of group identifiers
     */
    public function getGroups()
    {
        $groups = array();

        if($this->isGroupable())
        {
            $entity = $this->getTable()->getBehavior('groupable')->getGroups();

            $groups = array();
            foreach ($entity as $group) {
                $groups[] = $group->name;
            }
        }

        return $groups;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text password will be salted, encoded, and
     * then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        $password = null;

        if($this->isAuthenticatable()) {
            $password = (string) $this->getTable()->getBehavior('authenticatable')->getPassword();
        }

        return $password;
    }

    /**
     * Verify the password
     *
     * @param string $password The plain-text password to verify
     * @return bool Returns TRUE if the plain-text password and users hashed password, or FALSE otherwise.
     */
    public function verifyPassword($password)
    {
        $result = false;

        if($this->isAuthenticatable()) {
            $result = $this->getTable()->getBehavior('authenticatable')->verifyPassword($password);
        }

        return $result;
    }

    /**
     * Returns the user parameters
     *
     * @return array The parameters
     */
    public function getParameters()
    {
        $result = array();

        if($this->isParameterizable()) {
            $result = $this->getTable()->getBehavior('parameterizable')->getParameters();
        }

        return $result;
    }

    /**
     * The user has been successfully authenticated
     *
     * @param  boolean $strict If true, checks if the user has been authenticated for this request explicitly
     * @return boolean True if the user is not logged in, false otherwise
     */
    public function isAuthentic($strict = false)
    {
        return $strict ?: $this->authentic;
    }

    /**
     * Checks whether the user account is enabled.
     *
     * @return Boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Checks whether the user credentials have expired.
     *
     * @return Boolean
     */
    public function isExpired()
    {
        return (bool) $this->activation;
    }

    /**
     * Sets the user as authenticated for the request
     *
     * @return $this
     */
    public function setAuthentic()
    {
        $this->authentic = true;
        return $this;
    }

    /**
     * Get an user parameter
     *
     * @param string $name The parameter name
     * @param   mixed   $value      Default value when the parameter doesn't exist
     * @return  mixed   The value
     */
    public function get($name, $default = null)
    {
        $result = $this->getParameters()->get($name, $default);
        return $result;
    }

    /**
     * Set an user parameter
     *
     * @param string $name The parameter name
     * @param  mixed $value The parameter value
     * @return ModelEntityUser
     */
    public function set($name, $value)
    {
        $this->getParameters()->set($name, $value);
        return $this;
    }

    /**
     * Check if a user parameter exists
     *
     * @param string $name The parameter name
     * @return  boolean
     */
    public function has($name)
    {
        $result = $this->getParameters()->has($name);
        return $result;
    }

    /**
     * Removes an user parameter
     *
     * @param string $name The parameter name
     * @return ModelEntityUser
     */
    public function remove($name)
    {
        $this->getParameters()->remove($name);
        return $this;
    }

    public function save()
    {
        $translator = $this->getObject('translator');

        // Validate name
        if ($this->isModified('name') && trim($this->name) == '')
        {
            $this->setStatus(self::STATUS_FAILED);
            $this->setStatusMessage($translator('Please enter a name'));
            return false;
        }

        if ($this->isModified('email'))
        {
            // Validate E-mail
            if (!$this->getObject('lib:filter.email')->validate($this->email))
            {
                $this->setStatus(self::STATUS_FAILED);
                $this->setStatusMessage($translator('Please enter a valid E-mail address'));
                return false;
            }

            // Check if E-mail address is not already being used
            $query = $this->getObject('lib:database.query.select')
                ->where('tbl.email = :email')
                ->bind(array('email' => $this->email));

            if ($this->getObject('com:users.database.table.users')->count($query))
            {
                $this->setStatus(self::STATUS_FAILED);
                $this->setStatusMessage($translator('The provided E-mail address is already registered'));
                return false;
            }
        }

        if (!$this->isNew())
        {
            // Load the current user row for checks.
            $current = $this->getObject('com:users.database.table.users')
                ->select($this->id, Library\Database::FETCH_ROW);

            // There must be at least one enabled super administrator
            if (($this->isModified('role_id') || ($this->isModified('enabled') && !$this->enabled)) && $current->role_id == 25)
            {
                $query = $this->getObject('lib:database.query.select')->where('tbl.enabled = :enabled')
                    ->where('tbl.users_role_id = :role_id')->bind(array('enabled' => 1, 'role_id' => 25));

                if ($this->getObject('com:users.database.table.users')->count($query) <= 1)
                {
                    $this->setStatus(self::STATUS_FAILED);
                    $this->setStatusMessage('There must be at least one enabled super administrator');
                    return false;
                }
            }
        }

        return parent::save();
    }

    public function clear()
    {
        $result = parent::clear();

        // Clear cache
        $this->_groups = null;

        return $result;
    }

    /**
     * Check if the user is equal
     *
     * @param  Library\UserInterface $user
     * @return Boolean
     */
    public function equals(Library\ObjectInterface $user)
    {
        if($user instanceof Library\UserInterface)
        {
            if($user->getEmail() == $this->getEmail())
            {
                if($user->getPassword() == $this->getPassword()) {
                    return true;
                }
            }
        }

        return false;
    }

	/**
     * Return an associative array containing the user data.
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();

        $data['roles']      = $this->getRoles();
        $data['groups']     = $this->getGroups();
        $data['parameters'] = $this->getParameters()->toArray();
        $data['expired']    = $this->isExpired();
        $data['authentic']  = $this->isAuthentic();

        unset($data['activation']);

        return $data;
    }
}
