<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * User Model Entity
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Users
 */
class ModelEntityUser extends Library\ModelEntityRow
{
    /**
     * The User Role
     *
     * @var Library\ModelEntityInterface
     */
    protected $_role;

    /**
     * The User Groups
     *
     *  @var Library\ModelEntityInterface
     */
    protected $_groups;

    /**
     * User role getter.
     *
     * @return Library\ModelEntityInterface The user's role row object.
     */
    public function getRole()
    {
        if (!$this->_role)
        {
            $this->_role = $this->getObject('com:users.model.roles')
                ->id($this->role_id)
                ->fetch();
        }

        return $this->_role;
    }

    public function getGroups()
    {
        if(is_null($this->_groups))
        {
            $this->_groups =  $this->getObject('com:users.database.table.groups_users')
                ->select(array('users_user_id' => $this->role_id), Library\Database::FETCH_FIELD_LIST);

        }

        return $this->_groups;
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
                ->where('email = :email')
                ->bind(array('email' => $this->email));

            if ($this->getObject('com:users.database.table.users')->count($query))
            {
                $this->setStatus(self::STATUS_FAILED);
                $this->setStatusMessage($translator('The provided E-mail address is already registered'));
                return false;
            }
        }

        // Check if the attached role exists
        if ($this->isModified('role_id') && $this->getRole()->isNew())
        {
            $this->setStatus(self::STATUS_FAILED);
            $this->setStatusMessage('Invalid role');
            return false;
        }

        // Clear role cache
        if ($this->isModified('role_id')) {
            $this->_role = null;
        }

        if (!$this->isNew())
        {
            // Load the current user row for checks.
            $current = $this->getObject('com:users.database.table.users')
                ->select($this->id, Library\Database::FETCH_ROW);

            // There must be at least one enabled super administrator
            if (($this->isModified('role_id') || ($this->isModified('enabled') && !$this->enabled)) && $current->role_id == 25)
            {
                $query = $this->getObject('lib:database.query.select')->where('enabled = :enabled')
                    ->where('users_role_id = :role_id')->bind(array('enabled' => 1, 'role_id' => 25));

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

    public function reset()
    {
        $result = parent::reset();

        // Clear cache
        $this->_role   = null;
        $this->_groups = null;

        return $result;
    }

	/**
     * Return an associative array containing the user data.
     *
     * @return array
     */
    public function toArray()
    {
        $data = parent::toArray();
        unset($data['activation']);

        return $data;
    }

    /**
     * Sends a notification E-mail to the user.
     *
     * @param array $config Optional configuration array.
     *
     * @return bool
     */
    public function notify($config = array()) {

        $config = new Library\ObjectConfig($config);

        $application = $this->getObject('application');
        $user        = $this->getObject('user');

        $config->append(array(
            'subject' => '',
            'message' => '',
            'from_email' => $application->getConfig()->mailfrom,
            'from_name'  => $application->getConfig()->fromname))
            ->append(array('from_email' => $user->getEmail(), 'from_name' => $user->getName()));

        return \JUtility::sendMail($config->from_email, $config->from_name, $this->email, $config->subject, $config->message);
    }
}
