<?php
class ComArticlesTemplateHelperGrid extends KTemplateHelperGrid
{
    public function state($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'row' => null,
        ));

        switch($config->row->state)
        {
            case -1:
                $image = 'disabled.png';
                $alt   = JText::_('Archived');
                $text  = JText::_('Unarchive Item');
                $value = 0;

                break;

            case 0:
                $image = 'publish_x.png';
                $alt   = JText::_('Unpublished');
                $text  = JText::_('Publish Item');
                $value = 1;

                break;

            case 1:
                $now = gmdate('U');

                if($now <= strtotime($config->row->publish_up))
                {
                    $image = 'publish_y.png';
                    $alt   = JText::_('Published');
                }
                elseif($now <= strtotime($config->row->publish_down) || !(int) $config->row->publish_down)
                {
                    $image = 'publish_g.png';
                    $alt   = JText::_('Published');
                }
                else
                {
                    $image = 'publish_r.png';
                    $alt   = JText::_('Expired');
                }

                $text  = JText::_('Unpublish Item');
                $value = 0;

                break;
        }

        $url   = 'index.php?option=com_articles&view=article&id='.$config->row->id;
        $token = JUtility::getToken();
        $rel   = "{method:'post', url:'$url', params:{state:$value, _token:'$token', action:'edit'}}";

        $html[] = '<script src="media://lib_koowa/js/koowa.js" />';
        $html[] = '<img src="media://system/images/'.$image.'" border="0" alt="'.$alt.'" class="submitable" rel="'.$rel.'" />';

        return implode(PHP_EOL, $html);
    }
}