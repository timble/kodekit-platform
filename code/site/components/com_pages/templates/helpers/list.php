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
            
            $classes = array();
            if($config->active)
            {
                if(in_array($page->id, array_merge($config->active->parent_ids, (array) $config->active->id))) {
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
            
            $result .= '<li '.($classes ? 'class="'.implode(' ', $classes).'"' : '').'>';
            switch($page->type)
            {
                case 'component':
                    $link = $this->getService('koowa:dispatcher.router.route', array('url' => 'index.php?'.$page->link->getQuery().'&Itemid='.$page->id, 'escape' => true));
    				$result .= '<a href="'.(string) $link.'">';
                    $result .= $page->title;
                    $result .= '</a>';
    				break;
    				
    		    case 'menulink':
    		        $page_linked = $this->getService('application.pages')->getPage($page->link->query['Itemid']);
    		        $result .= '<a href="'.$page_linked->link.'">';
                    $result .= $page->title;
                    $result .= '</a>';
    				break;
    				
                case 'separator':
    				$result .= '<span class="separator">'.$page->title.'</span>';
    				break;
    
    			case 'url':
    				$result .= '<a href="'.$page->link.'">';
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