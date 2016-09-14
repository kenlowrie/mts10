<?php

class cUser {
    protected $userName;
    protected $userType;
    private $password;
	
    public function __construct($userName, $userType, $password){
        $this->userName = $userName;
        $this->userType = $userType;
        $this->password = $password;
    }
    
	public function getUserName(){
		return $this->userName;
	}
	
	public function isAdmin(){
	    return $this->userType == 0;
	}
	
	public function isEditor(){
	    return $this->userType == 1;
	}
	
	public function isGuest(){
	    return $this->userType == 2;
	}
	
	public function checkPassword($password){
		return $this->password == $password;
	}
}

class cUserList{
    protected $userlist;
    private $curUser;
    
    public function __construct(){
        $this->userlist = Array();
        $this->curUser = -1;
    }
    
    public function addUser($un,$type,$pw){
        $this->userlist[] = new cUser($un,$type,$pw);
    }
    
    public function validUser($un){
        for( $X = 0; $X < count($this->userlist); ++$X ){
            if( $this->userlist[$X]->getUserName() == $un ){
                $this->curUser = $X;
                return True;
            }
        }
        return False;
    }
    public function validPassword($pw){
        if( $this->curUser == -1 ) return False;

        return $this->userlist[$this->curUser]->checkPassword($pw);
    }
    public function isAdmin(){
        if( $this->curUser == -1 ) return False;

        return $this->userlist[$this->curUser]->isAdmin();
    }
    public function isGuest(){
        if( $this->curUser == -1 ) return False;

        return $this->userlist[$this->curUser]->isGuest();
    }
}

?>
