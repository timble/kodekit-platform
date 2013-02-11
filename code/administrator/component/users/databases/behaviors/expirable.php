<?php
/**
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Expirable Database Behavior Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersDatabaseBehaviorExpirable extends KDatabaseBehaviorAbstract
{
    /**
     * The Expiration period
     *
     * @var string
     */
    protected $_expiration;

    /**
     * Determines if an expiration date should be set for the row.
     *
     * @var boolean
     */
    protected $_expirable;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_expiration = $config->expiration;
        $this->_expirable     = $config->expirable;
    }

    protected function _initialize(KConfig $config)
    {
        $params = $this->getService('application.components')->users->params;

        $config->append(array(
            'expirable'     => $params->get('password_expire', 0),
            'expiration' => 6,
            'auto_mixin' => true
        ));

        parent::_initialize($config);
    }

    protected function _beforeTableInsert(KCommandContext $context)
    {
        if ($this->_expirable) {
            $this->resetExpiration(false);
        }
    }

    /**
     * Resets the expiration date.
     *
     * @param bool $autosave If true the mixer will be automatically saved.
     * @return  bool|object True if mixer was successfully stored, false otherwise, the mixer if no autosave.
     */
    public function resetExpiration($autosave = true)
    {
        if ($this->_expirable) {
            $this->expiration = gmdate('Y-m-d', time() + $this->_expiration * 30 * 24 * 60 * 60);
        } else {
            $this->expiration = null;
        }

        if ($autosave) {
            $result = $this->save();
        } else {
            $result = $this->getMixer();
        }

        return $result;
    }

    /**
     * Sets the row as expired.
     *
     * @param bool $autosave If true the mixer will be automatically saved.
     * @return  bool|object True if mixer was successfully stored, false otherwise, the mixer if no autosave.
     */
    public function expire($autosave = true)
    {
        $this->expiration = date('Y-m-d');

        if ($autosave) {
            $this->save();
        } else {
            $result = $this->getMixer();
        }

        return $result;
    }

    /**
     * Tells is the current password is expired.
     *
     * @return bool|null true if expired, false if not yet expired, null otherwise.
     */
    public function expired()
    {
        $result = true;

        if (empty($this->expiration)) {
            $result = null;
        } elseif (strtotime(date('Y-m-d')) < strtotime($this->expiration)) {
            $result = false;
        }

        return $result;
    }
}
