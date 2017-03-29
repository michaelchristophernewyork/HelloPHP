<?php
namespace model;
class User
{
    private $id;
    private $username;
    private $display_name;
    private $password;
    private $email;
    private $last_login;
    private $is_active;
    private $is_administrator;
    private $is_reporter;
    private $is_banned;

    function __construct()
    {
    }

    public static function fetch_by_username($username)
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT `username`, `password`, `email`, `lastlogin`, `active` FROM `users` WHERE `username` = :username');
        $req->execute(array('username' => $username));
        $user = $req->fetch();
        return User::create()->set_username($user['username'])->set_password($user['password'])->set_email($user['email'])->set_last_login($user['lastlogin'])->set_is_active($user['active']);
    }

    private function set_is_active($input)
    {
        $this->active = $input;
        return $this;
    }

    private function set_last_login($input)
    {
        $this->lastlogin = $input;
        return $this;
    }

    private function set_email($input)
    {
        $this->email = $input;
        return $this;
    }

    private function set_password($input)
    {
        $this->password = $input;
        return $this;
    }

    private function set_username($input)
    {
        $this->username = $input;
        return $this;
    }

    private function set_display_name($input)
    {
        if ($input != null)
        {
            $this->display_name = $input;
        } else {
            $this->display_name = $this->get_username();
        }
        return $this;
    }

    public static function create()
    {
        $instance = new self();
        return $instance;
    }

    public static function fetch_all()
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT `username`, `password`, `email`, `lastlogin`, `active` FROM `users`');
        $req->execute();
        $users = array();
        foreach ($req->fetchAll() as $user) {
            array_push($users, User::create()->set_username($user['username'])->set_password($user['password'])->set_email($user['email'])->set_lastlogin($user['lastlogin'])->set_active($user['active']));
        }
        return $users;
    }

    public static function all()
    {
        $list = [];
        $db = Db::getInstance();
        $req = $db->query('SELECT `username`, `password`, `email`, `lastlogin`, `active` FROM `users`');
        foreach ($req->fetchAll() as $user) {
            $list[] = User::create()->set_username($user['username'])->set_password($user['password'])->set_email($user['email'])->set_lastlogin($user['lastlogin'])->set_active($user['active']);
        }
        return $list;
    }

    public static function remove_user($username)
    {
        $db = Db::getInstance();
        $req = $db->prepare('DELETE FROM `users` WHERE `username` = :username');
        $req->bindParam(':username', $username, \PDO::PARAM_STR, 255);
        $req->execute();
    }

    public function get_email()
    {
        return $this->email;
    }

    public function get_display_name()
    {
        return $this->display_name;
    }

    public function get_username()
    {
        return $this->username;
    }

    function fetch_by_email($email)
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT `username`, `password`, `email`, `lastlogin`, `active` FROM `users` WHERE `email` = :email LIMIT 1');
        $req->execute(array('email' => $email));
        $user = $req->fetch();
        return User::create()->set_username($user['username'])->set_password($user['password'])->set_email($user['email'])->set_lastlogin($user['lastlogin'])->set_active($user['active']);
    }

    public function add_user($username, $password, $email, $lastlogin, $active)
    {
        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO `users`(`username`, `password`, `email`, `lastlogin`, `active`) VALUES (:username, :password, :email, :lastlogin, :active)');
        $req->bindParam(':username', $username, \PDO::PARAM_STR, 255);
        $req->bindParam(':password', $password, \PDO::PARAM_STR, 255);
        $req->bindParam(':email', $email, \PDO::PARAM_STR, 255);
        $req->bindParam(':lastlogin', $lastlogin, \PDO::PARAM_STR, 255);
        $req->bindParam(':active', $active, \PDO::PARAM_STR, 255);
        $req->execute();
    }

    public function add_user_ldap($username)
    {
        $this->set_username($username);
        $this->set_email($this->ReturnEmailAddress($username, \model\Setting::fetch_all()));
        $this->set_displayname($this->ReturnDisplayName($username, \model\Setting::fetch_all()));
        return $this;
    }

    function ReturnEmailAddress($input_username, $settings)
    {
        return $this->ReturnParameter($input_username, "mail", $settings);
    }

    function ReturnParameter($input_username, $input_parameter, $settings)
    {
        $ldapserver = $settings["ldap_baseDN"];
        $qc_username = $settings["service_username"];
        $password = $settings["service_password"];
        $ldap = ldap_connect($ldapserver);
        if ($bind = ldap_bind($ldap, $qc_username, $password)) {
            $result = ldap_search($ldap, "", "(CN=$input_username)") or die ("Error in search query: " . ldap_error($ldap));
            $data = ldap_get_entries($ldap, $result);
            if (isset($data[0][$input_parameter][0])) {
                return $data[0][$input_parameter][0];

            }
        }
        ldap_close($ldap);
        return "fail";
    }

    private function set_displayname($input)
    {
        $this->displayname = $input;
        return $this;
    }

    function ReturnDisplayName($input_username, $settings)
    {
        return $this->ReturnParameter($input_username, "displayname", $settings);
    }

    function IsNotNullOrEmptyString($question)
    {
        return (!isset($question) || trim($question) === '');
    }

}
