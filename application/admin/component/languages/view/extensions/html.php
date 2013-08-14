<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Extensions Html View
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Languages
 */
class LanguagesViewExtensionsHtml extends Library\ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'auto_assign' => false
        ));
        
        parent::_initialize($config);
    }
    
    public function render()
    {
        $tables     = $this->getObject('com:languages.model.tables')->getRowset();
        $extensions = $this->getObject('com:extensions.model.extensions')
            ->id(array_unique($tables->extensions_extension_id))
            ->getRowset();
        
        foreach($tables as $table) {
            $extensions->find($table->extensions_extension_id)->enabled = $table->enabled;
        }
        
        $this->extension = $extensions;
        $this->total      = count($extensions);
        
        return parent::render();
    }
}