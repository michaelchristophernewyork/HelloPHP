<?php
namespace model;
class User
{
    private $id;
    private $username;
    private $displayName;
    private $password;
    private $email;
    private $lastLogin;
    private $isActive;
    private $isAdministrator;
    private $isReporter;
    private $isBanned;

    function __construct()
    {
    }

    private function setIsActive($input)
    {
        $this->active = $input;
        return $this;
    }

    private function setLastLogin($input)
    {
        $this->lastlogin = $input;
        return $this;
    }

    private function setEmail($input)
    {
        $this->email = $input;
        return $this;
    }

    private function setpassword($input)
    {
        $this->password = $input;
        return $this;
    }

    private function setUsername($input)
    {
        $this->username = $input;
        return $this;
    }

    private function setDisplayName($input)
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

    public static function fetchAll()
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT username, display_name, password, email, last_login, is_active, is_administrator, is_reporter, is_banned FROM users');
        $req->execute();
        $users = array();
        foreach ($req->fetchAll() as $user) {
            array_push($users, User::create()
                ->setUsername($user['username'])
                ->setDisplayName($user['display_name'])
                ->setPassword($user['password'])
                ->setEmail($user['email'])
                ->setLastLogin($user['lastlogin'])
                ->setIsActive($user['active']));
        }
        return $users;
    }

    public static function fetchByUsername($username)
    {
        echo $username;
        $db = Db::getInstance();
        $req = $db->prepare('SELECT username, display_name, password, email, last_login, is_active, is_administrator, is_reporter, is_banned FROM users WHERE username = :username');
        $req->execute(array('username' => $username));
        $user = $req->fetch();
        return User::create()
            ->set_username($user['username'])
            ->set_display_name($user['display_name'])
            ->set_password($user['password'])
            ->set_email($user['email'])
            ->set_last_login($user['lastlogin'])
            ->set_is_active($user['active']);
    }

    public static function removeUser($username)
    {
        $db = Db::getInstance();
        $req = $db->prepare('DELETE FROM users WHERE username = :username');
        $req->bindParam(':username', $username, \PDO::PARAM_STR, 255);
        $req->execute();
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getDisplayName()
    {
        return $this->display_name;
    }

    public function getUsername()
    {
        return $this->username;
    }

    function fetchByEmail($email)
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT username, display_name, password, email, last_login, is_active, is_administrator, is_reporter, is_banned FROM users WHERE email = :email LIMIT 1');
        $req->execute(array('email' => $email));
        $user = $req->fetch();
        return User::create()
            ->set_username($user['username'])
            ->set_display_name($user['display_name'])
            ->set_password($user['password'])
            ->set_email($user['email'])
            ->set_last_login($user['lastlogin'])
            ->set_is_active($user['active']);
    }

    // INSERT INTO users (username, display_name, password, email) VALUES ('kushal', 'kushal', '$2b$12$bVGt6HWAxldbT4f2krB02uPQJTv6vWlWZjVH33.JdbP6ToA4THt2W', 'khada@qc.cuny.edu')

    public function addUser($username, $display_name, $password, $email, $active)
    {
        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO users (username, display_name, password, email, last_login) VALUES (:username, :display_name, :password, :email)');
        $req->bindParam(':username', $username, \PDO::PARAM_STR, 255);
        $req->bindParam(':display_name', $display_name, \PDO::PARAM_STR, 255);
        $req->bindParam(':password', $password, \PDO::PARAM_STR, 255);
        $req->bindParam(':email', $email, \PDO::PARAM_STR, 255);
        $req->execute();
    }

    public function verifyPassword($input)
    {
        if (password_verify($input, $this->password)) {
            return true;
        } else {
            return false;
        }
    }

    public function addUserLdap($username)
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

    function ReturnDisplayName($input_username, $settings)
    {
        return $this->ReturnParameter($input_username, "displayname", $settings);
    }

    function IsNotNullOrEmptyString($question)
    {
        return (!isset($question) || trim($question) === '');
    }

}
