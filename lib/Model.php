<?php

/**
 * Model 
 * 
 * Main model class for extention.
 *
 * @uses MysqlModel
 */

class Model extends MysqlModel
{
    protected $GET = array();
    protected $POST = array();
    protected $messages = '';
    protected $viewMethod;

    function __construct($get, $post)
    {
        parent::__construct();
        
        $this->GET = $get;
        $this->POST = $post;
        $this->viewMethod = 'askMainContent';
    }

    /**
     * getData 
     * 
     * Returns array with all data for genereting static page.
     *
     * @return array
     */
    public function getData()
    {
        if (method_exists($this, $this->GET['action']))
        {
            $return['main_content'] = $this->{$this->GET['action']}();
            $return['messages'] = $this->getMessages();
            $return['view_method'] = $this->getViewMethod();
        }
        else
        {
            $return['main_content'] = '{%LNG_404%}';
            $return['view_method'] = 'notFound';
        }
        return $return;
    }

    protected function getMessages()
    {
        return $this->messages;
    }

    protected function getViewMethod()
    {
        return $this->viewMethod;
    }

}
?>
