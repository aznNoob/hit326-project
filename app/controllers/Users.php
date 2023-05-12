<?php
class Users extends Controller
{
    private $userModel;

    public function __construct()
    {
        // Instantiate the user model to enable data grab from database
        $this->userModel = $this->model('User');
    }

    // Methods
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->initUserData();
            $data = $this->validateRegistrationData($data);

            if ($this->noErrorsCheck($data)) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                if ($this->userModel->register($data)) {
                    redirectURL('pages/index');
                } else {
                    die('An error has occurred.');
                }
            }
        } else {
            $data = $this->initUserData();
        }

        $this->view('users/register', $data);
    }


    // Helper Methods

    private function initUserData()
    {
        // Initialise the data needed for view
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


    private function validateRegistrationData($data)
    {
        // Sanitise the POST input
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Trim and store sanitized input
        $data['name'] = trim($_POST['name']);
        $data['email'] = trim($_POST['email']);
        $data['password'] = trim($_POST['password']);
        $data['confirm_password'] = trim($_POST['confirm_password']);

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

    private function noErrorsCheck($data)
    {
        // Check if there are any input errors
        return (empty($data['name_error']) &&
            empty($data['email_error']) &&
            empty($data['password_error']) &&
            empty($data['confirm_password_error']));
    }
}
