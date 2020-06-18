<?php
/* 
   *  APP CORE CLASS
   *  Creates URL & Loads Core Controller
   *  URL Format - /controller/method/param1/param2
   *  Admin  URL Format - /admin/controller/method/param1/param2
   */
class Core
{
  // Set Defaults
  protected $currentController = 'PageController'; // Default controller
  protected $currentMethod = 'index'; // Default method
  protected $params = []; // Set initial empty params array

  public function __construct()
  {
    $url = $this->getUrl();

    // Check if admin is needed
    if ($url[0] == 'admin') {
      // Going to admin site. Remove admin from url
      unset($url[0]);

      if(!isset($_SESSION['user_id'])){
        // Sending the user to the login page
        if($url[1] != 'login')
        {
          redirect('admin/login');
        }
        $this->currentController = 'AdminController';
        require_once('../app/controllers/admin/' . $this->currentController . '.php');
        
        $this->currentController = new $this->currentController;
        $this->currentMethod = 'login';
      } else {
        // Check if user is Admin or Author. If not, redirect to normal home page
        
        if(canVisitDashboard(getUserId())){
          $this->currentController = 'AdminController';
    
          if (isset($url[1])) {
            // Look in admin controllers folder for controller
            if (file_exists('../app/controllers/admin/' .ucwords($url[1]) . 'Controller.php')) {
              // If exists, set as controller
              $this->currentController = ucwords($url[1]). 'Controller';
              unset($url[1]);
            }
          }
    
          require_once('../app/controllers/admin/' . $this->currentController . '.php');
    
          $this->currentController = new $this->currentController;
    
          // Check if a method is being called
          if (isset($url[2])) {
            if (method_exists($this->currentController, $url[2])) {
              // Set current method
              $this->currentMethod = $url[2];
              unset($url[2]);
            }
          }
        } else {
          // User is not allowed to see the admin page
          redirect('page/index');
        }
      }
    } else {
      // Look in controllers folder for controller
      if (file_exists('../app/controllers/' . ucwords($url[0]) . 'Controller.php')) {
        // If exists, set as controller
        $this->currentController = ucwords($url[0]) . 'Controller';
        // Unset 0 index
        unset($url[0]);
      }

      // Require the current controller
      require_once('../app/controllers/' . $this->currentController . '.php');

      // Instantiate the current controller
      $this->currentController = new $this->currentController;

      // Check if second part of url is set (method)
      if (isset($url[1])) {
        // Check if method/function exists in current controller class
        if (method_exists($this->currentController, $url[1])) {
          // Set current method if it exsists
          $this->currentMethod = $url[1];
          // Unset 1 index
          unset($url[1]);
        }
      }
    }

    // Get params - Any values left over in url are params
    $this->params = $url ? array_values($url) : [];

    // Call a callback with an array of parameters
    call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
  }

  // Construct URL From $_GET['url']
  public function getUrl()
  {
    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);
      return $url;
    }
  }
}
