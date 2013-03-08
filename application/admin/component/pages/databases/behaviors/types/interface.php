<?php

use Nooku\Framework;

interface ComPagesDatabaseBehaviorTypeInterface
{
    public function getTypeTitle();

    public function getTypeDescription();

    public function getParams($group);

    public function getLink();
}