/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
var ComUsers = {

    passwordScore:function (password, user_inputs) {

        // Check if zxcvbn.js is already loaded.
        if (typeof zxcvbn !== 'function') return 0;

        var result = zxcvbn(password, user_inputs);

        return result.score;
    }
}
