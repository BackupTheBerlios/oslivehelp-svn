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

    // Contributors: Michael Bird, Jacob Mather

    // File Comments:
    // This class is used to carry out all the database functions.
    // Only MySQL databases are supported at the moment

    define('HCL_DB_ALL', 1);
    define('HCL_DB_ROWS', 2);
    define('HCL_DB_HEADERS', 4);
    define('HCL_DB_CACHE', 8);
    define('HCL_DB_NOCACHE', 16);
    define('HCL_DB_CLEARCACHE', 32);

    class Database {

        var $id;
        var $connection;
        var $error;
        var $result;
        var $total_results;
        var $select_result;
        var $table;

        // Connect to the database, then set $this->connection to true
        // if connected or false if not connected
        function connect()
        {
            $this->id = mysql_connect($GLOBALS['conf']['host'], $GLOBALS['conf']['username'], $GLOBALS['conf']['password']) or die(mysql_error());
            if ($this->id) {
                $this->connection = true;
                $this->select();
            } else {
                $this->connection = false;
                $this->error = mysql_error($this->id);
            }
        }

        // Disonnect, then set $this->connection to true
        // if connected or false if not connected
        function disconnect()
        {
            if ($this->connection) {
                mysql_close($this->id);
                $this->connection = false;
            }
        }

        // Status of the database connection, return boolean
        // (true if connected or false if disconnected), or
        // return error message and end script executing if
        // arguement 'die' is passed into the function
        function status($arg)
        {
            switch ($arg) {
                case 'die':
                    if ($this->connection = false) {
                        echo $this->error;
                        exit;
                    }
                    return false;
                    break;
                default:
                    return $this->connection;
            }
        }

        function select()
        {
            mysql_select_db($GLOBALS['conf']['database'], $this->id) or die(mysql_error());
        }

        // Prefixes the SQL table names
        // Work-around for mysql commands such as COUNT needed. Use raw_query() for now
        function prefix($sql)
        {
        	$matches = '';
            if (ereg('^SELECT', $sql)) {
                preg_match("/SELECT (.*) FROM (.*) WHERE (.*)/", $sql, $matches);
                /*preg_match_all("|`[^`]+\.[^`]+`|U", $matches[1], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[1] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[1]);
                    }
                }*/
                $return = '';
                preg_match_all("|`[^`]+`|U", $matches[2], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[2] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[2]);
                    }
                }
                /*preg_match_all("|`[^\.]+\.[^`]+`=\"\"|U", $matches[3], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[3] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[3]);
                    }
                }*/
                $sql = 'SELECT '.$matches[1].' FROM '.$matches[2].' WHERE '.$matches[3];
            	$this->table = substr($matches[2], 1, (strlen($matches[2]) - 2));
            } elseif (ereg('^UPDATE', $sql)) {
                preg_match("/UPDATE (.*) SET (.*) WHERE (.*)/", $sql, $matches);
                preg_match_all("|`[^`]+`|U", $matches[1], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[1] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[1]);
                    }
                }
                /*preg_match_all("|`[^\.]+\.[^`]+`=\"\"|U", $matches[2], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[2] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[2]);
                    }
                }
                preg_match_all("|`[^\.]+\.[^`]+`=\"\"|U", $matches[3], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[3] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[3]);
                    }
                }*/
                $sql = 'UPDATE '.$matches[1].' SET '.$matches[2].' WHERE '.$matches[3];
            	$this->table = substr($matches[1], 1, (strlen($matches[1]) - 2));
            } elseif (ereg('^ALTER (.*) CHANGE', $sql)) {
                preg_match("/ALTER TABLE (.*) CHANGE (.*)/", $sql, $matches);
                preg_match_all("|`[^`]+`|U", $matches[1], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[1] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[1]);
                    }
                }
                /*preg_match_all("|`[^\.]+\.[^`]+`=\"\"|U", $matches[2], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[2] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[2]);
                    }
                }
                preg_match_all("|`[^\.]+\.[^`]+`=\"\"|U", $matches[3], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[3] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[3]);
                    }
                }*/
                $sql = 'ALTER TABLE '.$matches[1].' CHANGE '.$matches[2];
            	$this->table = substr($matches[1], 1, (strlen($matches[1]) - 2));
            } elseif (ereg('^ALTER (.*) ADD', $sql)) {
                preg_match("/ALTER TABLE (.*) ADD (.*)/", $sql, $matches);
                preg_match_all("|`[^`]+`|U", $matches[1], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[1] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[1]);
                    }
                }
                /*preg_match_all("|`[^\.]+\.[^`]+`=\"\"|U", $matches[2], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[2] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[2]);
                    }
                }
                preg_match_all("|`[^\.]+\.[^`]+`=\"\"|U", $matches[3], $return);
                foreach ($return as $a) {
                    foreach ($a as $from) {
                        $matches[3] = str_replace($from, '`'.$GLOBALS['conf']['prefix'].substr($from, 1), $matches[3]);
                    }
                }*/
                $sql = 'ALTER TABLE '.$matches[1].' ADD '.$matches[2];
            	$this->table = substr($matches[1], 1, (strlen($matches[1]) - 2));
            } else {
                $pos = strpos($sql, '`');
                $prefix = substr($sql, $pos, strlen($GLOBALS['conf']['prefix']));
                if ($prefix !== $GLOBALS['conf']['prefix']) {
                    $sql = str_replace(substr($sql, 0, ($pos + 1)), substr($sql, 0, ($pos + 1)).$GLOBALS['conf']['prefix'], $sql);
                }
                $pos = strpos($sql, '`');
                $table = substr($sql, ($pos + 1));
                $pos = strpos($table, '`');
                $this->table = substr($table, 0, $pos);
            }
            return $sql;
        }

        // Remove all comments and split queries in the SQL
        // good for queries such as phpMyAdmin dumps
        function query($sql, $table = '', $cache = '', $arg = '')
        {
            $line = split("\n", $sql);
            if (count($line) == 1) {
            // Put in the prefixes
                $line[0] = $this->prefix($line[0]);
                if ($table == '') {
                	$table = $this->table;
                }
                return $this->raw_query($line[0], $table, $cache, $arg);
            } else {
                $plain_sql = '';
                foreach ($line as $val) {
                    if (!ereg('<*>', $val)) {
                        $tmp = trim(trim(stripslashes($val), '\t'));
                        if ($tmp !== '' && $tmp{0} !== '#' && $tmp{0} !== '-') {
                            if ($tmp[(strlen($tmp) - 1)] == ';') {
                                $plain_sql .= $tmp;
                                $plain_sql = $this->prefix($plain_sql);
				                if ($table == '') {
				                	$table = $this->table;
				                }
                                $this->raw_query($plain_sql, $table, $cache, $arg);
                                $plain_sql = '';
                            } else {
                                $plain_sql .= $tmp;
                            }
                        }
                    }
                }
                return true;
            }
        }

        // Querys whatever is passed into it without SQL validation checking
        function raw_query($sql, $table, $cache = '', $arg = '')
        {
            if ($sql == '') {
                return false;
            }
            if ($arg == '') {
                $arg = HCL_DB_ROWS;
            }
            if ($cache == '') {
            	$cache = HCL_DB_CACHE;
            }
			if ($cache == HCL_DB_CLEARCACHE) {
            	$this->clear_cache();
            }
            $this->select_result = '';
            if (ereg('^SELECT', $sql)) {
            	// Use cache to reduce database queries
            	if ($cache == HCL_DB_CACHE) {
	            	$this->result = $this->get_cache($sql, $table);
            	} else {
            		$this->result = false;
            	}
	            if (!$this->result) {
	                $this->result = mysql_query($sql, $this->id);
	                $this->total_results = 0;
	                while ($current_row = mysql_fetch_row($this->result)) {
	                    if (($arg == HCL_DB_ALL || $arg == HCL_DB_HEADERS) && $this->total_results == 0) {
	                        foreach($current_row as $key => $val) {
	                            $this->select_result[$this->total_results][$key] = $this->field($this->result, $key);
	                        }
	                        $this->total_results++;
	                    }
	                    if ($arg == HCL_DB_ALL || $arg == HCL_DB_ROWS) {
	                        foreach($current_row as $key => $val) {
	                            $this->select_result[$this->total_results][$this->field($this->result, $key)] = $val;
	                        }
	                        $this->total_results++;
	                    }
	                }
	                if ($this->select_result !== '') {
	                	if ($cache !== HCL_DB_NOCACHE) {
	                		$this->set_cache($sql, $table, $this->select_result);
	                	}
	                    return $this->select_result;
	                } else {
	                	if ($cache !== HCL_DB_NOCACHE) {
	                		$this->set_cache($sql, $table, false);
	                	}
	                    return false;
	                }
	            } else {
                    return $this->result;
	            }
            } else {
	            $this->result = mysql_query($sql, $this->id);
                if ($this->result && !ereg('^SELECT', $sql)) {
                    if (ereg('^INSERT INTO', $sql)) {
                    	$this->clear_cache($table);
                        return $this->id();
                    } else {
                    	$this->clear_cache($table);
                        return $this->affected();
                    }
                } else {
                    $this->error = mysql_error($this->id);
                    echo $this->error.'<br /><br />'.$sql;
                    exit;
                }
            }
        }

        function set_cache($sql, $table, $sqlresult)
        {
            if ($GLOBALS['conf']['safe_mode']) {
                return false;
            }
            $dir = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$table;
        	$cache = $dir.DIRECTORY_SEPARATOR.md5($sql).'.dbc';
            $contents = serialize($sqlresult);
            if (!file_exists($dir)) {
            	mkdir($dir);
            }
            if ($fp = fopen($cache, 'w')) {
                fwrite($fp, $contents);
                fclose($fp);
            }
        }

        function get_cache($sql, $table)
        {
            if ($GLOBALS['conf']['safe_mode']) {
                return false;
            }
            $cache = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$table.DIRECTORY_SEPARATOR.md5($sql).'.dbc';
            if (file_exists($cache) && filemtime($cache) > (time() - $GLOBALS['conf']['expire'])) {
                if ($fp = fopen($cache, 'r')) {
                    $contents = fread($fp, filesize($cache));
                    fclose($fp);
                    $sqlresult = unserialize($contents);
                    return $sqlresult;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        function clear_cache($table ='')
        {
            if ($GLOBALS['conf']['safe_mode']) {
                return false;
            }
        	if ($table == '') {
    			$cache = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache';
        	} else {
    			$cache = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$table;
        	}
        	$GLOBALS['file']->rdelete($cache);
        }
        
        function id()
        {
            return mysql_insert_id($this->id);
        }

        function fetch($sql)
        {
            return mysql_fetch_array($sql, $this->id);
        }

        function num($sql)
        {
            return mysql_num_rows($sql, $this->id);
        }

        function affected()
        {
            return mysql_affected_rows($this->id);
        }

        function field($sql, $id)
        {
            return mysql_field_name($sql, $id);
        }

    }

?>