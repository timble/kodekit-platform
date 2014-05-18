<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Sessions Model
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Users
 */
class ModelSessions extends Library\ModelTable
{
    /**
     * Constructor.
     *
     * @param   ObjectConfig  An optional Library\ObjectConfig object with configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('application', 'word')
            ->insert('email'      , 'email');

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
        
        if ($state->application)
        {
            $query->where('application IN :application')
                  ->bind(array('application' => (array) $state->application));
        }

        if ($state->email)
        {
            $query->where('tbl.email IN :email')
                  ->bind(array('email' => (array) $state->email));
        }
    }
}