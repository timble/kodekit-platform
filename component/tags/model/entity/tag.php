<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Tags;

use Nooku\Library;

/**
 * Tag Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Tag
 */
class ModelEntityTag extends Library\ModelEntityRow
{
    /**
     * Save the tag in the database.
     *
     * If the tag already exists, only add the relationship.
     *
     * @return bool
     */
    public function save()
    {
        $result = true;

        if($this->row)
        {
            $tag = $this->getTable()->select(array('title' => $this->title), Library\Database::FETCH_ROW);

            //Create the tag
            if($this->isNew() && $tag->isNew())
            {
                //Unset the row property
                $properties = $this->getProperties();
                unset($properties['row']);

                $result = $tag->setProperties($properties)->save();
            }

            //Create the tag relation
            if($result && !$tag->isNew())
            {
                $data = array(
                    'tag_id' => $tag->id,
                    'row'    => $this->row,
                );

                $name     = $this->getTable()->getName().'_relations';
                $table    = $this->getObject('com:tags.database.table.relations', array('name' => $name));
                $relation = $table->createRow(array('data' => $data));

                $result = $table->insert($relation);
            }
        }
        else $result = parent::save();

        return $result;
    }

    /**
     * Deletes the tag and it's relations form the database.
     *
     * @return bool
     */
    public function delete()
    {
        $result = true;

        $name   = $this->getTable()->getName().'_relations';
        $table  = $this->getObject('com:tags.database.table.relations', array('name' => $name));

        if($this->row) {
            $query = array('tag_id' => $this->id, 'row' => $this->row);
        } else {
            $query = array('tag_id' => $this->id);
        }

        $rowset = $table->select($query);

        if($rowset->count())
        {
            //Delete the relations
            if($result = $rowset->delete())
            {
                //Delete the tag
                if(!$this->row) {
                    $result = parent::delete();
                }
            }
        }

        return $result;
    }
}