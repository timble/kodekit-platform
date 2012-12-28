<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Article Element Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class JElementArticle extends JElement
{
    var $_name = 'Article';

    function fetchElement($name, $value, &$node, $control_name)
    {
        if(is_numeric($value))
        {
            $title = KService::get('com://admin/articles.model.articles')
                ->id($value)
                ->getRow()
                ->title;
        }
        else $title = JText::_('Select an Article');

        $fieldName  = $control_name.'['.$name.']';

        $html = "<script>
        function jSelectArticle(id, title, object) {
            document.getElementById(object + '_id').value = id;
            document.getElementById(object + '_name').value = title;
            SqueezeBox.close();
        }
        </script>";

        $link = JRoute::_('index.php?option=com_articles&view=articles&layout=element&tmpl=overlay&object='.$name);

        JHTML::_('behavior.modal', 'a.modal');
        $html .= "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($title, ENT_QUOTES, 'UTF-8').'" disabled="disabled" /></div>';
        $html .= '<a style="margin-left: 10px;" class="btn modal" title="'.JText::_('Select an Article').'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 650, y: 375}}">'.JText::_('Select').'</a>'."\n";
        $html .= "\n".'<input type="hidden" id="'.$name.'_id" name="'.$fieldName.'" value="'.(int)$value.'" />';

        return $html;
    }
}