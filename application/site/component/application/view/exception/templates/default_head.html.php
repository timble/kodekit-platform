<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<head>
    <ktml:style>
    <ktml:script>

    <link href="media://application/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />

    <style src="media://application/stylesheets/default.css" />
    <script>
        function toggleBacktrace() {
            var helpBoxOuter = document.getElementById('backtrace__info');
            helpBoxOuter.classList.toggle('is-hidden');
            var moreLessButton = document.getElementById('backtrace__button');
            if (helpBoxOuter.classList.contains('is-hidden')) {
                moreLessButton.innerText = moreLessButton.getAttribute('data-text-more');
            } else {
                moreLessButton.innerText = moreLessButton.getAttribute('data-text-less');
            }
        }
    </script>

    <title><?= translate('Error').': '.$code; ?></title>
</head>