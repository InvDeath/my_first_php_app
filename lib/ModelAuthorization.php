<?php

class ModelAuthorization extends Model
{
    protected function askMainContent()
    {
        return '{%LNG_404%}';
    }

    protected function askLoginForm()
    {
        return '{%TPL_LOGINFORM%}';
    }

    protected function askLoginRes()
    {
        $this->valid = FALSE;

        $POST = $this->POST;

        if ((isset($POST['username']))||(isset($POST['password'])))
        {
            $this->validLogin($POST['username']);
            $this->validPassword($POST['password']);

            if(!$this->valid)
            {
                $this->messages = '{%LNG_ERR_BADLOGIN%}';
                return $this->askLoginForm();
            }

            $pass = md5($POST['password']);
            $name = trim($POST['username']);

            $arrUser = $this->pullUserByName($name);

            $userId = $arrUser['id'];
            $userPassword = $arrUser['password'];
            if($pass != $userPassword)
            {
                $this->messages = '{%LNG_ERR_BADPASSWORD%}';
                return $this->askLoginForm();
            }

            setcookie('user',"$userId");
            setcookie('group','admin');  // will be changed on group_by_user.

            $this->viewMethod = 'loginOk';

            return TRUE;
        }
        else
        {
            $this->messages = '{%LNG_ERR_BADLOGIN%}';
            return $this->askLoginForm();
        }
 
    }


    protected function validLogin($login)
    {
        $valid = preg_match('/^[A-Za-z0-9_\-]{4,10}/', $login);
        $this->valid = $valid;


        return $valid;
    }

    protected function validPassword($pass)
    {

        $valid = preg_match("/^[A-Za-z0-9_\-]{5,10}/", $pass);
        $this->valid = $valid;


        return $valid;
    }

}
?>
