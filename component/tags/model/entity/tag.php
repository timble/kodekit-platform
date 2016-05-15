<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-tags for the canonical source repository
 */

namespace Kodekit\Component\Tags;

use Kodekit\Library;

/**
 * Tag Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Tag
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

            //Increase the tag count
            if($result)
            {
                $tag->count += 1;
                $result = $tag->save();
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

        if($count = $rowset->count())
        {
            //Delete the relations
            if($result = $rowset->delete())
            {
                //Update the count
                if($this->row)
                {
                    $this->count -= $count;
                    $result = parent::save();
                }
                else $result = parent::delete();
            }
        }

        return $result;
    }
}