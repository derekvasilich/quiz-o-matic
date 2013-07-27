<?

App::uses('CakeEmail', 'Network/Email');
App::uses('Sanitize', 'Utility');

class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'forgot', 'logout', 'reset', 'statement', 'quit_impersonating', 'signup');
        $this->Auth->mapActions(AuthActionsMap::CRUD('users'));
    }
    
    public function login() {
        $this->layout = 'splash';

        if (!$this->request->is('post'))
            return;
        
        if (!isset($this->request->data['User']))
            return; 
        
        if (!$this->Auth->login()) {
            $this->User->recursive = -1;
//            debug(__('BEFORE: %s (%s)', $this->request->data['User']['password'], AuthComponent::password($this->request->data['User']['password'])));
            $user = $this->User->findByUsername($this->request->data['User']['username']);
            if ($user) {
                $this->User->id = $user['User']['id'];
                $this->User->saveField('attempts', $user['User']['attempts']+1);
//                debug(__('FAILED: %s (%s) ', $user['User']['password'], $user['User']['attempts']));
                if ($user['User']['attempts']+1 >= 3) {
                    if ($user['User']['attempts']+1 == 3)
                        CakeLog::write('attempts', __('Login failed for %s (IP was %s), bad attempt %d [locked]', 
                                $this->request->data['User']['username'], $this->request->clientIp(), $user['User']['attempts']+1));
                    $this->Session->setFlash(__('You have exceeded the maximum number of login attempts.'), 'fail');
                    return $this->redirect($this->Auth->logout());
                }            
            }
            return $this->Session->setFlash(__('Invalid username or password. Please, try again.'), 'fail');
        }

//        debug(__("SUCCESS: %s", $this->Auth->user('password')));
        
        $modifed = strtotime($this->Auth->user('modified'));
        $mins_since = (time() - $modifed)/60;
        if ($this->Auth->user('is_active') != 1) {
            CakeLog::write('attempts', __('Login failed for %s (IP was %s), account deactivated', 
                    $this->request->data['User']['username'], $this->request->clientIp()));
            $this->Session->setFlash(__('You account has been deactivated.'), 'fail');
            return $this->redirect($this->Auth->logout());
        } else if ($this->Auth->user('attempts') >= 3 && $mins_since < 15) {
            $this->Session->setFlash(__('Your account has been temporarily locked.'), 'fail');
            return $this->redirect($this->Auth->logout());
        }
        
        CakeLog::write('attempts', __('Login succeeded for %s (IP was %s)', $this->request->data['User']['username'], $this->request->clientIp()));
//        $this->_stash_acl();

        // save the user agent information and reset attempts
        $this->User->id = $this->Auth->user('id');
        $this->User->saveField('user_agent', env('HTTP_USER_AGENT'));        
        $this->User->id = $this->Auth->user('id');
        $this->User->saveField('attempts', 0);        
        
        return $this->redirect($this->Auth->redirect());
    }
    
    protected function _stash_acl() {
        if (!$this->Auth->loggedIn())
            return false;
        
        $group_aros = $this->Acl->Aro->find('first', array(
            'conditions' => array(
                'Aro.model' => 'Group',
                'Aro.foreign_key' => $this->Auth->user('group_id')
            )
        ));
        $group_acos = $this->Acl->Aco->children();
        $this->_stash_perms($group_aros, $group_acos, 'Perms');
//        $user_aros = $this->Acl->Aro->find('first', array(
//            'conditions' => array(
//                'Aro.model' => 'User',
//                'Aro.foreign_key' => $this->Auth->user('id')
//            )
//        ));
//        $user_acos = $this->Acl->Aco->children();
//        $this->_stash_perms($user_aros, $user_acos, 'UserPerms');
        return true;
    }
    
    protected function _stash_perms($aro, $acos, $key) {
        foreach($acos as $aco){            
            $permission = $this->Acl->Aro->Permission->find('first', array(
                'conditions' => array(
                    'Permission.aro_id' => $aro['Aro']['id'],
                    'Permission.aco_id' => $aco['Aco']['id']
                )
            ));
            $p = &$permission['Permission'];
            if (isset($p['id'])) {
                $map = AuthActionsMap::CRUD(Inflector::underscore($permission['Aco']['alias']));
                $actions = array();
                if ($p['_create'] == 1 && isset($map['create']))
                    $actions = array_merge($actions, $map['create']);
                if ($p['_read'] == 1 && isset($map['read']))
                    $actions = array_merge($actions, $map['read']);
                if ($p['_update'] == 1 && isset($map['update']))
                    $actions = array_merge($actions, $map['update']);
                if ($p['_delete'] == 1 && isset($map['delete']))
                    $actions = array_merge($actions, $map['delete']);
                if ($key == 'UserPerms') {
                    foreach ($actions as $action)
                        $this->Session->write('Auth.'.$key.'.'.Inflector::pluralize($aco['Aco']['model']).'.'.$action, $aco['Aco']['foreign_key']);
                } else 
                    $this->Session->write('Auth.'.$key.'.'.$permission['Aco']['alias'], array_fill_keys($actions, true));
            }
        }

    }
    
    public function statement($statement_type = null) {
        if (!$this->Auth->loggedIn())
            throw new NotFoundException(__('You must be logged in to view this page'));
        
        if (!$statement_type) {
            $g = strtolower($this->Auth->user('Group.name'));
            if ($g === 'trainee' || $g === 'resident')
                $statement_type = 'privacy';
            else 
                $statement_type = 'confidentiality';
        }
        
        if ($statement_type !== 'privacy' 
                && $statement_type !== 'confidentiality')
            throw new NotFoundException(__('Invalid statement type.'));
        
        $statement_field = $statement_type.'_signed';
        $statement_name = $statement_type.'_statement';
        $this->layout = 'splash';
        if ($this->request->is('post')) {
            if (array_key_exists('Statement', $this->request->data)
                    && array_key_exists('agree', $this->request->data['Statement'])
                    && $this->request->data['Statement']['agree'] === '1') {
                $user = $this->Auth->user();
                $now = date(DATE_ATOM);
                $msg = sprintf("%s signed by %s via IP address %s at %s", $statement_name, $user['name'], $_SERVER['REMOTE_ADDR'], $now);
                $this->log($msg, $statement_name);
                $this->User->id = $user['id'];
                if (!$this->User->saveField($statement_field, $now)) {
                    $this->Session->setFlash(__('Unable create your digital signature at this time.'), 'fail');                                    
                } else {
                    $this->_refresh_auth($statement_field, $now);
                    $this->Session->setFlash(__('Your digital signature has been recorded.'), 'success'); 
                    return $this->redirect($this->Auth->redirect());
                }
            } else {
                $this->Session->setFlash(__('You must accept the terms to continue.'), 'fail');                
            }
        }   
        $this->set('title', Inflector::humanize($statement_name));        
        $this->set('statement', $this->Settings->get($statement_name));
    }
    
    function _refresh_auth($field = '', $value = '') {
        if (!empty($field) && !empty($value)) { 
            $this->Session->write('Auth.User.' . $field, $value);
        }
    }

    protected function _clear_session() {
        $this->Session->delete('Auth');
        $this->Session->delete('Settings');
        $this->Session->delete('Sort');
    }
    
    public function logout() {
        $this->_clear_session();
        $this->Session->setFlash('You have successfully logged out of Quizmatic.', 'success');
        $this->redirect($this->Auth->logout());
    }
   
    public function forgot() {
        $this->layout = 'splash';
        if ($this->request->is('post')) {
            $user = $this->User->findByUsernameAndFirstNameAndLastName(
                        $this->request->data('Forgot.username'),
                        $this->request->data('Forgot.first_name'), 
                        $this->request->data('Forgot.last_name')
                    );
            if (!$user) 
                $this->Session->setFlash(__('No user matching that information exists.'), 'fail');
            else {                
                // generate cryptographic key
                $bytes = openssl_random_pseudo_bytes('64');
                $hex = bin2hex($bytes); 
                
                $user = $this->User->read(null, $user['User']['id']);
                $this->User->saveField('key', $hex);                
                $to = $this->User->field('email');
                if (!$to || !strlen($to)) { 
                    $this->Session->setFlash(__('No user matching that information exists.'), 'fail');
                    return;
                }
                
                $title = 'Password Reset';
                // email user including link with key
                $email = new CakeEmail('smtp');
                $email->from(array('dwilliams@diamondl.ca' => 'Diamond L Consulting'))
                    ->to($to)
                    ->template('default', 'default')
                    ->subject('VENTIS Email Notification')
                    ->viewVars(compact('hex', 'title'))
                    ->emailFormat('html')
                    ->send('Please follow the link below to enter your new password.');
                
                $this->Session->setFlash(__('An email has been sent to your address.'
                    . 'Please refer to this email for instructions on resetting your password.'), 'success');
                
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
        }
    }
    
    public function reset($key = null) {
        $this->layout = 'splash';

        if (!$key || !strlen($key))
            throw new NotFoundException(__('Invalid key.'));            

        $user = $this->User->findByKey($key);
        if (!$user)
            throw new NotFoundException(__('You password has already been reset.'));        
        
        if ($this->request->is('post')) {
            $this->User->set($user);
            $this->User->set('key', '');
            $this->User->set('password', $this->request->data['User']['password']);
            $this->User->set('confirm_password', $this->request->data['User']['confirm_password']);
            if (!$this->User->validates())
                $this->Session->setFlash(__('The passwords are not valid.'));
            else if ($this->User->save($this->request->data, false, array('password', 'key'))) {
                $this->Session->setFlash(__('Your password has been updated. Please login below.'), 'success');
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'fail');
            }
        }            
    }

    public function impersonate($id) {
        $this->User->recursive = 1;
        $user = $this->User->read(null, $id);
        if (!$user) throw new NotFoundException(__('Invalid user'));
        if (Group::requires_privacy($user['Group']['type']) && !$this->_valid_signed($user['User']['privacy_signed'])) {
            $this->Session->setFlash(__('%s cannot be impersonated until his/her Privacy Statement has been signed.', 
                    $user['User']['name']), 'fail');            
            return $this->redirect($this->referer());
        }                    
        if (!$this->_valid_signed($user['User']['confidentiality_signed'])) {
            $this->Session->setFlash(__('%s cannot be impersonated until his/her Confidentiality Statement has been signed.', 
                    $user['User']['name']), 'fail');            
            return $this->redirect($this->referer());
        }
        $user['User']['impersonating'] = true;
        $user['User']['impersonator'] = $this->Auth->user('id');
        $user['User']['impersonator_name'] = $this->Auth->user('name');
        $this->_clear_session();
        $this->Session->write('Auth', $user);
        $this->Session->write('Auth.User.Group', $user['Group']);
        $this->_stash_acl();
        $this->redirect($this->Auth->redirect());
    }
    
    public function quit_impersonating() {
        if (!$this->Auth->user('impersonating'))
            throw new ForbiddenException('Permission denied');
        $uid = $this->Auth->user('impersonator');
        $this->User->recursive = 1;
        $user = $this->User->read(null, $uid);
        if (!$user)
            throw new NotFoundException('Invalid user');
        $this->_clear_session();
        $this->Session->write('Auth', $user);
        $this->Session->write('Auth.User.Group', $user['Group']);
        $this->_stash_acl();
        $this->redirect($this->Auth->redirect());        
    }
    
    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }
    
    public function add($group_name = null) {        
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The User has been saved.'), 'success');
                $this->redirect(array('action' => 'add'));
            } else {
                $this->Session->setFlash(__('The User could not be saved. Please, try again.'), 'fail');
            }
        } else {
            $this->loadModel('Group');
            if (!$group_name) $group_name = Group::ROLE_SU;
            $group = $this->Group->findByName($group_name);
            if ($group) $this->request->data['User']['group_id'] = $group['Group']['id'];
        }
        $this->_load_groups();
    }

    public function signup($group_name = null) {        
        $this->layout = 'splash';
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Thanks for signing up. Please login below.'), 'success');
                $this->redirect(array('action' => 'add'));
            } else {
                $this->Session->setFlash(__('Cannot signup at this time. Please, try again later.'), 'fail');
            }
        }
    }
    
    public function index() {
        $this->set('users', $this->paginate());        
    }
    
    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The User has been saved.'), 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The User could not be saved. Please, try again.'), 'fail');
            }
        } else {            
            $user = $this->User->read(null, $id);
            $this->request->data = $user;
            unset($this->request->data['User']['password']);
        }
//        $this->_load_groups();
    }

    public function delete($id = null) {
        if (!$this->request->is('post'))
            throw new MethodNotAllowedException();
        $this->User->id = $id;
        if (!$this->User->exists())
            throw new NotFoundException(__('Invalid user'));
        if ($this->User->delete()) {
            $this->Session->setFlash(__('The User was deleted.'), 'success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('The User was not deleted.'), 'fail');
        $this->redirect(array('action' => 'index'));
    }
    
}

?>
