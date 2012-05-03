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

    passwScore:function (password, words) {

        // Check if zxcvbn.js is already loaded.
        if (typeof zxcvbn !== 'function') return 0;

        var result = zxcvbn(password, words);

        return result.score;
    },

    bindPasswCheck:function (config) {

        window.addEvent("domready", function () {
            $(config.input_id).addEvent("keyup", function () {

                // Get user input values.
                var words = Array.copy(config.words);
                Array.each(config.user_input_ids, function (user_input_id, index) {
                    words.push($(user_input_id).get("value"));
                });

                var score = ComUsers.passwScore(this.get("value"), words) + 1;
                $(config.container_id).set("class", config['class'] + " " + "score" + score);
                $(config.container_id).set("html", config.score_map[score]);
            });

            // Update password score on user input change.
            Array.each(config.user_input_ids, function (user_input_id, index) {

                var fireEvent = function () {
                    $(config.input_id).fireEvent("keyup");
                };

                $(user_input_id).addEvents({"keyup":fireEvent, "change":fireEvent});
            });
        });
    }
}
