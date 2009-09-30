<?php
uses('sanitize');
class UsersController extends AppController {

    public $helpers = array('Wildflower.List', 'Time');
    public $pageTitle = 'User Accounts';
    public $components = array('Email');
    /**
     * @TODO shit code, refactor
     *
     * Delete an user
     *
     * @param int $id
     */
    function admin_delete($id) {
        $id = intval($id);
        if ($this->RequestHandler->isAjax()) {
            return $this->User->del($id);
        }

        if (empty($this->data)) {
            $this->data = $this->User->findById($id);
            if (empty($this->data)) {
                $this->indexRedirect();
            }
        } else {
            $this->User->del($this->data[$this->modelClass]['id']);
            $this->indexRedirect();
        }
    }

    /**
     * Login screen
     *
     */
    function login() {
        $this->layout = 'login';   
        $this->pageTitle = 'Login';
        $User = ClassRegistry::init('User');

        // Try to authorize user with POSTed data
        if ($user = $this->Auth->user()) {
            if (!empty($this->data) && $this->data['User']['remember']) {
                // Generate unique cookie token
                $cookieToken = Security::hash(String::uuid(), null, true);
                
                while ($User->findByCookieToken($cookieToken)) {
                    $cookieToken = Security::hash(String::uuid(), null, true);
                }

                // Save token to DB
                $User->create($user);
                $User->saveField('cookie_token', $cookieToken);

                // Save login cookie
                $cookie = array();
                $cookie['login'] = $this->data['User']['login'];
                $cookie['cookie_token'] = $cookieToken;
                $this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
                unset($this->data['User']['remember']);
            }
            
            // Save last login time
            $User->create($user);
            $User->saveField('last_login', time());
            
            $this->redirect($this->Auth->redirect());
        }

        // Try to authorize user with data from a cookie
        if (empty($this->data)) {
            $cookie = $this->Cookie->read('Auth.User');
            if (!is_null($cookie)) {
                $this->Auth->fields = array(
                    'username' => 'login', 
                    'password' => 'cookie_token'
                );
                if ($this->Auth->login($cookie)) {
                    //  Clear auth message, just in case we use it.
                    $this->Session->del('Message.auth');
                    
                    // Save last login time
                    $User->create($user);
                    $User->saveField('last_login', time());
                    
                    return $this->redirect($this->Auth->redirect());
                } else { 
                    // Delete invalid Cookie
                    $this->Cookie->del('Auth.User');
                }
            }
        }
    }

    /**
     * Logout
     * 
     * Delete User info from Session, Cookie and reset cookie token.
     */
    function admin_logout() {
        $this->User->create($this->Auth->user());
        $this->User->saveField('cookie_token', '');
        $this->Cookie->del('Auth.User');
        $this->redirect($this->Auth->logout());
    }

    function admin_view($id) {
        $this->User->recursive = -1;
        $this->set('user', $this->User->findById($id));
    }

    /**
     * Users overview
     * 
     */
    function admin_index() {
        $users = $this->User->findAll();
        $this->set(compact('users'));
    }
    
    function admin_change_password($id = null) {
        $this->data = $this->User->findById($id);
    }

    /**
     * Create new user
     *
     */
    function admin_create() {
        if ($this->User->save($this->data)) {
            return $this->redirect(array('action' => 'index'));
        }

        $users = $this->User->find('all');
        $this->set(compact('users'));
        $this->render('admin_index');
    }

    /**
     * Edit user account
     *
     * @param int $id
     */
    function admin_edit($id = null) {
        $this->data = $this->User->findById($id);
        if (empty($this->data)) $this->cakeError('object_not_found');
    }
    
    function admin_update() {
        unset($this->User->validate['password']);
        $this->User->create($this->data);
        if ($this->User->save()) {
            return $this->redirect(array('action' => 'edit', $this->User->id));
        }
        $this->render('admin_edit');
    }
    
