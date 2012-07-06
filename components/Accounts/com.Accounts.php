<?php
    /**
    * com.Accounts.php
    * Copyright 2009-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\components;

    /**
    * Account component
    */
    class Accounts extends \forge\Component implements \forge\components\Dashboard\InfoBox {
    	use \forge\Configurable;
    	
        /**
        * Minimum length of a password
        */
        const MinimumPasswordLength = 4;

        /**
        * Cache the permissions fetched out of the database
        * @var arrya
        */
        static protected $permissionCache = array();

        /**
        * Permissions
        * @var array
        */
        static protected $permissions = array(
            'Accounts' => array(
                'admin' => array(
                    'list',
                    'fields',
                    'registration'
                )
            )
        );

        /**
        * Initiate the component
        * @ignore
        * @return void
        */
        static public function init() {
            // If we have a cookie, utilize it.
            if (!is_null($uid = \forge\Memory::cookie('account')) && !is_null($password = \forge\Memory::cookie('password')))
                try {
                    // Get the account in question
                    $account = new \forge\components\Accounts\db\Account($uid);

                    // Get all valid cookies associated with this account
                    $cookies = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
                        'type' => new \forge\components\Accounts\db\Cookie,
                        'where' => array(
                            'account' => $uid,
                            'expire' => array('gt'=>time())
                        )
                    ]));

                    // Loop over the cookies and see if any matches the requested one
                    foreach ($cookies as /** @var \forge\components\Accounts\db\tables\Cookie **/ $cookie)
                        if (md5($account->user_password.$cookie->salt) == $password) {
                            // Trigger an extension of the cookie
                            $cookie->save();
                            \forge\Memory::cookie('account',$uid);
                            \forge\Memory::cookie('password',$password);

                            // If this is the first access of this session, log us in!
                            if (!self::isAuthenticated())
                                self::login($account->getID());
                        }
                }
                catch (\Exception $e) {}
        }

        /**
        * Attempt to login a user into the current session
        * @param string Account / E-mail
        * @param string Password (clear text)
        * @param bool Remember this with a cookie?
        * @return int
        * @throws Exception
        */
        static public function attemptLogin($username, $password, $cookie=false) {
            // Step 1: Developer account?
            $root = self::getConfig('root');
            if ($root['username'] == sha1($username) && $root['password'] == sha1($password)) {
                \forge\Memory::session('USER_DEVELOPER', true);
                return 2;
            }

            // Attempt creating the account (throws exception if it doesn't exist)
            try {
                $account = new Accounts\db\Account();
                $account->user_account = $username;
                $account->select('user_account');
            }
            catch (\Exception $e) {
				throw new \forge\HttpException('Account does not exist', \forge\HttpException::HTTP_FORBIDDEN);
            }

            if ($account->user_state != 'active')
                throw new \forge\HttpException('The user is not activated, and cannot be logged into.', \forge\HttpException::HTTP_FORBIDDEN);

            // Throw exception if it's the wrong password
            if ($account->user_password != $account->hashPassword($password))
                throw new \forge\HttpException('Wrong password given for user '.$username, \forge\HttpException::HTTP_FORBIDDEN);

            // Save us as logged on!
            self::login($account->getId());

            // Do we want to be remembered?
            if ((bool)$cookie === true) {
                $entry = new \forge\components\Accounts\db\Cookie();
                $entry->account = $account->getId();
                $entry->insert();

                \forge\Memory::cookie('account',$account->getId());
                \forge\Memory::cookie('password',md5($account->user_password.$entry->salt));
            }

            return 1;
        }

        /**
        * Set the current user ID
        * @param int ID
        * @return void
        */
        static private function login($uid) {
            \forge\Memory::session('USER_AUTHENTICATION',(int)$uid);
        }

        /**
        * Get infobox
        * @return string
        */
        static public function getInfoBox() {
            if (!self::getPermission(self::getUserId(),'accounts','admin','list','r'))
                return null;

            $accounts = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
                'type' => new \forge\components\Accounts\db\Account,
                'limit' => 1
            ]));

            return \forge\components\Templates::display(
                'components/Accounts/tpl/inc.infobox.php',
                array(
                    'accounts' => $accounts->getPages()
                )
            );
        }

        /**
        * Get the current user ID
        * @return int
        */
        static public function getUserId() {
            return (int)\forge\Memory::session('USER_AUTHENTICATION');
        }

        /**
         * Handle a registration attempt from the client
         * @return void
         */
        static public function handleRegistration() {
            if (empty($_POST['account']))
                throw new \forge\HttpException(_('You must specify an account name'), \forge\HttpException::HTTP_BAD_REQUEST);
            if (empty($_POST['email']))
                throw new \forge\HttpException(_('You must specify an e-mail address'), \forge\HttpException::HTTP_BAD_REQUEST);
            if (!\forge\components\Mailer::isMail($_POST['email']))
                throw new \forge\HttpException(_('You must specify a proper e-mail address'), \forge\HttpException::HTTP_BAD_REQUEST);
            if (empty($_POST['name_first']))
                throw new \forge\HttpException(_('You must specify a first name'), \forge\HttpException::HTTP_BAD_REQUEST);
            if (empty($_POST['name_last']))
                throw new \forge\HttpException(_('You must specify a last name'), \forge\HttpException::HTTP_BAD_REQUEST);
            if (empty($_POST['password']))
                throw new \forge\HttpException(_('You must specify a password'), \forge\HttpException::HTTP_BAD_REQUEST);
            if (empty($_POST['password_confirm']))
                throw new \forge\HttpException(_('You must confirm the password'), \forge\HttpException::HTTP_BAD_REQUEST);

            try {
                self::createAccount(
                    $_POST['account'],
                    $_POST['name_first'],
                    $_POST['name_last'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['password_confirm']
                );
            }
            catch (\Exception $e) {
                switch ($e->getMessage()) {
                    default:
                        throw $e;
                    case 'EMAIL_ALREADY_REGISTERED':
                        throw new \forge\HttpException(_('This e-mail address is already registered with us.'),\forge\HttpException::HTTP_BAD_REQUEST);
                    case 'ACCOUNT_ALREADY_REGISTERED':
                        throw new \forge\HttpException(_('This account name is already registered with us.'),\forge\HttpException::HTTP_BAD_REQUEST);
                    case 'BAD_PASSWORD':
                        throw new \forge\HttpException(_('The requested password was too short.'),\forge\HttpException::HTTP_BAD_REQUEST);
                    case 'BAD_CONFIRM':
                        throw new \forge\HttpException(_('The passwords are not equal'),\forge\HttpException::HTTP_BAD_REQUEST);
                }
            }
        }

        /**
        * Does the current user have the given permission?
        * @param string Domain
        * @param string Category
        * @param string Field
        * @param string Requirement
        * @return bool
        */
        static public function hasPermission($domain,$category,$permission,$requirement) {
            try {
                self::restrict($domain,$category,$permission,$requirement);
                
                return true;
            }
            catch (\Exception $e) {
                return false;
            }
        }
        
        /**
        * Logout the user from the session
        * @return void
        */
        static public function logout() {
            \forge\Memory::session('USER_DEVELOPER',false);
            \forge\Memory::session('USER_AUTHENTICATION',false);
            \forge\Memory::cookie('account',null);
            \forge\Memory::cookie('password',null);
        }

        /**
        * Confirm a user account
        * @param int User ID
        * @param string Confirmation hash
        * @return bool
        * @throws Exception
        */
        static public function confirm($id,$key) {
            $user = new \forge\components\Accounts\Account($id);

            if ($user->account->user_state == 'created' && $key==md5($user->account->user_password.$user->account->getID())) {
                $user->account->user_state = 'active';
                $user->account->save();
                $domains = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
                    'type' => new \forge\components\Websites\db\Website,
                    'where' => array('alias'=>'')
                ]));
                $sites = array();
                foreach ($domains as $i => $site)
                    $sites[$i] = '<a href="http://'.$site->domain.'/">http://'.$site->domain.'/</a>';
                $sites = implode('<br>',$sites);
                $tpl = array(
                    '%account%' => $user->account->user_account,
                    '%name%' => $user->account->user_name_first.' '.$user->account->user_name_last,
                    '%email%' => $user->account->user_email,
                    '%sites%' => $sites
                );
                $mail = new \forge\components\Mailer\Mail();
                $mail->AddAddress($user->account->user_email,$user->account->user_name_first.' '.$user->account->user_name_last);
                $mail->Subject = _('Confirm your new account');
                $mail->Body = str_replace(array_keys($tpl),array_values($tpl),\forge\components\Accounts::config('confirmation'));
                $mail->Send();

                return true;
            }

            return false;
        }

        /**
        * Create a new account
        * @param string Account name
        * @param string First name
        * @param string Last name
        * @param string E-mail address
        * @param string Password
        * @param string Password (confirm)
        */
        static public function createAccount($account,$nameFirst,$nameLast,$email,$password,$passwordConfirm) {
            // Must have arguments.
            if (empty($account) || empty($nameFirst) || empty($nameLast) || empty($email) || empty($password) || empty($passwordConfirm))
                throw new \Exception('EMPTY_ARGUMENTS');

            // E-mail must be valid
            if (!Mailer::isMail($email))
                throw new \Exception('BAD_EMAIL');

            // Password must be OK
            if (strlen($password) < Accounts::MinimumPasswordLength)
                throw new \Exception('BAD_PASSWORD');
            if (md5($password) != md5($passwordConfirm))
                throw new \Exception('BAD_CONFIRM');

            // Create a new row in the database table
            $accountInstance = new \forge\components\Accounts\db\Account();
            $accountInstance->makeSalt();
            $accountInstance->user_account = $account;
            $accountInstance->user_email = $email;
            $accountInstance->user_name_first = $nameFirst;
            $accountInstance->user_name_last = $nameLast;
            $accountInstance->user_password = $accountInstance->hashPassword($password);
            $accountInstance->insert();

            // Mail the stuff to the new user
            $domains = new \forge\components\Databases\TableList(new \forge\components\Databases\Params([
                'type' => new \forge\components\Websites\db\Website,
                'where' => array('alias'=>'')
            ]));
            $sites = array();
            foreach ($domains as $i => $site)
                $sites[$i] = '<a href="http://'.$site->domain.'/">http://'.$site->domain.'/</a>';
            $sites = implode('<br>',$sites);
            $tpl = array(
                '%account%' => $account,
                '%name%' => $nameFirst.' '.$nameLast,
                '%email%' => $email,
                '%password%' => $password,
                '%sites%' => $sites,
                '%link%' => '<a href="'.($url='http://'.$_SERVER['SERVER_NAME'].'/user/confirm?id='.$accountInstance->getID().'&key='.md5($accountInstance->user_password.$accountInstance->getID())).'">'.$url.'</a>'
            );
            $mail = new \forge\components\Mailer\Mail();
            $mail->AddAddress($email,$nameFirst.' '.$nameLast);
            $mail->Subject = _('Account registered');
            $mail->Body = str_replace(array_keys($tpl),array_values($tpl),\forge\components\Accounts::config('registration'));
            $mail->Send();
        }

        /**
        * Restrict the current page to a certain requirement
        * @param string Domain
        * @param string Category
        * @param string Field
        * @param string Requirement
        */
        static public function restrict($domain,$category,$permission,$requirement) {
            // Is this a developer login?
            if (\forge\Memory::session('USER_DEVELOPER'))
                return;

            // If logged in, find out our permissions
            if (\forge\Memory::session('USER_AUTHENTICATION')) {
                if (!self::getPermission(self::getUserId(), $domain, $category, $permission, $requirement))
                    throw new \forge\HttpException('FORBIDDEN', \forge\HttpException::HTTP_FORBIDDEN);

                return;
            }


            // If not logged in, error it!
            throw new \forge\HttpException('AUTHORIZATION_REQUIRED', \forge\HttpException::HTTP_UNAUTHORIZED);
        }

        /**
        * Does the user have a certain permission?
        * @param int Account the query is regards to
        * @param string Domain of the permission
        * @param string Category of the permission
        * @param string Name of the permission
        * @param string Require the following permissions
        * @return bool
        * @throws Exception
        */
        static public function getPermission($id,$domain,$category,$field,$requirement) {
            if (\forge\Memory::session('USER_DEVELOPER'))
                return true;

            if (!isset(self::$permissionCache[$id][$domain][$category][$field]))
                try {
                    $permission = new \forge\components\Accounts\db\Permissions();
                    $permission->user_id = $id;
                    $permission->permission_domain = $domain;
                    $permission->permission_category = $category;
                    $permission->permission_field = $field;
                    $permission->select(array('user_id','permission_domain','permission_category','permission_field'));

                    self::$permissionCache[$id][$domain][$category][$field]['r'] = $permission->permission_read;
                    self::$permissionCache[$id][$domain][$category][$field]['w'] = $permission->permission_write;
                }
                catch (\Exception $e) {
                    self::$permissionCache[$id][$domain][$category][$field]['r'] = false;
                    self::$permissionCache[$id][$domain][$category][$field]['w'] = false;
                }

            $evaluate = true;
            if (strstr($requirement,'r') !== false && self::$permissionCache[$id][$domain][$category][$field]['r']== 0)
                $evaluate = false;
            if (strstr($requirement,'w') !== false && self::$permissionCache[$id][$domain][$category][$field]['w'] == 0)
                $evaluate = false;

            return $evaluate;
        }

        /**
        * Get the user
        * @return \forge\components\Accounts\Account
        * @throws Exception
        */
        static public function getUser($id=null) {
            if (!is_null($id))
                return new \forge\components\Accounts\db\Account($id);

            // Is this a developer login?
            if (\forge\Memory::session('USER_DEVELOPER'))
                return new \forge\components\Accounts\db\Account();

            return new \forge\components\Accounts\db\Account(\forge\Memory::session('USER_AUTHENTICATION'));
        }

        /**
        * Force the client to be logged in through either sessions or cookies
        * @param string Redirect to another URL if not authenticated?
        * @return \forge\components\Accounts\db\tables\AccountEntry
        */
        static public function forceAuthentication($redir=false) {
            if (!self::isAuthenticated())
                \forge\components\SiteMap::redirect($redir ? $redir : '/user/login?from='.urlencode($_SERVER['REQUEST_URI']));

            return new \forge\components\Accounts\db\Account(self::getUserId());
        }

        /**
        * Have the client authenticated?
        * @return bool
        */
        static public function isAuthenticated() {
            return self::getUserId() > 0 || \forge\Memory::session('USER_DEVELOPER');
        }

        /**
        * Get list of set permissions
        * @return array
        */
        static public function getDomains() {
            $domains = array();

            foreach (\forge\Addon::getComponents(true) as $com)
                foreach (call_user_func($com.'::getPermissions') as $domain => $list1)
                    foreach ($list1 as $category => $list2)
                        foreach ($list2 as $field)
                            $domains[$domain][$category][] = $field;

            foreach (\forge\Addon::getModules(true) as $mod)
                foreach (call_user_func($mod.'::getPermissions') as $domain => $list1)
                    foreach ($list1 as $category => $list2)
                        foreach ($list2 as $field)
                            $domains[$domain][$category][] = $field;

            return $domains;
        }

        /**
        * Is the logged in user an administrator?
        * @return bool
        */
        static public function isAdmin() {
            try {
                self::restrict('Admin','administration','access','r');

                return true;
            }
            catch (\Exception $e) {
                return false;
            }
        }
        
        /**
         * Check wether or not the requestee is a developer
         * @return bool
         */
        static public function isDeveloper() {
        	return isset($_COOKIE['developer']) && sha1($_COOKIE['developer']) == self::getConfig('developer');
        }
        
        /**
         * Set a new developer key
         * @param $key string Developer key
         * @return void
         */
        static public function setDeveloperKey($key) {
        	self::setConfig('developer', sha1($key));
        	self::writeConfig();
        }
        
        /**
         * Set root username & password
         * @param $username string Username
         * @param $password string Password
         * @return bool Returns FALSE if root was already set
         */
        static public function setRoot($username, $password) {
        	if (self::getConfig('root', false) !== false)
        		return false;
        	
        	self::setConfig('root', array(
        		'username' => sha1($username),
        		'password' => sha1($password)
        	), true);
        }
    }
