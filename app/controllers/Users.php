<?php
class Users extends Controller
{
    private $userModel;

    public function __construct()
    {
        // Instantiate the user model to enable data grab from database
        $this->userModel = $this->model('User');
    }

    // Main Methods

    // Register method
    public function register()
    {
        if (checkLoggedIn()) {
            redirectURL('pages/error');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->initUserData();
            $data = $this->validateRegistrationData($data);

            if ($this->noErrorsCheck($data)) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                if ($this->userModel->register($data)) {
                    redirectURL('users/login');
                } else {
                    redirectURL('pages/error');
                    exit();
                }
            }
        } else {
            $data = $this->initUserData();
        }

        $this->view('users/register', $data);
    }

    // Login method
    public function login()
    {
        if (checkLoggedIn()) {
            redirectURL('pages/error');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->initLoginData();
            $data = $this->validateLoginData($data);

            if ($this->noLoginErrors($data)) {
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                if ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['login_error'] = 'Invalid email or password';
                    $this->view('users/login', $data);
                }
            } else {
                $this->view('users/login', $data);
            }
        } else {
            $data = $this->initLoginData();
            $this->view('users/login', $data);
        }
    }

    public function appoint($email)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        }
    }

    // Create session method
    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_role'] = $user->role;
        redirectURL('pages/index');
    }

    // Logout method
    public function logout()
    {
        $session_variables = ['user_id', 'user_email', 'user_name', 'user_role'];
        foreach ($session_variables as $var) {
            unset($_SESSION[$var]);
        }
        session_destroy();
        redirectURL('users/login');
    }

    /*
    ** Helper Methods 
    */

    // Helper methods for Registration

    // Initialise the data needed for register
    private function initUserData()
    {
        return [
            'name' => '',
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'name_error' => '',
            'email_error' => '',
            'password_error' => '',
            'confirm_password_error' => '',
        ];
    }

    // Sanitise and validate the registration data
    private function validateRegistrationData($data)
    {
        // Sanitise the POST input
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Trim and store sanitized input
        $data['name'] = trim($_POST['name']);
        $data['email'] = trim($_POST['email']);
        $data['password'] = $_POST['password'];
        $data['confirm_password'] = $_POST['confirm_password'];

        // Validation logic
        // Check if empty name input
        if (empty($data['name'])) {
            $data['name_error'] = 'Please enter a name';
        }

        // Check if empty email input
        if (empty($data['email'])) {
            $data['email_error'] = 'Please enter an email';
        } else {
            // Check if email already exists
            if ($this->userModel->findUserByEmail($data['email'])) {
                $data['email_error'] = 'Email is already registered';
            }
        }

        // Check if empty password input and meets length requirement
        if (empty($data['password'])) {
            $data['password_error'] = 'Please enter a password';
        } elseif (strlen($data['password']) < 6) {
            $data['password_error'] = 'Please enter password with more than six characters';
        }

        // Check if empty confirm password and passwords input match
        if (empty($data['confirm_password'])) {
            $data['confirm_password_error'] = 'Please confirm the password';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $data['confirm_password_error'] = 'Passwords do not match';
        }

        return $data;
    }

    // Check if there are any input errors
    private function noErrorsCheck($data)
    {
        return (empty($data['name_error']) &&
            empty($data['email_error']) &&
            empty($data['password_error']) &&
            empty($data['confirm_password_error']));
    }

    // Helper Methods for Login

    // Initialise the data needed for login
    private function initLoginData()
    {
        return [
            'email' => '',
            'password' => '',
            'login_error' => '',
        ];
    }

    // Sanitise and validate the login data
    private function validateLoginData($data)
    {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $data['email'] = $_POST['email'];
        $data['password'] = $_POST['password'];

        if (empty($data['email']) || empty($data['password'])) {
            $data['login_error'] = 'Please enter both email and password to login';
        } elseif (!$this->userModel->findUserByEmail($data['email'])) {
            $data['login_error'] = 'Invalid email or password';
        }

        return $data;
    }

    // Check if there are any login errors
    private function noLoginErrors($data)
    {
        return empty($data['login_error']);
    }
}
