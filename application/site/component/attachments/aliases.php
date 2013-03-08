<?php

use Nooku\Framework\ServiceManager;

ServiceManager::setAlias('com://site/attachments.model.attachments', 'com://admin/attachments.model.attachments');
ServiceManager::setAlias('com://site/attachments.view.attachment.file', 'com://admin/attachments.view.attachment.file');