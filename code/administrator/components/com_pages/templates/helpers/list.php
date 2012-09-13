<?php
class ComPagesTemplateHelperList extends KTemplateHelperAbstract
{
    public function pages($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'pages' => array()
        ));
        
        $pages  = $this->getService('application.pages');
        $active = $pages->getActive();
        
        $result     = '';
        $first      = true;
        $last_depth = 0;
        
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
            
            $result .= '<li '.($page->id == $active->id ? 'class="active"' : '').'>';
            switch($page->type)
            {
                case 'component':
    				$result .= '<a href="'.$this->getTemplate()->getView()->getRoute($page->link->getQuery().'&Itemid='.$page->id).'">';
                    $result .= $page->title;
                    $result .= '</a>';
    				break;
    				
    		    case 'menulink':
    		        $page_linked = $pages->getPage($page->link->query['Itemid']);
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