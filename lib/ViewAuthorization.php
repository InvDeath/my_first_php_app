<?php
class ViewAuthorization extends View
{
    protected function processGenMainContent()
    {
        return $this->genData['main_content'];
    }

    protected function processGenMsgLogin()
    {
        return $this->genData['messages'];
    }

}
?>
