<?php

    // Copyright (c) 2005 Help Center Live. All Rights Reserved

    // This file is part of Help Center Live.

    // Help Center Live is free software; you can redistribute it and/or modify
    // it under the terms of the GNU General Public License as published by
    // the Free Software Foundation; either version 2 of the License, or
    // (at your option) any later version.

    // Help Center Live is distributed in the hope that it will be useful,
    // but WITHOUT ANY WARRANTY; without even the implied warranty of
    // MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    // GNU General Public License for more details.

    // You should have received a copy of the GNU General Public License
    // along with Help Center Live; if not, write to the Free Software
    // Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

    // Contributors: Michael Bird, Michael Anderson

    // File Comments:
    // This class contains all the authentication functions

    class Auth {

        var $username;
        var $password;
        var $result;
        var $operatorname;

        // This function returns a boolean as to whether the current
        // person logged in is an operator. If the arguements
        // $username (and optional $password) are passed into the function,
        // that operator will be checked instead.
        function operator($username = '', $password = '')
        {
            if ($username == '' && $password == '') {
                if (isset($_SESSION['hcl_username']) && isset($_SESSION['hcl_password'])) {
                    if ($GLOBALS['db']->query('SELECT `id` FROM `operators` WHERE `username`="'.$_SESSION['hcl_username'].'" AND `password`="'.$_SESSION['hcl_password'].'"')) {
                        $this->username = $_SESSION['hcl_username'];
                        $this->password = $_SESSION['hcl_password'];
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                if ($username !== '' && $password !== '') {
                    if ($GLOBALS['db']->query('SELECT `id` FROM `operators` WHERE `username`="'.$username.'" AND `password`="'.md5($password).'"')) {
                        return true;
                    } else {
                        return false;
                    }
                } elseif ($username !== '' && $password == '') {
                    if ($GLOBALS['db']->query('SELECT `id` FROM `operators` WHERE `username`="'.$username.'"')) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }

        function admin()
        {
            if (isset($_SESSION['hcl_username']) && isset($_SESSION['hcl_password'])) {
                if ($this->result = $GLOBALS['db']->query('SELECT `level` FROM `operators` WHERE `username`="'.$_SESSION['hcl_username'].'" AND `password`="'.$_SESSION['hcl_password'].'"')) {
                    if ($this->result[0]['level'] == '0') {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function login($username, $password, $arg = '')
        {
            if ($arg == '') {
                if ($login = $GLOBALS['db']->query('SELECT `id` FROM `operators` WHERE `username`="'.$username.'" AND `password`="'.md5($password).'"')) {
                    $_SESSION['hcl_username'] = $username;
                    $_SESSION['hcl_password'] = md5($password);
                    return true;
                } else {
                    return false;
                }
            } else {
                switch($arg) {
                    case 'no_md5':
                        if ($GLOBALS['db']->query('SELECT `id` FROM `operators` WHERE `username`="'.$username.'" AND `password`="'.$password.'"')) {
                            $_SESSION['hcl_username'] = $username;
                            $_SESSION['hcl_password'] = $password;
                            return true;
                        } else {
                            return false;
                        }
                        break;
                    default:
                        return false;
                }
            }
        }

        function logout()
        {
            unset($_SESSION['hcl_username']);
            unset($_SESSION['hcl_password']);
            if (!isset($_SESSION['hcl_username']) && !isset($_SESSION['hcl_password'])) {
                return true;
            } else {
                return false;
            }
        }

        function check_login()
        {
             if ($this->operator()) {
                  header('Location: '.$GLOBALS['conf']['url'].'/admin/index.php');
             }
        }

        function check_logout()
        {
            if (!$this->operator()) {
                 header('Location: '.$GLOBALS['conf']['url'].'/admin/login.php');
                 exit;
            }
        }

    }

?>