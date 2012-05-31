<?php
/**
 * Created by JetBrains PhpStorm.
 * User: amazeika
 * Date: 31/05/12
 * Time: 09:58
 * To change this template use File | Settings | File Templates.
 */
class ComArticlesControllerDefault extends ComDefaultControllerDefault
{

    public function setRequest($request) {

        // Filter rowsets based on current logged user's permissions.
        $user           = JFactory::getUser();
        $request['aid'] = $user->get('aid', 0);

        return parent::setRequest($request);
    }

}