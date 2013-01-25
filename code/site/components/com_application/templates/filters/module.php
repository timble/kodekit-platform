<?php
/**
 * @version     $Id: link.php -1 1970-01-01 00:00:00Z  $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Template Filter Class
 *
 * Filter will parse elements of the form <html:modules position="[position]" /> and render the modules that are
 * available for this position.
 *
 * Filter will parse elements of the form <html:module position="[position]">[content]</module> and inject the
 * content into the module position.
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationTemplateFilterModule extends ComPagesTemplateFilterModule
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'modules' => $this->getService('application.modules'),
        ));

        parent::_initialize($config);
    }
}