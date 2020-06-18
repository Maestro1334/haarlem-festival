<?php

class UserController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('UserModel');

        // Loads user pages JavaScript & CSS
        $this->addCSS('user-cards.css');
        $this->addCSS('user/user.css');
        $this->addJs('user/user.js');
    }

    public function register()
    {
        // Check if logged in
        if (isLoggedIn()) {
            redirect('page/index');
        }

        // Check if POST
        if (isPost()) {
            // Sanitize POST
            filterPost();

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'location' => trim($_POST['location'])
            ];

            // Validate email
            validateEmail($data['email'], $data['email_err']);

            // Validate password
            validatePasswordInputs($data['password'], $data['confirm_password'], $data['password_err'], $data['confirm_password_err']);

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register new user as a Visitor
                if ($this->userModel->register($data)) {
                    // Redirect to login
                    flash('login_message', 'You are now registered and can log in');

                    $dataLogin = [
                        'username' => trim($_POST['email']),
                        'password' => '',
                        'username_err' => '',
                        'password_err' => '',
                    ];

                    if ($data['location'] == 'checkout') {
                        require_once('../app/controllers/CheckoutController.php');
                        $checkoutController = new CheckoutController();
                        $checkoutController->login($dataLogin);
                    } else {
                        $this->view('user/login', $dataLogin);
                    }
                } else {
                    die('Something went wrong');
                }
            } else {
                // There are errors. Reload the view
                if ($data['location'] == 'checkout') {
                    require_once('../app/controllers/CheckoutController.php');
                    $checkoutController = new CheckoutController();
                    $checkoutController->register($data);
                } else {
                    $this->view('user/register', $data);
                }
            }
        } else {
            // IF NOT A POST REQUEST

            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load View
            $this->view('user/register', $data);
        }
    }

    public function login()
    {
        // Check if logged in
        if (isLoggedIn()) {
            redirect('page/index');
        }

        // Check if POST
        if (isPost()) {
            // Sanitize POST
            filterPost();

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
                'location' => trim($_POST['location'])
            ];

            // Check for email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email address';
            }

            // Check for password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            }

            // Check for user
            if (!$this->userModel->getUserByEmail($data['email'])) {
                // No user found
                if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['email_err'] = 'This email is not registered';
                } else {
                    $data['email_err'] = 'This is not a valid email';
                }
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {

                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    // User Authenticated!
                    createUserSession($loggedInUser);
                    if ($data['location'] == 'checkout') {
                        redirect('checkout/payment');
                    } else {
                        redirect('page/index');
                    }
                } else {
                    // Incorrect password. Reload view with message
                    $data['password_err'] = 'Password incorrect';
                    if ($data['location'] == 'checkout') {
                        require_once('../app/controllers/CheckoutController.php');
                        $checkoutController = new CheckoutController();
                        $checkoutController->login($data);
                    } else {
                        $this->view('user/login', $data);
                    }
                }
            } else {
                // Incorrect username. Reload view
                if ($data['location'] == 'checkout') {
                    require_once('../app/controllers/CheckoutController.php');
                    $checkoutController = new CheckoutController();
                    $checkoutController->login($data);
                } else {
                    $this->view('user/login', $data);
                }
            }
        } else {
            // If NOT a POST

            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];

            // Load View
            $this->view('user/login', $data);
        }
    }

    // Logout & Destroy Session
    public function logout()
    {
        removeUserSession();
        redirect('user/login');
    }

    public function forgot_password()
    {
        $data = ['email' => '', 'email_err' => ''];
        if (isPost()) {
            // Post request
            filterPost();
            // Validate email
            $email = $_POST['email'];
            validateEmail($email, $data['email_err'], false);

            if (empty($data['email_err'])) {
                // Get the user id
                $userId = $this->userModel->getUserIdByEmail($email);
                if ($userId) {
                    // Generating token
                    $token = bin2hex(random_bytes(10));
                    try {
                        // Store token to database
                        $this->userModel->createPasswordReset($userId, $token);
                        // Send mail using mail helper
                        try {
                            sendPasswordResetMail($email, $token);
                            flash('forgot_password_message', 'An email to reset your password has been send');
                        } catch (\Throwable $th) {
                            flash('forgot_password_message', $th, 'alert alert-danger');
                        }
                        $this->view('user/forgot_password', $data);
                    } catch (\Throwable $th) {
                        //throw $th;
                        flash('forgot_password_message', $th->getMessage(), 'alert alert-danger');
                    }
                }
            }
            $data['email'] = $email;
        }
        // Get request
        $this->view('user/forgot_password', $data);
    }

    public function reset_password($token = null)
    {
        $data = ['password' => '', 'password_err' => '', 'confirm_password' => '', 'confirm_password_err' => '', 'token' => $token, 'token_err' => ''];
        if (isPost()) {
            filterPost();
            $data['password'] = $_POST['password'];
            $data['confirm_password'] = $_POST['confirm_password'];
            $data['token'] = $_POST['token'];
            validatePasswordInputs($data['password'], $data['confirm_password'], $data['password_err'], $data['confirm_password_err']);

            if (empty($data['password_err']) && empty($data['confirm_password_err'])) {
                $userId = $this->userModel->getTokenUserId($data['token']);
                print_r($userId);
                if ($userId) {
                    // There is a user which requested a password reset with this token
                    try {
                        $this->userModel->updatePasswordFromReset($data['password'], $data['token']);
                        flash('login_message', 'The password has been saved. You can now log in');
                        redirect('user/login');
                    } catch (\Throwable $th) {
                        flash('token_message', 'Something went wrong. Please try again', 'alert alert-danger');
                    }
                } else {
                    // No user returned. Token is invalid. Redirect the user
                    flash('forgot_password_message', 'The token is invalid. Please fill this form again', 'alert alert-danger');
                    redirect('user/forgot_password');
                }
            }
            $this->view('user/reset_password', $data);
        }
        // Get request
        if (!$token) {
            $data['token_err'] = 'There is no token given. Please retry the link in your mail';
            flash('token_message', $data['token_err'], 'alert alert-danger');
        }
        $this->view('user/reset_password', $data);
    }

    public function account() {
        if (!isLoggedIn()) {
          redirect('user/login');
        }

        // Get the account page data from the database and send them to the view page to be displayed
        $data = [
          'invoice' => $this->userModel->getUserOrders(),
        ];

        // Loads user CSS into the view
        $this->addCSS('user/user.css');

        // Loads user JavaScript onto page
        $this->addJs('user/user.js');

        // Require 'account' view php file and give it the $data array with the account contents
        $this->view('user/account', $data);
    }
}