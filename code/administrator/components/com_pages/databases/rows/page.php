<?php
/**
 * @version     $Id: page.php 3035 2011-10-09 16:57:12Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Page Database Row Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesDatabaseRowPage extends KDatabaseRowTable/* implements KServiceInstantiatable*/
{
    protected $_strategy;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        if($config->strategy)
        {
            $identifier = clone $this->getIdentifier();
            $identifier->path = array('database', 'row', $identifier->name);
            $identifier->name = $config->strategy;
            
            $this->setStrategy($this->getService($identifier, KConfig::unbox($config)));
            $this->getStrategy()->setObject($this);
        }
    }
    
    public function getStrategy()
    {
        return $this->_strategy;
    }
    
    public function setStrategy(ComPagesDatabaseRowPageInterface $strategy)
    {
        $this->_strategy = $strategy;
        
        return $this;
    }
    
    public function save()
    {
        $this->getStrategy()->save();
        
        return parent::save();
    }

    /**
     * Returns the siblings of the row
     *
     * @return KDatabaseRowAbstract
     */
    public function getSiblings()
    {
        if($this->id)
        {
            $table = $this->getTable();
            $query = $this->getService('koowa:database.query.select')
                ->where('tbl.'.$table->getIdentityColumn().' <> :id')
                ->where('tbl.pages_menu_id = :pages_menu_id')
                ->having('level = :level')
                ->bind(array(
                    'id' => $this->id,
                    'pages_menu_id' => $this->pages_menu_id,
                    'level' => $this->level));

            $parent_ids = $this->getParentIds();
            if($parent_ids)
            {
                $query->join(array('closures' => $table->getRelationTable()), 'closures.descendant_id = tbl.'.$table->getIdentityColumn(), 'INNER')
                    ->where('closures.ancestor_id = :parent_id')
                    ->bind(array('parent_id' => $this->parent_id));
            }

            $result = $this->getTable()->select($query, KDatabase::FETCH_ROWSET);
        }
        else $result = null;

        return $result;
    }

    public function __get($key)
    {
        $strategy = $this->getStrategy();
        if(!isset($this->$key) && $strategy->hasProperty($key)) {
            $strategy->setProperty($key);
        }

        return parent::__get($key);
    }
}
