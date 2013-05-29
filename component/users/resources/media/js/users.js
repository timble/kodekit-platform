/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
var ComUsers = {

    Password:{

        score:function (password, words) {

            // Check if zxcvbn.js is already loaded.
            if (typeof zxcvbn !== 'function') return 0;

            var result = zxcvbn(password, words);

            return result.score;
        },

        checker:function (config) {

            var my = {};

            my.score = 0;
            my.config = config;

            my.initialize = function () {

                var config = this.config;

                window.addEvent('domready', function () {
                    $(config.input_id).addEvent('keyup', function () {

                        // Get user input values.
                        var words = Array.copy(config.words);
                        Array.each(config.user_input_ids, function (user_input_id, index) {
                            words.push($(user_input_id).get('value'));
                        });

                        var password = this.get('value');

                        my.score = ComUsers.Password.score(password, words);

                        if (password.length) my.score += 1;

                        $(config.container_id).set('class', config['class'] + ' ' + 'score' + my.score);
                        $(config.container_id).set('html', config.score_map[my.score]);
                    });

                    // Update password score on user input change.
                    Array.each(config.user_input_ids, function (user_input_id, index) {

                        var fireEvent = function () {
                            $(config.input_id).fireEvent('keyup');
                        };

                        $(user_input_id).addEvents({"keyup":fireEvent, "change":fireEvent});
                    });

                    // Intercept the move request in the controller chain.
                    if (config.min_score) {
                        $(config.container_id).getParent('form').addEvent('submit', function () {
                            if (my.score < config.min_score) {
                                alert(config.min_score_msg);
                                return false;
                            }
                        });
                    }
                });
            };

            my.initialize();

            return my;
        }
    },

    Form:{

        addValidator:function (validator) {
            // Check if validator exists.
            if (typeof ComUsers.Form.Validators[validator] == 'function') {
                ComUsers.Form.Validators[validator].call();
            } else {
                alert('Validator: ' + validator + ' not found.');
            }
        },

        addValidators:function(validators) {
            Array.each(validators, function(validator, idx) {
                ComUsers.Form.addValidator(validator);
            });
        },

        Validators:{

            passwordLength:function () {
                if (Form && Form.Validator) {
                    Form.Validator.add('passwordLength', {
                        errorMsg:function (element, props) {
                            return Form.Validator.getMsg('minLength').substitute({minLength:props.passwordLength, length:element.get('value').length});
                        },
                        test:function (element, props) {
                            var result = true;
                            var value = element.get('value');
                            // Only check if a password is set.
                            if (value.length) {
                                result = value.length >= props.passwordLength;
                            }
                            return result;
                        }
                    });
                }
            },

            passwordVerify:function () {
                if (Form && Form.Validator) {
                    Form.Validator.add('passwordVerify', {
                        errorMsg:function (element, props) {
                            return Form.Validator.getMsg('match').substitute({matchName: props.matchName || document.id(props.matchInput).get('name')});
                        },
                        test:function (element, props) {
                            var result = true;
                            var passwd = $(props.matchInput).get('value');
                            if (passwd.length && passwd != element.get('value')) {
                                result = false;
                            }
                            return result;
                        }
                    });
                }
            }
        }
    }
}
