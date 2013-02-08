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
 * User Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersDatabaseRowUser extends KDatabaseRowTable
{
    /**
     * @var ComUsersDatabaseRowRole User role object.
     */
    protected $_role;

    protected $_groups;

    public function __get($column)
    {
        if ($column == 'params' && !$this->_data['params'] instanceof JParameter)
        {
            $path = JPATH_APPLICATION . '/component/users/databases/rows';
            $name = str_replace(' ', '_', strtolower((string) $this->getRole()->name));
            $file = $path . '/' . $name . '.xml';

            if (!file_exists($file)) {
                $file = $path . '/user.xml';
            }

            $params = new JParameter($this->_data['params']);
            $params->loadSetupFile($file);

            $this->_data['params'] = $params;
        }

        return parent::__get($column);
    }

    /**
     * User role getter.
     *
     * @return ComUsersDatabaseRowRole The user's role row object.
     */
    public function getRole()
    {
        if (!$this->_role)
        {
            //@TODO : Temporarily using KService::get since User object is not yet properly set on session when
            // getting it with JFactory::getUser.
            $this->_role = KService::get('com://admin/users.model.roles')->id($this->role_id)->getRow();
            //$this->_role = $this->getService('com://admin/users.model.roles')->id($this->role_id)->getRow();
        }
        return $this->_role;
    }

    public function getGroups()
    {
        if(is_null($this->_groups))
        {
            if(!$this->guest)
            {
                $this->_groups = KService::get('com://admin/users.database.table.groups_users')
                    ->select(array('users_user_id' => $this->id), KDatabase::FETCH_FIELD_LIST);
            }
            else $this->_groups = array();
        }

        return $this->_groups;
    }

    public function save()
    {
        // Validate name
        if ($this->isModified('name') && trim($this->name) == '')
        {
            $this->setStatus(KDatabase::STATUS_FAILED);
            $this->setStatusMessage(JText::_('Please enter a name'));
            return false;
        }

        if ($this->isModified('email'))
        {
            // Validate E-mail
            if (!$this->getService('koowa:filter.email')->validate($this->email))
            {
                $this->setStatus(KDatabase::STATUS_FAILED);
                $this->setStatusMessage(JText::_('Please enter a valid E-mail address'));
                return false;
            }

            // Check if E-mail address is not already being used
            $query = $this->getService('koowa:database.query.select')
                ->where('email = :email')
                ->where('users_user_id <> :id')
                ->bind(array('email' => $this->email, 'id' => $this->id));

            if ($this->getService('com://admin/users.database.table.users')->count($query))
            {
                $this->setStatus(KDatabase::STATUS_FAILED);
                $this->setStatusMessage(JText::_('The provided E-mail address is already registered'));
                return false;
            }
        }

        // Check if the attached role exists
        if ($this->isModified('role_id') && $this->getRole()->isNew())
        {
            $this->setStatus(KDatabase::STATUS_FAILED);
            $this->setStatusMessage('Invalid role');
            return false;
        }

        // Set parameters.
        if ($this->isModified('params'))
        {
            $params = new JParameter('');
            $params->bind($this->_data['params']);
            $this->params = $params->toString();
            /*if(!$this->isNew() && $this->_data['params'] == $current->params->toString()) {
                unset($this->_modified['params']);
            }*/
        }

        if ($this->isModified('role_id'))
        {
            // Clear role cache
            $this->_role = null;
        }

        if (!$this->isNew())
        {
            // Load the current user row for checks.
            $current = $this->getService('com://admin/users.database.table.users')
                ->select($this->id, KDatabase::FETCH_ROW);

            // There must be at least one enabled super administrator
            if (($this->isModified('role_id') || ($this->isModified('enabled') && !$this->enabled)) && $current->role_id == 25)
            {
                $query = $this->getService('koowa:database.query.select')->where('enabled = :enabled')
                    ->where('users_role_id = :role_id')->bind(array('enabled' => 1, 'role_id' => 25));

                if ($this->getService('com://admin/users.database.table.users')->count($query) <= 1)
                {
                    $this->setStatus(KDatabase::STATUS_FAILED);
                    $this->setStatusMessage('There must be at least one enabled super administrator');
                    return false;
                }
            }
        }

        return parent::save();
    }

    public function load()
    {
        $result = parent::load();

        // Clear role cache
        if ($result) {
            $this->_role = null;
        }

        return $result;
    }

    public function reset()
    {
        $result = parent::reset();

        // Clear role cache
        $this->_role = null;
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
        $data['params'] = $this->params->toArray();

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

        $config = new KConfig($config);

        $application = $this->getService('application');
        $user        = $this->getService('user');

        $config->append(array(
            'subject' => '',
            'message' => '',
            'from_email' => $application->getCfg('mailfrom'),
            'from_name'  => $application->getCfg('fromname')))
            ->append(array('from_email' => $user->getEmail(), 'from_name' => $user->getName()));

        return JUtility::sendMail($config->from_email, $config->from_name, $this->email, $config->subject, $config->message);
    }
}