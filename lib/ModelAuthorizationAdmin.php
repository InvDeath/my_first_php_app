<?php
class ModelAuthorizationAdmin extends Model
{
    protected function logout()
    {
        setcookie('user','');
        setcookie('group','');
        return FALSE;
    }
}
?>
