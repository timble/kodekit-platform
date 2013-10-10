<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Groups Model
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Cache
 */
class CacheModelGroups extends Library\ModelAbstract
{	
    public function __construct(Library\ObjectConfig $config)
	{
	    parent::__construct($config);
		
		$this->getState()
		    ->insert('name'     , 'cmd')
		    ->insert('site'     , 'cmd')
		 	->insert('limit'    , 'int')
            ->insert('offset'   , 'int')
            ->insert('sort'     , 'cmd')
            ->insert('direction', 'word', 'asc')
            ->insert('search'   , 'string');
	}
	
    public function fetch()
    {
        if(!isset($this->_data))
        {
            $context = $this->getCommandContext();
            $context->data  = null;
            $context->state = $this->getState();

            if ($this->getCommandChain()->run('before.fetch', $context) !== false)
            {
                $state = $context->state;
                $data = array();
                $keys = $this->getObject('com:cache.model.items')->site($state->site)->fetch();

                foreach($keys as $key)
                {
                    if(!isset($data[$key->group]))
                    {
                        $data[$key->group] = array(
                            'name'  => $key->group,
                            'site'  => $key->site,
                            'count' => 0,
                            'size'  => 0,
                        );
                    }

                    $data[$key->group]['size'] += $key->size;
                    $data[$key->group]['count']++;
                }

                //Apply state information
                if($this->getState()->name) {
                    $data = array_intersect_key($data, array_flip((array)$state->name));
                }

                foreach($data as $key => $value)
                {
                    if($state->search)
                    {
                        if($value['name'] != $state->search) {
                            unset($data[$key]);
                        }
                    }
                }

                //Set the total
                $this->_count = count($data);

                //Apply limit and offset
                if($state->limit) {
                    $data = array_slice($data, $state->offset, $state->limit);
                }

                $context->data = $this->getObject('com:cache.database.rowset.groups', array('data' => $data));
                $this->getCommandChain()->run('after.fetch', $context);
            }

            $this->_data = Library\ObjectConfig::unbox($context->data);
        }

        return $this->_data;
    }

    public function count()
    {
        if(!isset($this->_count)) {
            $this->fetch();
        }
        
        return $this->_count;
    }
}