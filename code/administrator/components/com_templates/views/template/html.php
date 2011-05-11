<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template HTML View class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates    
 */
class ComTemplatesViewTemplateHtml extends ComTemplatesViewHtml
{
    public function display()
    {
        $this->getToolbar()
            ->prepend('divider')
            ->prepend('preview');

        $template     = $this->getModel()->getItem();
        $this->params = new JParameter($template->ini, $template->xml_file, 'template');

        return parent::display();
    }
}