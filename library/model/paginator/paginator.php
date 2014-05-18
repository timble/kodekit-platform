<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Paginator Model Class
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Model
 */
class ModelPaginator extends ObjectConfig implements ModelPaginatorInterface
{
    /**
     * Get the pages
     *
     * @return ObjectConfig A ObjectConfig object that holds the page information
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Set a configuration element
     *
     * @param  string 
     * @param  mixed 
     * @return void
     */
    public function set($name, $value)
    {
        parent::set($name, $value);
        
        //Only calculate the limit and offset if we have a total
        if($this->total)
        {
            $this->limit  = (int) max($this->limit, 1);
            $this->offset = (int) max($this->offset, 0);
        
            if($this->limit > $this->total) {
                $this->offset = 0;
            }
           
            if(!$this->limit) 
            {
                $this->offset = 0;
                $this->limit  = $this->total;
            }

            $this->count  = (int) ceil($this->total / $this->limit);

            if($this->offset > $this->total) {
                $this->offset = ($this->count-1) * $this->limit;
            }

            $this->current = (int) floor($this->offset / $this->limit) + 1;
        }
    }
    
 	/**
     * Implements lazy loading of the pages config property.
     *
     * @param string 
     * @return mixed
     */
    public function __get($name)
    {
        if($name == 'pages' && !isset($this->pages)) {
            $this->pages = $this->_pages();
        }
        
        return $this->get($name);
    }
   
 	/**
     * Get a list of pages
     *
     * @return  array   Returns and array of pages information
     */
    protected function _pages()
    {
        $pages = new ObjectConfig();
        $current  = ($this->current - 1) * $this->limit;
        
        // First
        $offset = 0;
        $class  = $offset == $this->offset ? 'pagination__first disabled' : 'pagination__first';
        $pages->first = array('title' => 'First', 'page' => 1, 'offset' => $offset, 'limit' => $this->limit, 'attribs' => array('class' => $class));
      
        // Previous
        $offset = max(0, ($this->current - 2) * $this->limit);
        $class  = $offset == $this->offset ? 'pagination__previous disabled' : 'pagination__previous';
        $pages->prev = array('title' => 'Previous', 'page' => $this->current - 1, 'offset' => $offset, 'limit' => $this->limit, 'rel' => 'prev', 'attribs' => array('class' => $class));

        // Pages
        $offsets = array();
        foreach($this->_offsets() as $page => $offset)
        {
            $class = $offset == $this->offset ? 'pagination__offset active' : 'pagination__offset';
            $offsets[] = array('title' => $page, 'page' => $page, 'offset' => $offset, 'limit' => $this->limit, 'attribs' => array('class' => $class));
        }
        
        $pages->offsets = $offsets;
        
        // Next
        $offset = min(($this->count-1) * $this->limit, ($this->current) * $this->limit);
        $class  = $offset == $this->offset ? 'pagination__next disabled' : 'pagination__next';
        $pages->next = array('title' => 'Next', 'page' => $this->current + 1, 'offset' => $offset, 'limit' => $this->limit, 'rel' => 'next', 'attribs' => array('class' => $class));
       
        // Last
        $offset = ($this->count - 1) * $this->limit;
        $class  = $offset == $this->offset ? 'pagination__last disabled' : 'pagination__last';
        $pages->last = array('title' => 'Last', 'page' => $this->count, 'offset' => $offset, 'limit' => $this->limit, 'attribs' => array('class' => $class));
        
        return $pages;
    }
    
    /**
     * Get the offset for each page, optionally with a range
     *
     * @return  array   Page number => offset
     */
    protected function _offsets()
    {
        if($display = $this->display)
        {
            $start  = (int) max($this->current - $display, 1);
            $start  = min($this->count, $start);
            $stop   = (int) min($this->current + $display, $this->count);
        }
        else // show all pages
        {
            $start = 1;
            $stop = $this->count;
        }

        $result = array();
        if($start > 0)
        {
            foreach(range($start, $stop) as $pagenumber) {
                $result[$pagenumber] =  ($pagenumber-1) * $this->limit;
            }
        }

        return $result;
    }
}