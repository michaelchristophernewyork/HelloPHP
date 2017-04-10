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
                ->setLastLogin($user['last_login'])
                ->setIsActive($user['is_active'])
                ->setIsAdministrator($user['is_administrator'])
                ->setIsReporter($user['active'])
                ->setIsBanned($user['is_banned']));
        }
        return $users;
    }

    private function setIsBanned($input)
    {
        $this->active = $input;
        return $this;
    }

    private function setIsReporter($input)
    {
        $this->isReporter = $input;
        return $this;
    }

    private function setIsAdministrator($input)
    {
        $this->isAdministrator = $input;
        return $this;
    }

    private function setIsActive($input)
    {
        $this->isActive = $input;
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

    private function setDisplayName($input)
    {
        if ($input != null)
        {
            $this->displayName = $input;
        } else {
            $this->displayName = $this->getUsername();
        }
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    private function setUsername($input)
    {
        $this->username = $input;
        return $this;
    }

    public static function create()
    {
        $instance = new self();
        return $instance;
    }

    public static function fetchByUsername(DB $db, $username)
    {
        $req = $db->prepare('SELECT username, display_name, password, email, last_login, is_active, is_administrator, is_reporter, is_banned FROM users WHERE username = :username');
        $req->execute(array('username' => $username));
        $user = $req->fetch();
        return User::create()
                ->setUsername($user['username'])
                ->setDisplayName($user['display_name'])
                ->setPassword($user['password'])
                ->setEmail($user['email'])
                ->setLastLogin($user['last_login'])
                ->setIsActive($user['is_active'])
                ->setIsAdministrator($user['is_administrator'])
                ->setIsReporter($user['active'])
                ->setIsBanned($user['is_banned']);
    }

    public static function removeUser($username)
    {
        $db = Db::getInstance();
        $req = $db->prepare('DELETE FROM users WHERE username = :username');
        $req->bindParam(':username', $username, \PDO::PARAM_STR, 255);
        $req->execute();
    }

    public static function addUser($username, $displayName, $password, $email)
    {
        $db = Db::getInstance();
        $req = $db->prepare('INSERT INTO users (username, display_name, password, email, last_login) VALUES (:username, :display_name, :password, :email)');
        $req->bindParam(':username', $username, \PDO::PARAM_STR, 255);
        $req->bindParam(':display_name', $displayName, \PDO::PARAM_STR, 255);
        $req->bindParam(':password', $password, \PDO::PARAM_STR, 255);
        $req->bindParam(':email', $email, \PDO::PARAM_STR, 255);
        $req->execute();
    }

    public static function hashPassword($input)
    {
        $options = ['cost' => 12,];
        return password_hash($input, PASSWORD_BCRYPT, $options);
    }

    public function getEmail()
    {
        return $this->email;
    }

    // INSERT INTO users (username, display_name, password, email) VALUES ('kushal', 'kushal', '$2b$12$bVGt6HWAxldbT4f2krB02uPQJTv6vWlWZjVH33.JdbP6ToA4THt2W', 'khada@qc.cuny.edu')

    public function getDisplayName()
    {
        return $this->display_name;
    }

    function fetchByEmail($email)
    {
        $db = Db::getInstance();
        $req = $db->prepare('SELECT username, display_name, password, email, last_login, is_active, is_administrator, is_reporter, is_banned FROM users WHERE email = :email LIMIT 1');
        $req->execute(array('email' => $email));
        $user = $req->fetch();
        return User::create()
                ->setUsername($user['username'])
                ->setDisplayName($user['display_name'])
                ->setPassword($user['password'])
                ->setEmail($user['email'])
                ->setLastLogin($user['last_login'])
                ->setIsActive($user['is_active'])
                ->setIsAdministrator($user['is_administrator'])
                ->setIsReporter($user['active'])
                ->setIsBanned($user['is_banned']);
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

    function ReturnEmailAddress($inputUsername, $settings)
    {
        return $this->ReturnParameter($inputUsername, "mail", $settings);
    }

    function ReturnParameter($inputUsername, $input_parameter, $settings)
    {
        $ldapserver = $settings["ldap_baseDN"];
        $qc_username = $settings["service_username"];
        $password = $settings["service_password"];
        $ldap = ldap_connect($ldapserver);
        if ($bind = ldap_bind($ldap, $qc_username, $password)) {
            $result = ldap_search($ldap, "", "(CN=$inputUsername)") or die ("Error in search query: " . ldap_error($ldap));
            $data = ldap_get_entries($ldap, $result);
            if (isset($data[0][$input_parameter][0])) {
                return $data[0][$input_parameter][0];

            }
        }
        ldap_close($ldap);
        return "fail";
    }

    function ReturnDisplayName($inputUsername, $settings)
    {
        return $this->ReturnParameter($inputUsername, "displayname", $settings);
    }

    function IsNotNullOrEmptyString($question)
    {
        return (!isset($question) || trim($question) === '');
    }

}
