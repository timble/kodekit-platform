<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Languages;

use Nooku\Library;

class CommandTranslation extends Library\Command
{
    protected $_tables;

    public function __construct(Library\Config $config)
    {
        parent::__construct($config);

        $this->_tables = $this->getService('com:languages.model.tables')
            ->enabled(true)
            ->getRowset();
    }

    public static function getInstance(Library\Config $config, Library\ServiceManagerInterface $manager)
    {
        if(!$manager->has($config->service_identifier))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

    protected function _databaseBeforeSelect(Library\CommandContext $context)
    {
        if($query = $context->query)
        {
            $languages = $this->getService('application.languages');
            $active    = $languages->getActive();
            $primary   = $languages->getPrimary();

            // Modify table in the query if active language is not the primary.
            if($active->iso_code != $primary->iso_code && is_array($query->table))
            {
                $table = array_shift(array_values($query->table));
                if(is_string($table))
                {
                    $table = $this->_tables->find(array('name' => $table))->top();
                    if($table instanceof Library\DatabaseRowInterface && $table->enabled) {
                        $query->table[key($query->table)] = strtolower($active->iso_code).'_'.$table->name;
                    }
                }
            }
        }
    }

    protected function _databaseAfterInsert(Library\CommandContext $context)
    {
        if($context->affected)
        {
            // Insert item into language specific tables.
            $table = $this->_tables->find(array('name' => $context->query->table))->top();
            if($table instanceof Library\DatabaseRowInterface && $table->enabled)
            {
                $languages = $this->getService('application.languages');
                $primary   = $languages->getPrimary();

                foreach($languages as $language)
                {
                    if($language->iso_code != $primary->iso_code)
                    {
                        $query = clone $context->query;
                        $query->table(strtolower($language->iso_code).'_'.$query->table);

                        if(($key = array_search($table->unique_column, $query->columns)) !== false) {
                            $query->values[0][$key] = $context->getSubject()->getInsertId();
                        }

                        $context->getSubject()->execute($query);
                    }
                }
            }
        }
    }

    protected function _databaseBeforeUpdate(Library\CommandContext $context)
    {
        $languages = $this->getService('application.languages');
        $active    = $languages->getActive();
        $primary   = $languages->getPrimary();

        if($active->iso_code != $primary->iso_code)
        {
            $table = $this->_tables->find(array('name' => $context->query->table))->top();
            if($table instanceof Library\DatabaseRowInterface && $table->enabled) {
                $context->query->table(strtolower($active->iso_code).'_'.$table->name);
            }
        }
    }

    protected function _databaseAfterUpdate(Library\CommandContext $context)
    {
        if($context->affected)
        {
            // Execute code only if query is not item specific.
            $params = $context->query->getParams();
            if(!isset($params['id']))
            {
                $languages = $this->getService('application.languages');
                $primary   = $languages->getPrimary();
                $active    = $languages->getActive();

                // Update item in other language specific tables.
                if($active->iso-code == $primary->iso_code) {
                    $table = $context->query->table;
                } else {
                    $table = substr($context->query->table, 6);
                }

                $table = $this->_tables->find(array('name' => $table))->top();
                if($table instanceof Library\DatabaseRowInterface && $table->enabled)
                {
                    foreach($languages as $language)
                    {
                        if($language->iso_code != $active->iso_code)
                        {
                            $query = clone $context->query;

                            if($language->iso_code == $primary->iso_code) {
                                $query->table($table->name);
                            } else {
                                $query->table(strtolower($language->iso_code).'_'.$table->name);
                            }

                            $context->getSubject()->execute($query);
                        }
                    }
                }
            }

        }
    }
}