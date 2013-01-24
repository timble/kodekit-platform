<?php
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
        
        foreach(clone $config->pages as $page)
        {
            $depth = substr_count($page->path, '/');
            
            if(substr($page->path, -1) != '/') {
                $depth++;
            }
        
            if($depth > $last_depth)
            {
                $result .= '<ul '.$this->_buildAttributes($config->attribs).'>';
                
                if($first && $config->title) {
                	$result .= '<li class="nav-header">'.$config->title.'</li>';
                }
                
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
            
            $classes = array();
            if($config->active)
            {
                if(in_array($page->id, array_merge($config->active->getParentIds(), (array) $config->active->id))) {
                    $classes[] = 'active';
                }
                
                if($page->id == $config->active->id) {
                    $classes[] = 'current';
                }
                
                foreach($config->pages as $value)
                {
                    if(strpos($value->path, $page->path.'/') === 0)
                    {
                        $classes[] = 'parent';
                        break;
                    }
                }
            }
            
            if($page->type == 'separator') {
            	$classes[] = 'nav-header';
            }
            
            $result .= '<li '.($classes ? 'class="'.implode(' ', $classes).'"' : '').'>';
            switch($page->type)
            {
                case 'component':
                    $link = $this->getService('koowa:dispatcher.router.route', array('url' => 'index.php?'.$page->getLink()->getQuery().'&Itemid='.$page->id, 'escape' => true));
    				$result .= '<a href="'.(string) $link.'">';
                    $result .= $page->title;
                    $result .= '</a>';
    				break;
    				
    		    case 'menulink':
    		        $page_linked = $this->getService('application.pages')->getPage($page->getLink()->query['Itemid']);
    		        $result .= '<a href="'.$page_linked->getLink().'">';
                    $result .= $page->title;
                    $result .= '</a>';
    				break;
    				
                case 'separator':
    				$result .= $page->title;
    				break;
    
    			case 'url':
    				$result .= '<a href="'.$page->getLink().'">';
                    $result .= $page->title;
                    $result .= '</a>';
    				break;
    				
    	        case 'redirect':
    	            $result .= '<a href="'.$page->route.'">';
    	            $result .= $page->title;
    	            $result .= '</a>';
            }
        }
        
        return $result;
    }
}