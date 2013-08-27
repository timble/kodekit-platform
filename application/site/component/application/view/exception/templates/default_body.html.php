<body>

<div class="container">
    <h1><?= translate('Page not found') ?></h1>
    <p><strong><?= translate('You may not be able to visit this page because of:'); ?></strong></p>
    <ol>
        <li><?= translate('An out-of-date bookmark/favourite'); ?></li>
        <li><?= translate('A search engine that has an out-of-date listing for this site'); ?></li>
        <li><?= translate('A mis-typed address'); ?></li>
        <li><?= translate('You have no access to this page'); ?></li>
        <li><?= translate('The requested resource was not found'); ?></li>
        <li><?= translate('An error has occurred while processing your request.'); ?></li>
    </ol>
    <p><strong><?= translate('Please try one of the following pages:'); ?></strong></p>
    <ul>
        <li><a href="/" title="<?= translate('Go to the home page'); ?>"><?= translate('Home Page'); ?></a></li>
    </ul>
    <p><?= translate('If difficulties persist, please contact the system administrator of this site.'); ?></p>
    <div class="backtrace">
        <button id="backtrace__button" class="btn" onclick="toggleBacktrace()" data-text-less="<?= translate('Less') ?>" data-text-more="<?= translate('More') ?>">More</button>
    </div>
    <div id="backtrace__info" class="is-hidden">
        <? if(count($trace)) : ?>
            <?= import('default_backtrace.html'); ?>
        <? endif; ?>
    </div>
</div>

</body>