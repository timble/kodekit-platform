<?php
/**
 * User: Oli Griffiths <http://github.com/oligriffiths>
 * Date: 06/10/2014
 * Time: 10:08
 */

namespace Nooku\Component\Users;

use Nooku\Library;

/**
 * Basic Auth Authenticator
 *
 * @author  Oli Griffiths <http://github.com/oligriffiths>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherAuthenticatorBasic extends Library\DispatcherAuthenticatorAbstract
{
    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config Configuration options
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $action = $config->action ?: 'dispatch';
        $this->addCommandCallback('before.'.$action, 'authenticateRequest');
    }

    /**
     * Initializes the options for the object
     *
     * @config
     * action: the default "before" action that the authenticator will respond to
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config A ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'action' => 'dispatch'
        ));

        parent::_initialize($config);
    }

    /**
     * Authenticate user against users data
     *
     * @param Library\DispatcherContextInterface $context	A dispatcher context object
     * @return  boolean Returns FALSE if the check failed. Otherwise TRUE.
     */
    public function authenticateRequest(Library\DispatcherContextInterface $context)
    {
        if(!$context->getUser()->isAuthentic()){

            $username = $context->request->getUser();
            $password = $context->request->getPassword();

            //Only auth if username is set
            if(!$username) return;

            //Find the user
            $user = $this->getObject('com:users.model.users')->email($username)->fetch();

            //Ensure we found a user
            if(!$user->id) throw new Library\ControllerExceptionRequestNotAuthenticated('Wrong email');

            //Check user password
            if (!$user->getPassword()->verifyPassword($password)) {
                throw new Library\ControllerExceptionRequestNotAuthenticated('Wrong password');
            }

            //Check user enabled
            if (!$user->enabled) {
                throw new Library\ControllerExceptionRequestNotAuthenticated('Account disabled');
            }

            //Set user data in context
            $data  = $this->getObject('user.provider')->load($user->id)->toArray();
            $data['authentic'] = true;

            $context->getUser()->setData($data);
        }
    }
}