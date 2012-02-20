<?php

KLoader::loadIdentifier('com://site/attachments.aliases');

echo KService::get('com://site/attachments.dispatcher')->dispatch();