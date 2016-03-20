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
 * Sessions Model
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Users
 */
class ModelSessions extends Library\ModelDatabase
{
    /**
     * Constructor.
     *
     * @param   ObjectConfig $config An optional Library\ObjectConfig object with configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('path'  , 'path')
            ->insert('doamin', 'url')
            ->insert('email' , 'email');

        //@TODO : Add special session id filter
        $this->getState()
            ->insert('id', 'string', null, true);
    }

    /**
     * Builds SELECT columns list for the query.
     *
     * @param   Library\DatabaseQuerySelect  A query object.
     * @return  void
     */
    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array(
            'name'     => 'users.name'
        ));
    }

    /**
     * Builds LEFT JOINS clauses for the query.
     *
     * @param   Library\DatabaseQuerySelect  A query object.
     * @return  void
     */
    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        $state = $this->getState();

        $query->join(array('users' => 'users'), 'tbl.email = users.email');
    }

    /**
     * Builds a WHERE clause for the query.
     *
     * @param   Library\DatabaseQuerySelect  A query object.
     * @return  void
     */
    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if ($state->path)
        {
            $query->where('path IN :path')
                  ->bind(array('path' => (array) $state->path));
        }

        if ($state->domain)
        {
            $query->where('path IN :domain')
                ->bind(array('domain' => (array) $state->domain));
        }

        if ($state->email)
        {
            $query->where('tbl.email IN :email')
                  ->bind(array('email' => (array) $state->email));
        }
    }
}