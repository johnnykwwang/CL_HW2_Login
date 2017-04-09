<?php

/**
 * Class login
 * handles the user's login and logout process
 */

$uamserect = "tauam";

class Login
{
    /**
     * @var object The database connection
     */
    private $db_connection = null;
    /**
     * @var array Collection of error messages
     */
    public $errors = array();
    /**
     * @var array Collection of success / neutral messages
     */
    public $messages = array();

    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$login = new Login();"
     */
    public function __construct()
    {
        // create/read session, absolutely necessary
        session_start();

        // check the possible login actions:
        // if user tried to log out (happen when user clicks logout button)
        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
        // login via post data (if user just submitted a login form)
        elseif (isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
    }
    private function attempt_login() {
        global $uamsecret, $userpassword;
        
        echo "<h1>Logging in...</h1>";

        $hexchal = pack ("H32", $_POST['chal']);
        $newchal = $uamsecret ? pack("H*", md5($hexchal . $uamsecret)) : $hexchal;

        $response = md5("\0" . $_POST['user_password'] . $newchal);
        
        $newpwd = pack("a32", $_POST['user_password']);
        $pappassword = implode ('', unpack("H32", ($newpwd ^ $newchal)));
        
        if ((isset ($uamsecret)) && isset($userpassword)) {
            print implode('', array(
                '<meta http-equiv="refresh" content="0;url=',
                'http://', $_POST['uamip'], ':', $_POST['uamport'], '/',
                'logon?username=', $_POST['username'], '&password=', $pappassword, '">'
            ));
        } else {
            print implode('', array(
                '<meta http-equiv="refresh" content="0;url=',
                'http://', $_POST['uamip'], ':', $_POST['uamport'], '/',
                'logon?username=', $_POST['username'], '&response=', $response,
                '&userurl=', $_POST['userurl'], '">'
            ));
        }
    }
    /* private function redirect($v_url, $v_permanent = false){ */
    /*     if ($v_permanent){ */
    /*       header("HTTP/1.1 301 Moved Permanently"); */
    /*     } */
    /*     echo 'Redirecting..'; */
    /*     header("Location: ".$v_url); */
    /*     exit; */
    /*   } // redirect() */

    /**
     * log in with post data
     */
    private function dologinWithPostData()
    {
        // check login form contents
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Username field was empty.";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] = "Password field was empty.";
        } elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            // create a database connection, using the constants from config/db.php (which we loaded in index.php)
            $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // change character set to utf8 and check it
            if (!$this->db_connection->set_charset("utf8")) {
                $this->errors[] = $this->db_connection->error;
            }

            // if no connection errors (= working database connection)
            if (!$this->db_connection->connect_errno) {

                // escape the POST stuff
                $user_name = $this->db_connection->real_escape_string($_POST['user_name']);

                // database query, getting all the info of the selected user (allows login via email address in the
                // username field)
                $sql = "SELECT username, value
                        FROM radcheck
                        WHERE username = '" . $user_name . "';";
                $result_of_login_check = $this->db_connection->query($sql);

                // if this user exists
                if ($result_of_login_check->num_rows == 1) {

                    // get result row (as an object)
                    $result_row = $result_of_login_check->fetch_object();

                    // using PHP 5.5's password_verify() function to check if the provided password fits
                    // the hash of that user's password
                    if (password_verify($_POST['user_password'], $result_row->value)) {

                        // write user data into PHP SESSION (a file on your server)
                        $_SESSION['user_name'] = $result_row->user_name;
                        //$_SESSION['user_email'] = $result_row->user_email;
                        $_SESSION['user_login_status'] = 1;
                        $this->attempt_login();
                    } else {
                        $this->errors[] = "Wrong password. Try again.";
                    }
                } else {
                    $this->errors[] = "This user does not exist.";
                }
            } else {
                $this->errors[] = "Database connection problem.";
            }
        }
    }

    /**
     * perform the logout
     */
    public function doLogout()
    {
        // delete the session of the user
        $_SESSION = array();
        session_destroy();
        // return a little feeedback message
        $this->messages[] = "You have been logged out.";

    }

    /**
     * simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1) {
            return true;
        }
        // default return
        return false;
    }
}
