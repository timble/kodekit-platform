<?php

use Nooku\Framework;

interface PagesDatabaseBehaviorTypeInterface
{
    public function getTypeTitle();

    public function getTypeDescription();

    public function getParams($group);

    public function getLink();
}