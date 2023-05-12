<?php
class Users extends Controller
{

    public function __construct()
    {
    }

    // Methods

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->initUserData();
            $data = $this->validateRegistrationData($data);

            if ($this->noErrors($data)) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                redirectURL('pages/index');
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
        // Validation logic
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
