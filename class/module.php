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
    // This class allows implementation of modules

    class Module {

        var $result;
        var $modules;
        var $links;
        var $array;

        function exists($module) {
            if (is_dir(dirname(__FILE__).'/../modules/'.$module)) {
                if (is_dir(dirname(__FILE__).'/../modules/'.$module.'/hcl')) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        function active($module) {
            $this->get_all();
            foreach ($this->modules as $key => $var) {
                if ($this->modules[$key]['dir'] == $module) {
                    if ($this->get_config($this->modules[$key]['dir'])) {
                        if ($GLOBALS['conf']['modules'][$this->modules[$key]['dir']]['active']) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            }
        }

        function auth($module)
        {
            if ($this->exists($module)) {
                include_once(dirname(__FILE__).'/../modules/'.$module.'/hcl/auth.php');
                return true;
            } else {
                return false;
            }
        }

        function links($type, $display = '')
        {
            $this->links = array();
            $this->get_all();
            foreach ($this->modules as $key => $val) {
                if ($this->get_config($this->modules[$key]['dir'])) {
                    if ($GLOBALS['conf']['modules'][$this->modules[$key]['dir']]['active'] && ($GLOBALS['conf']['modules'][$this->modules[$key]['dir']]['display'] || $display == 'true')) {
                        if ($GLOBALS['conf']['modules'][$this->modules[$key]['dir']]['operator'] !== '') {
                            $this->array['name'] = $this->modules[$key]['dir'];
                            $this->array['title'] = $GLOBALS['conf']['modules'][$this->modules[$key]['dir']]['title'];
                            switch ($type) {
                            case 'operator':
                                $this->array['url'] = $GLOBALS['conf']['url'].'/admin/module.php?module='.$this->modules[$key]['dir'].'&file='.$GLOBALS['conf']['modules'][$this->modules[$key]['dir']]['operator'].'&type=operator';
                                break;
                            case 'admin':
                                $this->array['url'] = $GLOBALS['conf']['url'].'/admin/module.php?module='.$this->modules[$key]['dir'].'&file='.$GLOBALS['conf']['modules'][$this->modules[$key]['dir']]['admin'].'&type=admin';
                                break;
                            case 'conf':
                                $this->array['url'] = $GLOBALS['conf']['url'].'/admin/module.php?module='.$this->modules[$key]['dir'].'&file='.$GLOBALS['conf']['modules'][$this->modules[$key]['dir']]['conf'].'&type=conf';
                                break;
                            }
                            $this->links[] = $this->array;
                        }
                    }
                }
            }
            return $this->links;
        }

        function get_all()
        {
            $this->modules = $GLOBALS['file']->dirlist(dirname(__FILE__).'/../modules/');
            return $this->modules;
        }

        function get_config($module = '')
        {
            if ($module !== '') {
                if ($this->exists($module)) {
                    include_once(dirname(__FILE__).'/../modules/'.$module.'/hcl/config.php');
                    return true;
                } else {
                    return false;
                }
            } else {
                $this->get_all();
                foreach ($this->modules as $key => $val) {
                    $this->get_config($this->modules[$key]['dir']);
                }
            }
        }

    }

?>