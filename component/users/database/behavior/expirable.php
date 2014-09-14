<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Expirable Database Behavior
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Nooku\Component\Users
 */
class DatabaseBehaviorExpirable extends Library\DatabaseBehaviorAbstract
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

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_expiration = $config->expiration;
        $this->_expirable  = $config->expirable;
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'expirable'  => 1,
            'expiration' => 6,
        ));

        parent::_initialize($config);
    }

    protected function _beforeInsert(Library\DatabaseContext $context)
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
        $this->expiration = gmdate('Y-m-d');

        if ($autosave) {
            $result = $this->save();
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

        if (!$this->expirable() || (!empty($this->expiration) && (strtotime(gmdate('Y-m-d')) < strtotime($this->expiration)))) {
            $result = false;
        }

        return $result;
    }

    public function expirable()
    {
        return (bool) $this->_expirable;
    }
}
