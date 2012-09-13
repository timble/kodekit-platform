<?php
class ComPagesTemplateHelperList extends KTemplateHelperAbstract
{
    public function pages($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'pages'  => array(),
            'active' => null
        ));
        
        $application = $this->getService('application')->getIdentifier()->application;
        $result      = '';
        $first       = true;
        $last_depth  = 0;
        
        foreach($config->pages as $page)
        {
            $depth = substr_count($page->path, '/');
            
            if(substr($page->path, -1) != '/') {
                $depth++;
            }
        
            if($depth > $last_depth)
            {
                $result .= '<ul '.($first ? 'class="nav"' : '').'>';
                
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
                    $link = $this->getService('koowa:dispatcher.router.route', array('url' => 'index.php?'.$page->link->getQuery().($application == 'site' ? '&Itemid='.$page->id : ''), 'escape' => true));
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