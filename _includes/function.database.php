<?php

// Example
//$sql_command = new sql_db();
//$sql_command->connect($database_host,$database_name,$database_username,$database_password);
//$sql_command->close();


class sql_db {

		// Connect Database
		function connect($host,$db_name,$username,$password) {
		mysql_connect($host,$username,$password) or die(mysql_error());
		mysql_select_db($db_name) or die( "Unable to select database") or die(mysql_error());
		}
		
		
		// Insert Database
		function insert($table,$names,$values) {
		mysql_query("INSERT INTO $table ($names) VALUES ($values)") or die(mysql_error());
		}

		
		// Select Database Information
		function select($table,$before,$after) {
		$sqlinfo = mysql_query("SELECT $before FROM $table $after") or die(mysql_error());
		return $sqlinfo; 
		}
		
		
		// Return SQL Information
		function result($result) {
		$out = array();
		$out = @mysql_fetch_row($result);
		return $out;
		}


		function results($result) {
		$out = array();
		while ($row =  @mysql_fetch_row($result)) {
		$out[] = $row;
		}
		return $out;
		}

		// Update Database Information
		function update($table,$set,$where) {
		if(strlen($where)>0) { $extra_query = "WHERE $where"; } else { $extra_query = ""; }
		mysql_query("UPDATE $table SET $set $extra_query") or die(mysql_error());
		}


		// Delete Database Information
		function delete($table,$where) {
		if(strlen($where)>0) { $extra_query = "WHERE $where"; } else { $extra_query = ""; }
		mysql_query("DELETE FROM $table $extra_query") or die(mysql_error());
		}


		// Count Database Rows
		function count_rows($table,$field,$where) {
		if(strlen($field)>0) { $extra_query = "$field"; } else { $extra_query = "*"; }
		if(strlen($where)>0) { $extra_query2 = "WHERE $where"; } else { $extra_query2 = ""; }
		$docount = @mysql_query("SELECT $extra_query FROM $table $extra_query2") or die(mysql_error());
        $numberrows = @mysql_num_rows($docount);
		return $numberrows;
		}
		
		function count_nrow($table,$field,$where) {
		if(strlen($where)>0) { $whereq = "WHERE $where"; } else { $whereq = ""; }
		$rownc = mysql_query("SELECT COUNT($field) as count FROM $table $whereq") or die(mysql_error());
	    $rownc1 = mysql_fetch_array($rownc);
	    return $rownc1['count'];
		}
		
		// Get max id number	
	    function maxid($table,$field) {
	    $maxid2 = mysql_query("SELECT MAX($field) as maxid FROM $table") or die(mysql_error());
	    $maxid3 = mysql_fetch_array($maxid2);
	    return $maxid3['maxid'];
		}


		// Close Connection
     	function close() {
		mysql_close() or die(mysql_error());
		exit();
		}

}

?>