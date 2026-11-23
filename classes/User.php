<?php
class User extends BaseModel
{
    public function getAll()
    {
        return $this->db->query("SELECT id_user, username FROM users")->fetchAll();
    }

    // Mengembalikan data user jika kredensial benar, selain itu false
    public function login($username, $password)
    {
        $st = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $st->execute([$username]);
        $user = $st->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
