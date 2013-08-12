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
 * Items Model
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Cache
 */
class CacheModelItems extends Library\ModelAbstract
{	
    public function __construct(Library\ObjectConfig $config)
	{
	    parent::__construct($config);

        $this->getState()
		    ->insert('name'  , 'cmd')
		    ->insert('hash'  , 'cmd')
		    ->insert('group' , 'url')
		    ->insert('site'  , 'cmd')
		 	->insert('limit' , 'int')
            ->insert('offset', 'int')
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
                $data = JFactory::getCache()->keys();

                //Apply state information
                if($state->hash) {
                    $data = array_intersect_key($data, array_flip((array)$state->hash));
                }

                foreach($data as $key => $value)
                {
                    if($state->group)
                    {
                        if($value->group != $state->group) {
                            unset($data[$key]);
                        }
                    }

                    if($state->site)
                    {
                        if($value->site != $state->site) {
                            unset($data[$key]);
                        }
                    }

                    if($state->search)
                    {
                        if($value->name != $state->search) {
                            unset($data[$key]);
                        }
                    }
                }

                //Set the total
                $this->_total = count($data);

                //Apply limit and offset
                if($state->limit) {
                    $data = array_slice($data, $state->offset, $state->limit);
                }

                $context->data = $this->getObject('com:cache.database.rowset.items', array('data' => $data));
                $this->getCommandChain()->run('after.fetch', $context);
            }

            $this->_data = Library\ObjectConfig::unbox($context->data);
        }

        return $this->_data;
    }
    
    public function getTotal()
    {
        if(!isset($this->_total)) {
            $this->fetch();
        }
        
        return $this->_total;
    }
}