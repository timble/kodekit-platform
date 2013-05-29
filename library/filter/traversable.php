<?php
/**
 * @package      Koowa_Filter
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link 		http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Filter Traversable Interface
 *
 * This interface signals FilterAbstract::getInstance() to decorate the Filter with a FilterIterator. The iterator
 * will traverse the data if it's traversable and filter each value separately.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
interface FilterTraversable { }