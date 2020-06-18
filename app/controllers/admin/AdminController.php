<?php
class AdminController extends Controller
{

  private $userModel;

  public function __construct()
  {
    $this->userModel = $this->model('UserModel', 'admin');
    //$this->addJs();
  }

  public function index()
  {
    // Load the dashboard
    $this->view('admin/dashboard');
  }

  public function login()
  {
    // Check if logged in
    if (isLoggedIn()) {
      redirect('admin/index');
    }
    
    // Check if POST
    if (isPost()) {
      // Sanitize POST
      filterPost();

      $data = [
        'username' => trim($_POST['username']),
        'password' => trim($_POST['password']),
        'username_err' => '',
        'password_err' => '',
        'type_err' => '',
      ];

      // Check for username
      if (empty($data['username'])) {
        $data['username_err'] = 'Please enter an username';
      }

      // Check for password
      if (empty($data['password'])) {
        $data['password_err'] = 'Please enter a password';
      }

      // Check for user
      if (!empty($data['username']) && !$this->userModel->getUserByEmail($data['username'])) {
        // No user found
        if (filter_var($data['username'], FILTER_VALIDATE_EMAIL)) {
          $data['username_err'] = 'This email is not registered';
        } else {
          $data['username_err'] = 'This email is not valid';
        }
      }

      // Make sure errors are empty
      if (empty($data['username_err']) && empty($data['password_err'])) {

        // Check and set logged in user
        $loggedInUser = $this->userModel->login($data['username'], $data['password']);

        if ($loggedInUser) {

          if ($loggedInUser->type != UserType::VISITOR ) {
            // User Authenticated!
            createUserSession($loggedInUser);
            redirect('admin/index');
            } else {
            // Incorrect usertype
            flash('login_message', 'You dont have the right authentication', 'alert alert-danger');
            $this->view('admin/login', $data);
            }
          
        } else {
          // Incorrect password. Reload view with message
          $data['password_err'] = 'Password incorrect';
          $this->view('admin/login', $data);
        }
      } else {
        // Incorrect username. Reload view
        $this->view('admin/login', $data);
      }
    } else {
      // GET request
      $data = [
        'username' => '',
        'password' => '',
        'username_err' => '',
        'password_err' => '',
        'type_err' => '',
      ];

      // Load View
      $this->view('admin/login', $data);
    }
  }

  public function logout()
  {
    removeUserSession();
    redirect('admin/login');
  }

}