    function admin_update_password() {
        unset($this->User->validate['name'], $this->User->validate['email'], $this->User->validate['login']);
        App::import('Security');
        $this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
        $this->User->create($this->data);
        if (!$this->User->exists()) $this->cakeError('object_not_found');
        if ($this->User->save()) {
            return $this->redirect(array('action' => 'edit', $this->data[$this->modelClass]['id']));
        }
        $this->render('admin_change_password');
    }

    /**
    * Allows a user to sign up for a new account
    * @link http://www.jonnyreeves.co.uk/2008/05/user-registration-with-cakephp-12-and-auth-component/
    */
    function register(){
        // If the user submitted the form…
        if (!empty($this->data)){
            // do we require a confirmation email?
            $reqEmailConf = Configure::read('Wildflower.settings.require_email_confirmation') == 1;

            // Turn the supplied password into the correct Hash.
            // and move into the 'password' field so it will get saved.
            App::import('Security');
            $this->data['User']['password'] = Security::hash($this->data['User']['passwrd'], null, true);
            if($reqEmailConf) $this->data['User']['group_id'] = 3;
            else $this->data['User']['group_id'] = 4;
            // Always Sanitize any data from users!
            $this->User->data = Sanitize::clean($this->data);
            if ($this->User->save()){
                // Use a private method to send a confirmation email
                if($reqEmailConf) $this->__sendConfirmationEmail($this->User->getLastInsertID());
                // Success! Redirect to a thanks page.
                $this->redirect('/users/thanks');
            }
            // The plain text password supplied has been hashed into the 'password' field so
            // should now be nulled so it doesn't get render in the HTML if the save() fails
            $this->data['User']['passwrd'] = null;
            $this->data['User']['confirm_password'] = null;
        }
    }

    function thanks(){
        //hooks?
    }

    /**
    * Send out an activation email to the user.id specified by $user_id
    *  @param Int $user_id User to send activation email to
    *  @return Boolean indicates success
    *  @link http://www.jonnyreeves.co.uk/2008/06/cakephp-activating-user-account-via-email/
    */
    function __sendConfirmationEmail($user_id) {
        $user = $this->User->find(array('User.id' => $user_id), array('User.email', 'User.login'), null, false);
        if ($user === false) {
            debug(__METHOD__." failed to retrieve User data for user.id: {$user_id}");
            return false;
        }
        $link = 'http://' . env('HTTP_HOST') . str_replace('register','activate',env('REQUEST_URI')). DS . $user_id . DS . $this->User->getActivationHash();
        //echo 'activate link: <a href="'.$link.">$link</a>";

        // Set data for the "view" of the Email
        $this->set('activate_url', $link);
        $this->set('login', $this->data['User']['login']);

        $this->Email->to = $user['User']['email'];
        $this->Email->subject = Configure::read('Wildflower.settings.site_name') . ' - Please confirm your email address';
        $this->Email->from = Configure::read('Wildflower.settings.contact_email');
        $this->Email->template = 'user_confirm';
        $this->Email->sendAs = 'text';   // you probably want to use both :)
        return $this->Email->send();
    }

    /**
    * Activates a user account from an incoming link
    *
    *  @param Int $user_id User.id to activate
    *  @param String $in_hash Incoming Activation Hash from the email
    *  @link http://www.jonnyreeves.co.uk/2008/06/cakephp-activating-user-account-via-email/
    */
    function activate($user_id = null, $in_hash = null) {
        $this->User->id = $user_id;
        if ($this->User->exists() && ($in_hash == $this->User->getActivationHash())){
            // Update the active flag in the database
            //$this->User->saveField(‘active’, 1);
            $this->User->saveField('group_id', 4);
            // Let the user know they can now log in!
            $this->Session->setFlash('Your account has been activated, please log in.');
            $this->redirect('login');
        }
        // Activation failed, render ‘/views/user/activate.ctp’ which should tell the user.
    }

}
