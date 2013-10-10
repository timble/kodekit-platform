<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Component Template Locator
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\TemplateLoaderComponent
 */
class ApplicationTemplateLocatorComponent extends Library\TemplateLocatorComponent
{
    /**
     * Get a path from an file
     *
     * Function will check if a template override can be found based on the file.
     *
     * @param  string $file The file path
     * @return string The real file path
     */
    public function realPath($file)
    {
        //Theme override
        $theme  = $this->getObject('application')->getTheme();
        $theme  = JPATH_APPLICATION.'/public/theme/'.$theme.'/templates';
        $theme .= str_replace(array(JPATH_ROOT.'/component', '/view', '/templates'), '', $file);

        if($result = parent::realPath($theme)) {
            return $result;
        } else {
            return parent::realPath($file);
        }
    }
}