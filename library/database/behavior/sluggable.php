<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Database Sluggable Behavior
 *
 * Generates a slug, a short label for the row, containing only letters, numbers, underscores or hyphens. A slug is
 * generally used in an URL.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Database
 * @see     http://en.wikipedia.org/wiki/Slug_(web_publishing)
 */
class DatabaseBehaviorSluggable extends DatabaseBehaviorAbstract
{
    /**
     * The column name from where to generate the slug, or a set of column names to concatenate for generating the slug.
     *
     * Default is 'title'.
     *
     * @var array
     */
    protected $_columns;

    /**
     * Separator character / string to use for replacing non alphabetic characters in generated slug.
     *
     * Default is '-'.
     *
     * @var string
     */
    protected $_separator;

    /**
     * Maximum length the generated slug can have. If this is null the length of the slug column will be used.
     *
     * Default is NULL.
     *
     * @var integer
     */
    protected $_length;

    /**
     * Set to true if slugs should be re-generated when updating an existing row.
     *
     * Default is true.
     *
     * @var boolean
     */
    protected $_updatable;

    /**
     * Set to true if slugs should be unique. If false and the slug column has a unique index set this will result in
     * an error being throw that needs to be recovered.
     *
     * Default is NULL.
     *
     * @var boolean
     */
    protected $_unique;

    /**
     * Constructor.
     *
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_columns   = (array) ObjectConfig::unbox($config->columns);
        $this->_separator = $config->separator;
        $this->_updatable = $config->updatable;
        $this->_length    = $config->length;
        $this->_unique    = $config->unique;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'columns'    => 'title',
            'separator'  => '-',
            'updatable'  => true,
            'length'     => null,
            'unique'     => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Check if the behavior is supported
     *
     * Behavior requires a 'slug' row property
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $table = $this->getMixer();

        //Only check if we are connected with a table object, otherwise just return true.
        if($table instanceof DatabaseTableInterface)
        {
            if(!$table->hasColumn('slug'))  {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the slug
     *
     * This function will always return a unique slug. If the slug is not unique it will prepend the identity column
     * value.
     *
     * @return string
     */
    public function getSlug()
    {
        $result = $this->slug;
        if (!$this->getTable()->getColumn('slug', true)->unique)
        {
            $column = $this->getIdentityColumn();
            $result = $this->{$column} . $this->_separator . $this->slug;
        }

        return $result;
    }

    /**
     * Insert a slug
     *
     * If multiple columns are set they will be concatenated and separated by the separator in the order they are
     * defined.
     *
     * Requires a 'slug' column
     *
     * @param DatabaseContext	$context A database context object
     * @return void
     */
    protected function _beforeInsert(DatabaseContext $context)
    {
        $this->_createSlug();
    }

    /**
     * Update the slug
     *
     * Only works if {@link $updatable} property is TRUE. If the slug is empty the slug will be regenerated. If the
     * slug has been modified it will be sanitized.
     *
     * Requires a 'slug' column
     *
     * @param DatabaseContext	$context A database context object
     * @return void
     */
    protected function _beforeUpdate(DatabaseContext $context)
    {
        if ($this->_updatable) {
            $this->_createSlug();
        }
    }

    /**
     * Create a sluggable filter
     *
     * @return FilterSlug
     */
    protected function _createFilter()
    {
        $config = array();
        $config['separator'] = $this->_separator;

        if (!isset($this->_length)) {
            $config['length'] = $this->getTable()->getColumn('slug')->length;
        } else {
            $config['length'] = $this->_length;
        }

        //Create the filter
        $filter = $this->getObject('lib:filter.slug', $config);
        return $filter;
    }

    /**
     * Create the slug
     *
     * @return void
     */
    protected function _createSlug()
    {
        //Create the slug filter
        $filter = $this->_createFilter();

        if(empty($this->slug))
        {
            $slugs = array();
            foreach($this->_columns as $column) {
                $slugs[] = $filter->sanitize($this->$column);
            }

            $this->slug = implode($this->_separator, array_filter($slugs));
        }
        elseif($this->isModified('slug')) {
            $this->slug = $filter->sanitize($this->slug);
        }

        // Canonicalize the slug
        $this->_canonicalizeSlug();
    }

    /**
     * Make sure the slug is unique
     *
     * This function checks if the slug already exists and if so appends a number to the slug to make it unique. The
     * slug will get the form of slug-x.
     *
     * @return void
     */
    protected function _canonicalizeSlug()
    {
        $table = $this->getTable();

        //If unique is not set, use the column metadata
        if(is_null($this->_unique)) {
            $this->_unique = $table->getColumn('slug', true)->unique;
        }

        //If the slug needs to be unique and it already exists, make it unique
        $query = $this->getObject('lib:database.query.select');
        $query->where('slug = :slug')->bind(array('slug' => $this->slug));

        if (!$this->isNew()) 
        {
            $query->where($table->getIdentityColumn().' <> :id')
                ->bind(array('id' => $this->id));
        }

        if($this->_unique && $table->count($query))
        {
            $length = $this->_length ? $this->_length : $table->getColumn('slug')->length;

            // Cut 4 characters to make space for slug-1 slug-23 etc
            if ($length && strlen($this->slug) > $length-4) {
                $this->slug = substr($this->slug, 0, $length-4);
            }

            $query = $this->getObject('lib:database.query.select')
                ->columns('slug')
                ->where('slug LIKE :slug')
                ->bind(array('slug' => $this->slug . '-%'));

            $slugs = $table->select($query, Database::FETCH_FIELD_LIST);

            $i = 1;
            while(in_array($this->slug.'-'.$i, $slugs)) {
                $i++;
            }

            $this->slug = $this->slug.'-'.$i;
        }
    }
}