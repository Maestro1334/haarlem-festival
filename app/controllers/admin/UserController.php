<?php
class UserController extends Controller 
{

  private $userModel;

  public function __construct()
  {
    $this->userModel = $this->model('UserModel', 'admin');
    
  }

  public function index()
  {
    // initialize datatable
    $this->addJs('datatables/datatables.min.js');
    $this->addCSS('datatables/datatables.min.css');

    // custom js
    $this->addJs('admin/user.js');

    $users = $this->userModel->getAllUsers();
    $data = [
      'users' => $users
    ];
    $this->view('admin/users', $data);
  }

  public function delete($user_id)
  {
    // delete user
    if ($this->userModel->deleteUser($user_id)) {
        flash('user_message', 'User Removed');
        redirect('admin/user');
    } else {
        die('Something went wrong');
    }
    
  }

  public function add()
  {
    if(isPost()){
      
        filterPost();
  
        $data = [
          'email' =>trim($_POST['email']),
          'type' =>trim($_POST['type']),
          'password' => $this->generatePassword(),
          'email_err' => '',
          'type_err' => ''
        ];

        // Validate email
        validateEmail($data['email'], $data['email_err'], true, 'admin');
        
        // check for type
        if (empty($data['type'])) {
          $data['type_err'] = 'Please enter a type';
        }

        
  
        // check if the errors are empty
        if (empty($data['email_err']) && empty($data['type_err'])) {

          $data['hashed_password'] = password_hash($data['password'], PASSWORD_DEFAULT);

          // add user
          if($this->userModel->addUser($data)){
            try {
              sendAccountDetails($data);
              flash('user_message', 'User has been added and notified');
            } catch (\Throwable $th) {
              //throw $th;
            }
            flash('user_message', 'User Added');
            redirect('admin/user');
          } else {
            die('something went wrong');
          }
        } else {
          // load view with errors
          $this->view('admin/add_user', $data);
        }
  
      } else {
  
        $data = [
          'email' => '',
          'type' => ''
        ];
  
        $this->view('admin/add_user', $data);
      }
  }

  public function edit($user_id)
  {
    if(isPost()){

      filterPost();

      $data = [
        'id' => $user_id,
        'email' => trim($_POST['email']),
        'type' => trim($_POST['type']),
        'email_err' => '',
        'type_err' => ''
      ];

      // check for email
      if (empty($data['email'])) {
        $data['email_err'] = 'Please enter an email';
      }
      // check for type
      if (empty($data['type'])) {
        $data['type_err'] = 'Please enter a type';
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

      // check if the errors are empty
      if (empty($data['email_err']) && empty($data['type_err'])) {
        // update user
        if($this->userModel->updateUser($data)){
          flash('user_message', 'User Updated');
          redirect('admin/user/index');
        } else {
          die('something went wrong');
        }
      } else {
        // load view with errors
        $this->view('admin/edit_user', $data);
      }

    } else {
      $user = $this->userModel->getUserById($user_id);

      $data = [
        'id' => $user_id,
        'email' => $user->email,
        'type' => $user->type,
        'email_err' => '',
        'type_err' => ''
      ];

      $this->view('admin/edit_user', $data);
    }
  }

  private function generatePassword()
  {
      // https://gist.github.com/tylerhall/521810
    // Use every character
    $passChars = array();
    $passChars[] = '@[A-Z]@' . '@[a-z]@' . '@[0-9]@' . '@[^\w]@';

    $all = '';
    $password = '';

    foreach ($passChars as $char) {
        $password .= $char[array_rand(str_split($char))];
        $all .= $char;
    }

    $all = str_split($all);

    for ($i=0; $i < 8 - count($passChars); $i++) { 
        $password .= $all[array_rand($all)];
    }

    $password = str_shuffle($password);

    return $password;
  }
}

?>
