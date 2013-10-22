<?php

class ViewMain extends View
{
    protected function processGenMainContent()
    {
        if ( is_array($this->genData['main_content']))
        {
            return $this->genData['main_content'];
        }
        else
        {
            return $this->genData['main_content'];
        }
 
    }
    
    protected function processGenMsgLogin()
    {
        return $this->genData['messages'];
    }

}


?>
