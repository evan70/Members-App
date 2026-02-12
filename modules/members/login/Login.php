<?php
class Login extends Trongate {

    public function index() {
        $this->view('login');
    }

    public function submit_login() {
        $this->validation->set_rules('username', 'username/email', 'required|callback_login_check');
        $this->validation->set_rules('password', 'password', 'required');

        $result = $this->validation->run();

        if ($result === true) {
            echo 'Well done - log user in (later!)';
        } else {
            $this->index();
        }
    }

    public function login_check($username) {
        $password = post('password');
        $login_result = $this->model->login_check($username, $password);
        return $login_result; // Will be either true (bool) or an error msg (string)
    }

}