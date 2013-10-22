<?php
class ModelEmplistAdmin extends Model
{
    protected function askMainContent()
    {
        return $this->pullEmployees();
    }

    protected function addEmployee()
    {
        if (empty($this->POST))
        {
            $this->viewMethod = 'showAddForm';
            return FALSE;
        }

        if ($name = $this->validateName($this->POST['name']))
        {
            $okName = $name;
        }
        else
        {
            $this->viewMethod = 'showAddForm';
            $this->messages = '{%LNG_WRONG_NAME%}';
            return FALSE;
        }

        if($email = $this->validateEmail($this->POST['email']))
        {
            $okEmail = $email;
        }
        else
        {
            $this->viewMethod = 'showAddForm';
            $this->messages = '{%LNG_WRONG_EMAIL%}';

            return FALSE;
        }
        
        if ($this->pushEmployee($okName, $okEmail))
        {
            $this->messages = '{%LNG_EMPLOYEE_ADDED%}';
          
            return $this->pullEmployees();  
        }


    }

    protected function removeEmployee()
    {
        if(empty($this->POST))
        {
            $this->viewMethod = 'confirmRemoving';
            return FALSE;
        }
        
        if($this->POST['confirm'] == 'Yes')
        {
            $this->throwEmployee($this->GET['id']); 
            return $this->pullEmployees();        
        }
        else
        {
            return $this->pullEmployees();
        }
    }

    protected function editEmployee()
    {
        if(empty($this->POST))
        {
            $this->viewMethod = 'showEditForm';

            return $this->pullEmpById($this->GET['id']);
        }


        if ($name = $this->validateName($this->POST['name']))
        {
            $okName = $name;
        }
        else
        {
            $this->viewMethod = 'showEditForm';
            $this->messages = '{%LNG_WRONG_NAME%}';
            
            return $this->pullEmpById($this->GET['id']);
        }

        if($email = $this->validateEmail($this->POST['email']))
        {
            $okEmail = $email;
        }
        else
        {
            $this->viewMethod = 'showEditForm';
            $this->messages = '{%LNG_WRONG_EMAIL%}';

            return $this->pullEmpById($this->GET['id']);
        }
        
        if ($this->turnEmployee($this->GET['id'], $okName, $okEmail))
        {
            $this->messages = '{%LNG_EMPLOYEE_ADDED%}';
          
            return $this->pullEmployees();  
        }
        
    }

    private function validateName($name)
    {
        if (empty($name))
        {
            return FALSE;
        }

        if(is_numeric($name))
        {
            return FALSE;
        }

        return trim($name);
    }

    private function validateEmail($email)
    {
        $email = trim($email);
        if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/",$email)){
            //list($username,$domain)= explode('@',$email);
            //if(!checkdnsrr($domain,'MX')) {
            //    $this->wasValid = FALSE;
            //    return MSG_INVALID;
            //}
            return FALSE;
        }
        
        if($this->pullEmpByEmail($email, $this->GET['id']))
        {
            return FALSE;
        }
        
        return $email;
    }

    

}
?>
