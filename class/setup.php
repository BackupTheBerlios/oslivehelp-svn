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
    // This class contains all the setup functions

    class Setup {

        var $result;
        var $sql;
        var $files;
        var $file;
        var $dirs;
        var $id;

        function install_db()
        {
            $GLOBALS['inc']->db();
            $this->sql = $GLOBALS['file']->read(dirname(__FILE__).'/../setup/database.sql');
            $GLOBALS['db']->query($this->sql);
            $GLOBALS['db']->query('INSERT INTO `operators` (`username`, `password`, `firstname`, `lastname`, `email`, `showpic`, `autosave`, `level`,`picture`) VALUES ("admin", "'.md5('admin').'", "Default", "User", "admin@example.com", "0", "1", "0","")');
            $this->id = $GLOBALS['db']->id();
            $GLOBALS['db']->query('INSERT INTO `notes` (`timestamp`,`operatorid`, `subject`, `message`) VALUES (UNIX_TIMESTAMP(), "'.$this->id.'", "'.$GLOBALS['lang']['hcl_welcome'].'", "'.$GLOBALS['lang']['hcl_welcome_message'].'")');
        }

        function upgrade_db()
        {
            $GLOBALS['inc']->db();
            $this->sql = $GLOBALS['file']->read(dirname(__FILE__).'/../setup/upgrade.sql');
            if ($GLOBALS['db']->query($this->sql)) {
                return true;
            } else {
                return false;
            }
        }

        function conf($host, $database, $username, $password, $prefix, $url, $monitor_traffic, $template, $company, $language)
        {
            $this->file = $GLOBALS['file']->read(dirname(__FILE__).'/../config.php');
            $this->file = preg_replace("/\['host'\] = '.*';/", '[\'host\'] = \''.$host.'\';', $this->file);
            $this->file = preg_replace("/\['database'\] = '.*';/", '[\'database\'] = \''.$database.'\';', $this->file);
            $this->file = preg_replace("/\['username'\] = '.*';/", '[\'username\'] = \''.$username.'\';', $this->file);
            $this->file = preg_replace("/\['password'\] = '.*';/", '[\'password\'] = \''.$password.'\';', $this->file);
            $this->file = preg_replace("/\['prefix'\] = '.*';/", '[\'prefix\'] = \''.$prefix.'\';', $this->file);
            $this->file = preg_replace("/\['url'\] = '.*';/", '[\'url\'] = \''.$url.'\';', $this->file);
            $this->file = preg_replace("/\['monitor_traffic\'\] = '.*';/", '$conf[\'monitor_traffic\'] = \''.$monitor_traffic.'\';', $this->file);
            $this->file = preg_replace("/\['template'\] = '.*';/", '[\'template\'] = \''.$template.'\';', $this->file);
            $this->file = preg_replace("/\['lang'\] = '.*';/", '[\'lang\'] = \''.substr($language, 0, -4).'\';', $this->file);
            $this->file = preg_replace("/\['company'\] = '.*';/", '[\'company\'] = \''.$company.'\';', $this->file);
            $GLOBALS['file']->save(dirname(__FILE__).'/../config.php', $this->file);
        }

        function language($language = '')
        {
            if ($language == '') {
                $results = array();
                $this->files = $GLOBALS['file']->filelist(dirname(__FILE__).'/../lang/', 'php');
                foreach ($this->files as $key => $val) {
                    $this->files[$key]['name'] = ucfirst(strtolower(substr($this->files[$key]['file'], 0, -4)));
                    if (substr($this->files[$key]['name'], 0, 5) !== 'Index') {
                        $results[] = $this->files[$key];
                    }
                }
                return $results;
            } else {
                $_SESSION['hcl_language'] = $language;
            }
        }

        function template()
        {
            $results = array();
            $this->dirs = $GLOBALS['file']->dirlist(dirname(__FILE__).'/../templates/');
            foreach ($this->dirs as $key => $val) {
                $this->dirs[$key]['name'] = ucfirst(strtolower($this->dirs[$key]['dir']));
                if (substr($this->dirs[$key]['name'], 0, 5) !== 'Index') {
                    $results[] = $this->dirs[$key];
                }
            }
            return $results;
        }

    }

?>