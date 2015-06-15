<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
//	public function authenticate()
//	{
//		$users=array(
//			// username => password
//			'demo'=>'demo',
//			'admin'=>'admin',
//		);
//		if(!isset($users[$this->username]))
//			$this->errorCode=self::ERROR_USERNAME_INVALID;
//		elseif($users[$this->username]!==$this->password)
//			$this->errorCode=self::ERROR_PASSWORD_INVALID;
//		else
//			$this->errorCode=self::ERROR_NONE;
//		return !$this->errorCode;
//	}
    private $_id;

    public function authenticate() {
        $record = User::model()->find(array('condition' => 'username="' . $this->username . '"'));  // check user name from database
        if ($record === null) {
            $this->_id = 'user Null';
            $this->errorCode = "Your user name does not found in our database";
        } else if ($record->enabled == 0) {                //  check status as Active in db
            $this->errorCode = "Your account has not activated";
        } else if ($record->password !== sha1($this->password)) {            // compare db password with passwod field
            $this->_id = $this->id;
            $this->errorCode = "Your password are invalid";
        } else {
            if (isset($record->Roles->name)) {
                $sRolesName = $record->Roles->name;
            } else {
                if ($record->roles_id == -1) {
                    $sRolesName = 'Super User';
                } else {
                    $sRolesName = '- (Undefined Roles)';
                }
            }

            $this->_id = $record['id'];
            $this->setState('code', $record->code);
            $this->setState('name', $record->name);
            $this->setState('phone', $record->phone);
            $this->setState('email', $record->email);
            $this->setState('city', $record->city_id);
            $this->setState('address', $record->address);
            $this->setState('roles_id', $record->roles_id);
            $this->setState('departement_id',(isset($record->departement_id)) ? $record->departement_id : '');
            $this->setState('roles_name', $sRolesName);
            $this->setState('saldo', (isset($record->saldo)) ? $record->saldo : 0);
            $this->setState('avatar_img', landa()->urlImg('avatar/', $record->avatar_img, $this->_id));

            //save the auth session
            if ($record->roles_id == -1) {
                $this->setState('isSuperUser', 1);
                $this->setState('roles', array());
            } else {
                $this->setState('isSuperUser', 0);
                $mRolesAuth = RolesAuth::model()->findAll(array('condition' => 'roles_id=' . $record->roles_id, 'select' => 'id,crud,auth_id', 'index' => 'auth_id'));
                $this->setState('roles', $mRolesAuth);
            }

            //set app session
            $siteConfig = SiteConfig::model()->findByPk(1);
            if (isset($siteConfig->date_system))
                app()->session['date_system'] = $siteConfig->date_system;

            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId() {       //  override Id
        return $this->_id;
    }

}
