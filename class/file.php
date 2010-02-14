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

    // Contributors: Michael Bird

    // File Comments:
    // This class contains all the functions that will be used to read and write to files

    class File {

        function max_size()
        {
            if (intval(ini_get('upload_max_filesize')) > intval(ini_get('post_max_size'))) {
                return ini_get('post_max_size');
            } else {
                return ini_get('upload_max_filesize');
            }
        }

        function read($file)
        {
            $handle = fopen($file, "r");
            if (filesize($file) > 0) {
                $contents = fread($handle, filesize($file));
                fclose($handle);
            } else {
                $contents = '';
            }
            return $contents;
        }

        function check_write($file)
        {
            if (is_writable($file)) {
                $contents = file_get_contents($file);
                $handle = fopen($file, "w");
                $write = fwrite($handle, $contents);
                fclose($handle);
                if ($write === FALSE) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }

        function save($file, $contents)
        {
            if (is_writable($file)) {
                $handle = fopen($file, "w");
                fwrite($handle, $contents);
                fclose($handle);
                return true;
            } else {
                return false;
            }
        }

        function create($file, $contents)
        {
            if ($handle = @fopen($file, "w")) {
                fwrite($handle, $contents);
                fclose($handle);
                return true;
            } else {
                return false;
            }
        }

        function rdelete($dir)
        {
            if (file_exists($dir)) {
                if ($dp = opendir($dir)) {
                    while (false !== ($file = readdir($dp))) {
                        if ($file !== '.' && $file !== '..') {
                            if (!is_dir($dir.DIRECTORY_SEPARATOR.$file)) {
                                @unlink($dir.DIRECTORY_SEPARATOR.$file);
                            } else {
                                $this->rdelete($dir.DIRECTORY_SEPARATOR.$file);
                            }
                        }
                    }
                    closedir($dp);
                }
			}
        }

        function filelist($dir, $arg = '')
        {
            $list = array();
            $handle = opendir($dir);
            while ($file = readdir($handle)) {
                if ($file !== '.' && $file !== '..') {
                    switch ($arg) {
                        case 'php':
                            if (substr($file, -3) == 'php') {
                                $list[] = array('file' => $file);
                            }
                            break;
                        default:
                            $list[] = array('file' => $file);
                    }
                }
            }
            closedir($handle);
            return $list;
        }

        function dirlist($dir)
        {
            $list = array();
            $handle = opendir($dir);
            while ($name = readdir($handle)) {
                if ($name !== '.' && $name !== '..') {
                    $list[] = array('dir' => $name);
                }
            }
            closedir($handle);
            return $list;
        }

        function insert($admin, $file, $chatid)
        {
            $filecontent = $file["tmp_name"];
            $filesize = $file["size"];
            $size = round(($filesize / 1000), 2);
            $size = $size." kb";
            $filename = $file["name"];
            $filetype = $file["type"];
            $fileopen = fopen($filecontent, "rb");
            $filedata = base64_encode(fread($fileopen, $filesize));
            if ($filedata === '') $filedata = ' ';
            if ($filetype === '') $filetype = ' ';
            if ($filename === '') $filename = ' ';
            if ($size === '') $size = '0 kb';
            if ($admin == 'true') {
                $GLOBALS['db']->query('INSERT INTO `files` (`chatid`, `file`, `type`, `name`, `size`, `status`) VALUES ("'.$chatid.'", "'.$filedata.'", "'.$filetype.'", "'.$filename.'", "'.$size.'", "operator")');
            } else {
                $GLOBALS['db']->query('INSERT INTO `files` (`chatid`, `file`, `type`, `name`, `size`, `status`) VALUES ("'.$chatid.'", "'.$filedata.'", "'.$filetype.'", "'.$filename.'", "'.$size.'", "user")');
            }
            fclose($fileopen);
            return $size;
        }
        
        function get($admin, $id, $client = '')
        {
            $this->result = $GLOBALS['db']->query('SELECT * FROM `files` WHERE `id`="'.$id.'"');
            if (($admin == 'true' && $this->result[0]['status'] == 'operator_received') || ($admin == 'false' && $this->result[0]['status'] == 'user_received')) {
                $this->result[0]['file'] = base64_decode($this->result[0]['file']);
                $GLOBALS['db']->query('DELETE FROM `files` WHERE `id`="'.$id.'"');
                if ($client !== 'true') {
                    unset($_SESSION['hcl_'.$_GET['chatid']]['fileid']);
                }
                return $this->result[0];
            } else {
                return false;
            }
        }

        function request_transfer($chatid)
        {
            return $GLOBALS['db']->query('UPDATE `sessions` SET `transfer`="yes" WHERE `chatid`="'.$chatid.'"');
        }

        function upload_icon($id, $icon, $status)
        {
            if ($id !== '0') {
                switch ($icon['type']) {
                case 'image/gif':
                    if (move_uploaded_file($icon['tmp_name'], dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.gif')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'image/jpeg':
                    if (move_uploaded_file($icon['tmp_name'], dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.jpg')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'image/png':
                    if (move_uploaded_file($icon['tmp_name'], dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.png')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'application/x-shockwave-flash':
                    if (move_uploaded_file($icon['tmp_name'], dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.swf')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                default:
                    return false;
                }
            } else {
                switch ($icon['type']) {
                case 'image/gif':
                    if (move_uploaded_file($icon['tmp_name'], dirname(__FILE__).'/../icons/'.$status.'.gif')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'image/jpeg':
                    if (move_uploaded_file($icon['tmp_name'], dirname(__FILE__).'/../icons/'.$status.'.jpg')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'image/png':
                    if (move_uploaded_file($icon['tmp_name'], dirname(__FILE__).'/../icons/'.$status.'.png')) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 'application/x-shockwave-flash':
                    if (move_uploaded_file($icon['tmp_name'], dirname(__FILE__).'/../icons/'.$status.'.swf')) {
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

        function delete_icon($id, $status)
        {
            if ($id !== '0') {
                if (file_exists(dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.gif')) {
                    unlink(dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.gif');
                }
                if (file_exists(dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.jpg')) {
                    unlink(dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.jpg');
                }
                if (file_exists(dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.png')) {
                    unlink(dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.png');
                }
                if (file_exists(dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.swf')) {
                    unlink(dirname(__FILE__).'/../icons/'.$status.'_'.$id.'.swf');
                }
            } else {
                if (file_exists(dirname(__FILE__).'/../icons/'.$status.'.gif')) {
                    unlink(dirname(__FILE__).'/../icons/'.$status.'.gif');
                }
                if (file_exists(dirname(__FILE__).'/../icons/'.$status.'.jpg')) {
                    unlink(dirname(__FILE__).'/../icons/'.$status.'.jpg');
                }
                if (file_exists(dirname(__FILE__).'/../icons/'.$status.'.png')) {
                    unlink(dirname(__FILE__).'/../icons/'.$status.'.png');
                }
                if (file_exists(dirname(__FILE__).'/../icons/'.$status.'.swf')) {
                    unlink(dirname(__FILE__).'/../icons/'.$status.'.swf');
                }
            }
            return true;
        }

    }

?>