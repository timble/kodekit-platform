<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * List Template Helper Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesTemplateHelperList extends KTemplateHelperAbstract
{
    public function pages($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'pages'   => array(),
            'active'  => null,
            'attribs' => array('class' => array('nav'))
        ));
        
        $result     = '';
        $first      = true;
        $last_depth = 0;
        
        $disabled = $this->getService('component')->getController()->getView()->getLayout() == 'form';
        if($disabled) {
            $config->append(array('attribs' => array('class' => array('disabled'))));
        }
        
        foreach($config->pages as $page)
        {
            $depth = substr_count($page->path, '/');
            
            if(substr($page->path, -1) != '/') {
                $depth++;
            }
        
            if($depth > $last_depth)
            {
                $result .= $first ? '<ul '.$this->_buildAttributes($config->attribs).'>' : '<ul>';
                
                $last_depth = $depth;
                $first      = false;
            }
        
            if($depth < $last_depth)
            {
                $result .= str_repeat('</li></ul>', $last_depth - $depth);
                $last_depth = $depth;
            }
            
            if($depth == $last_depth) {
                $result .= '</li>';
            }

            $result .= '<li '.($config->active && $page->id == $config->active->id ? 'class="active"' : '').'>';
            switch($page->type)
            {
                case 'component':
                    $link = $this->getTemplate()->getView()->getRoute($page->getLink()->getQuery());
    				$result .= $disabled ? '<span class="nolink">' : '<a href="'.(string) $link.'">';
                    $result .= $page->title;
                    $result .= $disabled ? '</span>' : '</a>';
    				break;
    				
    		    case 'menulink':
    		        $page_linked = $this->getService('application.pages')->getPage($page->getLink()->query['Itemid']);
    		        $result .= $disabled ? '<span class="nolink">' : '<a href="'.$page_linked->getLink().'">';
                    $result .= $page->title;
                    $result .= $disabled ? '</span>' : '</a>';
    				break;
    				
                case 'separator':
    				$result .= '<span class="separator '.($disabled ? 'nolink' : '').'">'.$page->title.'</span>';
    				break;
    
    			case 'url':
    				$result .= $disabled ? '<span class="nolink">' : '<a href="'.$page->getLink().'">';
                    $result .= $page->title;
                    $result .= $disabled ? '</span>' : '</a>';
    				break;
    				
    	        case 'redirect':
    	            $result .= $disabled ? '<span class="nolink">' : '<a href="'.$page->route.'">';
    	            $result .= $page->title;
    	            $result .= $disabled ? '</span>' : '</a>';
            }
        }
        
        return $result;
    }
}