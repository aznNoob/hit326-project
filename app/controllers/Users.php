<?php
class Users extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    // Methods

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->initUserData();
            $data = $this->validateRegistrationData($data);

            if ($this->noErrors($data)) {
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
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // Trim and store sanitized input
        $data['name'] = trim($_POST['name']);
        $data['email'] = trim($_POST['email']);
        $data['password'] = trim($_POST['password']);
        $data['confirm_password'] = trim($_POST['confirm_password']);
        // Validation logic
        if (empty($data['name'])) {
            $data['name_error'] = 'Please enter a name';
        }

        if (empty($data['email'])) {
            $data['email_error'] = 'Please enter an email';
        } else {
            // Check if email already exists
            if ($this->userModel->findUserByEmail($data['email'])) {
                $data['email_error'] = 'Email is already registered';
            }
        }

        if (empty($data['password'])) {
            $data['password_error'] = 'Please enter a password';
        } elseif (strlen($data['password']) < 6) {
            $data['password_error'] = 'Please enter password with more than six characters';
        }

        if (empty($data['confirm_password'])) {
            $data['confirm_password_error'] = 'Please confirm the password';
        } elseif ($data['password'] !== $data['confirm_password']) {
            $data['confirm_password_error'] = 'Passwords do not match';
        }

        return $data;
    }

    private function noErrors($data)
    {
        return (empty($data['name_error']) &&
            empty($data['email_error']) &&
            empty($data['password_error']) &&
            empty($data['confirm_password_error']));
    }
}
