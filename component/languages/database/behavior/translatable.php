<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Languages;

use Nooku\Library;

/**
 * Translatable Database Behavior
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Languages
 */
class DatabaseBehaviorTranslatable extends Library\DatabaseBehaviorAbstract implements Library\ObjectMultiton
{
    protected $_tables;
    
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);
        
        $this->_tables = $this->getObject('com:languages.model.tables')
            ->enabled(true)
            ->getRowset();
    }

    public function execute($name, Library\CommandContext $context)
    {
        $mixer  = $this->getMixer();
        $table  = $mixer instanceof Library\DatabaseTableInterface ? $mixer : $mixer->getTable();

        if($context->getSubject() instanceof $table) {
            $table->getAdapter()->getCommandChain()->enqueue($this);
        }

        $result = parent::execute($name, $context);

        if($context->subject instanceof $table) {
            $table->getAdapter()->getCommandChain()->dequeue($this);
        }

        return $result;
    }
    
    public function getHandle()
    {
        $return = null;
        $mixer  = $this->getMixer();

        if($mixer instanceof Library\DatabaseTableInterface || $mixer instanceof Library\DatabaseRowInterface || $mixer instanceof Library\DatabaseRowsetInterface)
        {
            // If table is not enabled, return null to prevent enqueueing.
            $table  = $mixer instanceof Library\DatabaseTableInterface ? $mixer : $mixer->getTable();
            $needle = array(
                'name' => $table->getBase(),
                'extension_name' => 'com_'.$table->getIdentifier()->package
            );

            if(count($this->_tables->find($needle))) {
                $return = parent::getHandle();
            }
        }
        else $return = parent::getHandle();

        return $return;
    }
    
    public function getMixableMethods(Library\ObjectMixable $mixer = null)
    {
        $methods = parent::getMixableMethods($mixer);
        
        if(!is_null($mixer))
        {
            // If table is not enabled, don't mix the methods.
            $table  = $mixer instanceof Library\DatabaseTableInterface ? $mixer : $mixer->getTable();
            $needle = array(
                'name' => $table->getBase(),
                'extension_name' => 'com_'.$table->getIdentifier()->package
            );
            
            if(!count($this->_tables->find($needle)))
            {
                $methods['isTranslatable'] = function() {
                    return false;
                };
                
                unset($methods['getLanguages']);
                unset($methods['getTranslations']);
            }
        }
        
        return $methods;
    }
    
    public function getLanguages()
    {
        return $this->getObject('application.languages');
    }
    
    public function getTranslations()
    {
        $translations = $this->getObject('com:languages.model.translations')
            ->table($this->getMixer()->getIdentifier()->package)
            ->row($this->id)
            ->getRowset();
        
        return $translations;
    }
    
    protected function _beforeTableSelect(Library\CommandContext $context)
    {
        if($query = $context->query)
        {
            $table     = $this->_tables->find(array('name' => $context->table))->top();
            $languages = $this->getObject('application.languages');
            $active    = $languages->getActive();
            $primary   = $languages->getPrimary();
            
            // Join translation to add status to rows.
            $state = $context->options->state;
            if(!$query->isCountQuery() && $state && !$state->isUnique() && isset($state->translated))
            {
                $query->columns(array(
                        'translation_status'    => 'translations.status',
                        'translation_original'  => 'translations.original',
                        'translation_deleted'   => 'translations.deleted'))
                    ->join(array('translations' => 'languages_translations'),
                        'translations.table = :translation_table'.
                        ' AND translations.row = tbl.'.$table->unique_column.
                        ' AND translations.iso_code = :translation_iso_code')
                    ->bind(array(
                        'translation_iso_code' => $active->iso_code,
                        'translation_table'    => $table->name
                    ));
                
                if(!is_null($state->translated))
                {
                    $status = $state->translated ? LanguagesDatabaseRowTranslation::STATUS_COMPLETED : array(
                        LanguagesDatabaseRowTranslation::STATUS_MISSING,
                        LanguagesDatabaseRowTranslation::STATUS_OUTDATED
                    );
                    
                    $query->where('translations.status IN :translation_status')
                        ->bind(array('translation_status' => (array) $status));
                }
            }
        }
    }
    
    protected function _afterTableInsert(Library\CommandContext $context)
    {
        if($context->affected)
        {
            $languages  = $this->getObject('application.languages');
            $active     = $languages->getActive();
            $primary    = $languages->getPrimary();
            $table      = $this->_tables->find(array('name' => $context->table))->top();
            $database   = $this->getTable()->getAdapter();
            $prefix     = $active->iso_code != $primary->iso_code ? strtolower($active->iso_code.'_') : '';

            $translation = array(
                'iso_code'   => $active->iso_code,
                'table'      => $context->table,
                'row'        => $context->data->id,
                'slug'       => $context->data->slug,
                'status'     => DatabaseRowTranslation::STATUS_COMPLETED,
                'original'   => 1
            );
            
            // Insert item into the translations table.
            $this->getObject('com:languages.database.row.translation')
                ->setData($translation)
                ->save();

            // Get the item data.
            $select = $this->getObject('lib:database.query.select')
                ->table($prefix.$table->name)
                ->where($table->unique_column.' = :unique')
                ->bind(array('unique' => $context->data->id));

            foreach($languages as $language)
            {
                if($language->iso_code != $active->iso_code)
                {
                    // Insert item into translations table.
                    $translation['iso_code'] = $language->iso_code;
                    $translation['status']   = DatabaseRowTranslation::STATUS_MISSING;
                    $translation['original'] = 0;
                    
                    $this->getObject('com:languages.database.row.translation')
                        ->setData($translation)
                        ->save();

                    // Copy the item's data to all missing translations.
                    $prefix = $language->iso_code != $primary->iso_code ? strtolower($language->iso_code.'_') : '';
                    $query = 'REPLACE INTO '.$database->quoteIdentifier($prefix.$table->name).' '.$select;
                    $database->execute($query);
                }
            }
        }
    }

    protected function _afterTableUpdate(Library\CommandContext $context)
    {
        $languages = $this->getObject('application.languages');
        $primary   = $languages->getPrimary();
        $active    = $languages->getActive();
        
        // Update item in the translations table.
        $table = $this->_tables->find(array('name' => $context->table))->top();
        $translation  = $this->getObject('com:languages.database.table.translations')
            ->select(array(
                'iso_code' => $active->iso_code,
                'table'    => $context->table,
                'row'      => $context->data->id
            ), Library\Database::FETCH_ROW);
        
        $translation->setData(array(
            'status' => DatabaseRowTranslation::STATUS_COMPLETED,
            'slug'   => $context->data->slug
        ))->save();
        
        // Set the other items to outdated if they were completed before.
        $query = $this->getObject('lib:database.query.select')
            ->where('iso_code <> :iso_code')
            ->where('table = :table')
            ->where('row = :row')
            ->where('status = :status')
            ->bind(array(
                'iso_code' => $active->iso_code,
                'table' => $context->table,
                'row' => $context->data->id,
                'status' => DatabaseRowTranslation::STATUS_COMPLETED
            ));
        
        $translations = $this->getObject('com:languages.database.table.translations')
            ->select($query);
        
        $translations->status = DatabaseRowTranslation::STATUS_OUTDATED;
        $translations->save();
        
        // Copy the item's data to all missing translations.
        $database = $this->getTable()->getAdapter();
        $prefix = $active->iso_code != $primary->iso_code ? strtolower($active->iso_code.'_') : '';
        $select = $this->getObject('lib:database.query.select')
            ->table($prefix.$table->name)
            ->where($table->unique_column.' = :unique')
            ->bind(array('unique' => $context->data->id));

        $query->bind(array('status' => DatabaseRowTranslation::STATUS_MISSING));
        $translations = $this->getObject('com:languages.database.table.translations')
            ->select($query);

        foreach($translations as $translation)
        {
            $prefix = $translation->iso_code != $primary->iso_code ? strtolower($translation->iso_code.'_') : '';
            $query = 'REPLACE INTO '.$database->quoteIdentifier($prefix.$table->name).' '.$select;
            $database->execute($query);
        }
    }

    protected function _beforeAdapterSelect(Library\CommandContext $context)
    {
        if($query = $context->query)
        {
            $languages = $this->getObject('application.languages');
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

    protected function _beforeAdapterInsert(Library\CommandContext $context)
    {
        $languages = $this->getObject('application.languages');
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

    protected function _afterAdapterInsert(Library\CommandContext $context)
    {
        if($context->affected)
        {
            // Insert item into language specific tables.
            $table = $this->_tables->find(array('name' => $context->query->table))->top();
            if($table instanceof Library\DatabaseRowInterface && $table->enabled)
            {
                $languages = $this->getObject('application.languages');
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

    protected function _beforeAdapterUpdate(Library\CommandContext $context)
    {
        $languages = $this->getObject('application.languages');
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

    protected function _afterAdapterUpdate(Library\CommandContext $context)
    {
        if($context->affected)
        {
            // Execute code only if query is not item specific.
            $params = $context->query->getParameters();
            if(!isset($params['id']))
            {
                $languages = $this->getObject('application.languages');
                $primary   = $languages->getPrimary();
                $active    = $languages->getActive();

                // Update item in other language specific tables.
                if($active->iso_code == $primary->iso_code) {
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

    protected function _beforeAdapterDelete(Library\CommandContext $context)
    {
        $languages = $this->getObject('application.languages');
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

    protected function _afterAdapterDelete(Library\CommandContext $context)
    {
        if($context->affected)
        {
            $languages = $this->getObject('application.languages');
            $primary   = $languages->getPrimary();
            $active    = $languages->getActive();

            // Remove item from other tables too.
            if($active->iso_code == $primary->iso_code) {
                $table = $context->query->table;
            } else {
                $table = substr($context->query->table['0'], 6);
            }

            $table = $this->_tables->find(array('name' => $table))->top();
            if($table instanceof Library\DatabaseRowInterface && $table->enabled)
            {
                foreach($languages as $language)
                {
                    if($language->iso_code != $active->iso_code)
                    {
                        $query = clone $context->query;

                        $prefix = $language->iso_code != $primary->iso_code ? strtolower($language->iso_code.'_') : '';
                        $query->table($prefix.$table->name);

                        $context->getSubject()->execute($query);
                    }
                }

                // Mark item as deleted in translations table.
                $this->getObject('com:languages.database.table.translations')
                    ->select(array('table' => $table->name, 'row' => $query->data[$table->unique_column]))
                    ->setData(array('deleted' => 1))
                    ->save();
            }
        }
    }
}