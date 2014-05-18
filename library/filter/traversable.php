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
 * Filter Traversable Interface
 *
 * This interface signals FilterAbstract::getInstance() to decorate the Filter with a FilterIterator. The iterator
 * will traverse the data if it's traversable and filter each value separately.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Filter
 * @see KFilterAbstract::getInstance()
 */
interface FilterTraversable { }