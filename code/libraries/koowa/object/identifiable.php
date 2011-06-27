<?php
/**
 * @version     $Id: interface.php 1061 2009-07-20 17:00:46Z johan $
 * @category    Koowa
 * @package     Koowa_Factory
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Object Identifiable Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Factory
 */
interface KObjectIdentifiable
{
    /**
     * Get the object identifier
     * 
     * @return  KIdentifier 
     */
    public function getIdentifier();
}