<?php
uses('sanitize');
class WildUsersController extends AppController {

    public $helpers = array('Wildflower.List', 'Time');
    public $pageTitle = 'User Accounts';

    var $components = array('Email');
    
    /**
     * @TODO shit code, refactor
     *
     * Delete an user
     *
     * @param int $id
     */
    function wf_delete($id) {
        $id = intval($id);
        if ($this->RequestHandler->isAjax()) {
            return $this->WildUser->del($id);
        }

        if (empty($this->data)) {
            $this->data = $this->WildUser->findById($id);
            if (empty($this->data)) {
                $this->indexRedirect();
            }
        } else {
            $this->WildUser->del($this->data[$this->modelClass]['id']);
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

        if ($user = $this->Auth->user()) {
            if (!empty($this->data) && $this->data['WildUser']['remember']) {
                // Generate unique cookie token
                $cookieToken = Security::hash(String::uuid(), null, true);
                $WildUser = ClassRegistry::init('WildUser');
                while ($WildUser->findByCookieToken($cookieToken)) {
                    $cookieToken = Security::hash(String::uuid(), null, true);
                }

                // Save token to DB
                $WildUser->create($user);
                $WildUser->saveField('cookie_token', $cookieToken);

                // Save login cookie
                $cookie = array();
                $cookie['login'] = $this->data['WildUser']['login'];
                $cookie['cookie_token'] = $cookieToken;
                $this->Cookie->write('Auth.WildUser', $cookie, true, '+2 weeks');
                unset($this->data['WildUser']['remember']);
            }
            $this->redirect($this->Auth->redirect());
        }

        // Try login cookie
        if (empty($this->data)) {
            $cookie = $this->Cookie->read('Auth.WildUser');
            if (!is_null($cookie)) {
                $this->Auth->fields = array('username' => 'login', 'password' => 'cookie_token');
                if ($this->Auth->login($cookie)) {
                    //  Clear auth message, just in case we use it.
                    $this->Session->del('Message.auth');
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
    function wf_logout() {
        $this->WildUser->create($this->Auth->user());
        $this->WildUser->saveField('cookie_token', '');
        $this->Cookie->del('Auth.WildUser');
        $this->redirect($this->Auth->logout());
    }

    function wf_view($id) {
        $this->WildUser->recursive = -1;
        $this->set('user', $this->WildUser->findById($id));
    }

    /**
     * Users overview
     * 
     */
    function wf_index() {
        $users = $this->WildUser->findAll();
        $this->set(compact('users'));
    }
    
    function wf_change_password($id = null) {
        $this->data = $this->WildUser->findById($id);
    }

    /**
     * Create new user
     *
     */
    function wf_create() {
        if ($this->WildUser->save($this->data)) {
            return $this->redirect(array('action' => 'index'));
        }

        $users = $this->WildUser->find('all');
        $this->set(compact('users'));
        $this->render('wf_index');
    }

    /**
     * Edit user account
     *
     * @param int $id
     */
    function wf_edit($id = null) {
        $this->data = $this->WildUser->findById($id);
        if (empty($this->data)) $this->cakeError('object_not_found');
    }
    
    function wf_update() {
        unset($this->WildUser->validate['password']);
        $this->WildUser->create($this->data);
        if ($this->WildUser->save()) {
            return $this->redirect(array('action' => 'edit', $this->WildUser->id));
        }
        $this->render('admin_edit');
    }
    
    function wf_update_password() {
        unset($this->WildUser->validate['name'], $this->WildUser->validate['email'], $this->WildUser->validate['login']);
        App::import('Security');
        $this->data['WildUser']['password'] = Security::hash($this->data['WildUser']['password'], null, true);
        $this->WildUser->create($this->data);
        if (!$this->WildUser->exists()) $this->cakeError('object_not_found');
        if ($this->WildUser->save()) {
            return $this->redirect(array('action' => 'edit', $this->data[$this->modelClass]['id']));
        }
        $this->render('wf_change_password');
    }
    
    /**
    * Allows a user to sign up for a new account
    * @link http://www.jonnyreeves.co.uk/2008/05/user-registration-with-cakephp-12-and-auth-component/
    */
    function register(){
        // If the user submitted the form…
        if (!empty($this->data)){
            // do we require a confirmation email?
            $reqEmailConf = Configure::read('Wildflower.settings.require_email_confirmation') == 'on';
            
            // Turn the supplied password into the correct Hash.
            // and move into the 'password' field so it will get saved.
            App::import('Security');
            $this->data['WildUser']['password'] = Security::hash($this->data['WildUser']['passwrd'], null, true);
            if($reqEmailConf) $this->data['WildUser']['wild_group_id'] = 3;
            else $this->data['WildUser']['wild_group_id'] = 4;
            // Always Sanitize any data from users!
            $this->WildUser->data = Sanitize::clean($this->data);
            if ($this->WildUser->save()){
                // Use a private method to send a confirmation email
                if($reqEmailConf) $this->__sendConfirmationEmail($this->WildUser->getLastInsertID());
                
                // Success! Redirect to a thanks page.
                $this->redirect('/users/thanks');
            }
            // The plain text password supplied has been hashed into the 'password' field so
            // should now be nulled so it doesn't get render in the HTML if the save() fails
            $this->data['WildUser']['passwrd'] = null;
            $this->data['WildUser']['confirm_password'] = null;
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
        $user = $this->WildUser->find(array('WildUser.id' => $user_id), array('WildUser.email', 'WildUser.login'), null, false);
        if ($user === false) {
            debug(__METHOD__." failed to retrieve User data for user.id: {$user_id}");
            return false;
        }
        $link = 'http://' . env('HTTP_HOST') . str_replace('register','activate',env('REQUEST_URI')). DS . $user_id . DS . $this->WildUser->getActivationHash();
        //echo 'activate link: <a href="'.$link.">$link</a>";
        
        // Set data for the "view" of the Email
        $this->set('activate_url', $link);
        $this->set('login', $this->data['WildUser']['login']);
        
        $this->Email->to = $user['WildUser']['email'];
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
        $this->WildUser->id = $user_id;
        if ($this->WildUser->exists() && ($in_hash == $this->WildUser->getActivationHash())){
            // Update the active flag in the database
            //$this->User->saveField(‘active’, 1);
            $this->WildUser->saveField('wild_group_id', 4);
            
            // Let the user know they can now log in!
            $this->Session->setFlash('Your account has been activated, please log in.');
            $this->redirect('login');
        }
        // Activation failed, render ‘/views/user/activate.ctp’ which should tell the user.
    }
}
