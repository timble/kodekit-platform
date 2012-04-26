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

    passwordScore:function (password) {

        // Initialize score.
        var score = 0;

        // Password lenght > 6 = 1 point
        if (password.length > 6) score++;

        // Password lengnth > 12 = 1 point
        if (password.length > 12) score++;

        // Password has both lower and uppercase characters = 1 point
        if (( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) )) score++;

        // Password has at least one number = 1 point
        if (password.match(/\d+/)) score++;

        // Password has at least one special character = 1 point
        if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) score++;

        return score;
    }

}