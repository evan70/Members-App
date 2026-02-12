<?php
class Login_model extends Model {

    public function login_check($submitted_username, $submitted_password) {

        $error_msg = 'You did not submit a correct username/email and/or password.';

        $params = [
            'username' => $submitted_username,
            'email_address' => $submitted_username
        ];

        $sql = 'SELECT * FROM members WHERE username = :username OR email_address = :email_address';
        $rows = $this->db->query_bind($sql, $params, 'object');

        if (empty($rows)) {
            return $error_msg;
        }

        $stored_password = $rows[0]->password;
        $password_valid = password_verify($submitted_password, $stored_password);
        if ($password_valid === false) {
            return $error_msg;
        }

        return true;

    }

}