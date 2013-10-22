<?php
/**
 * Controller 
 * 
 * After getting generated GET array with routs and actions, 
 * constructor of this class makes instans of 'model' and 
 * 'view'.
 *
 */

class Controller
{
 
    /**
     * GET 
     * 
     * @var array
     */
    private $GET = array();
    
    /**
     * POST 
     * 
     * @var array
     */
    private $POST = array();
    
    /**
     * view 
     * 
     * @var instans
     */
    private $view;
    
    /**
     * model 
     * 
     * @var instans
     */
    private $model;

    /**
     * __construct 
     * 
     * @return void
     */
    function __construct()
    {
        if(!isset($_SESSION['room']))
        {
            $_SESSION['room'] = '1';
        }
        
        $this->GET = $this->createGet($_GET);
        $this->POST = $this->validatePost();
        $this->model = $this->makeModel($this->GET['page']);
        $this->view = $this->makeView($this->GET['page']);
    }
    
    /**
     * printPage 
     * 
     * Returns data for rendering in view.
     *
     * @return void
     */
    public function printPage()
    {
        return $this->view->getData($this->model->getData($this->GET, $this->POST));
    }

    /**
     * createGet 
     * 
     * Returns array with type of view/model and actions.
     *
     * @param array $get 
     * @return array
     */
    private function createGet($get)
    {
        foreach($get as $key => $value)
        {
            $trimedGet[$key] = trim($value);
        }

        if ( !empty($get['page']) )
        {
            $retGet['page'] = $trimedGet['page'];
        }
        else
        {
            $retGet['page'] = 'main';
        }
           
        if ( !empty($get['action']) )
        {
            $retGet['action'] = $trimedGet['action'];
        }
        else
        {
            $retGet['action'] = 'askMainContent';
        }

        if ( !empty($get['id']) )
        {
            $retGet['id'] = $trimedGet['id'];
        }
        else
        {
            $retGet['id'] = 0;
        }

        return $retGet;
 
    }

    /**
     * vlidatePost 
     * 
     * @param array $post 
     * @return array
     */
    private function validatePost()
    {
        $post = $_POST;
        return $post; // under construction...
    }
      
    /**
     * makeModel 
     * 
     * Returns instance of Model class. 
     * Depends of types user and 'page' with GET.
     *
     * @param string $page 
     * @return object
     */
    private function makeModel($page)
    {
        if (isset($_COOKIE['group']))
        {
            $class = 'Model'.ucwords($page).ucwords($_COOKIE['group']);
        }
        else
        {
            $class = 'Model'.ucwords($page);
        }
        
        $inst = new $class($this->GET, $this->POST);

        return $inst;
    }

    /**
     * makeView 
     * 
     * Returns instance of View class. 
     * Depends of types user and 'page' with GET.
     *
     * @param string $page 
     * @return object
     */
    private function makeView($page)
    {
        if (isset($_COOKIE['group']))
        {
            $class = 'View'.ucwords($page).ucwords($_COOKIE['group']);
        }
        else
        {
            $class = 'View'.ucwords($page);
        }
        
        $inst = new $class();

        return $inst;
    }

}

?>
