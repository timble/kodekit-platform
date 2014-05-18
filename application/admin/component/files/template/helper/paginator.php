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
 * Paginator Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Files
 */
class FilesTemplateHelperPaginator extends ApplicationTemplateHelperPaginator
{
    /**
     * Render item pagination
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     * @see     http://developer.yahoo.com/ypatterns/navigation/pagination/
     */
    public function pagination($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'limit'   => 0,
        ));

        $html = '';
        $html .= '<div class="pagination">';
        $html .= '<div class="limit">'.$this->translate('Display NUM').' '.$this->limit($config).'</div>';
        $html .=  $this->pages($config);
        $html .= '<div class="limit"> '.$this->translate('Page').' <span class="page-current">1</span>';
        $html .= ' '.$this->translate('of').' <span class="page-total">1</span></div>';
        $html .= '</div>';

        return $html;
    }

    public function pages($config = array())
    {
        $config = new Library\ModelPaginator($config);
		$config->append(array(
			'total'      => 0,
			'display'    => 4,
			'offset'     => 0,
			'limit'	     => 0,
			'attribs'	=> array(),
		));

		$html   = '<div class="btn-group">'.$this->link($config->pages->first).'</div>';
		$html  .= '<div class="btn-group">';
		$html  .= $this->link($config->pages->prev);
		$html  .= '</div>';
		$html  .= '<div class="btn-group page-list"></div>';
		$html  .= '<div class="btn-group">';
		$html  .= $this->link($config->pages->next);
		$html  .= '</div>';
		$html  .= '<div class="btn-group">'.$this->link($config->pages->last).'</div>';

		return $html;
    }
}