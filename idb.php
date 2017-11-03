<?php
include 'config.php';
function idb($sql, $debug = false)
{
global $config;

$db = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
  mysqli_set_charset($db, 'UTF8');
    if ($debug == true)
        echo '<BR />debug it : ' . $sql . '<BR />';
    if (strpos(strtoupper($sql), 'SELECT') === false)
       { $result = $db->query($sql);}
    else {
if(!$result = $db->query($sql)){
    die('There was an error running the query [' . $db->error . ']');
}
        $temp   = explode(" ", $sql);
        $fields = $temp[1];
        $fields = str_replace('`', '', $fields);
        $table  = $temp[3];
        $table  = str_replace('`', '', $table);
        if ($fields == '*') {
            $finfo  = mysqli_fetch_fields($result);
foreach ($finfo as $val) $fields_name[] =  $val->name;
        } else {
            if (strpos($fields, ',') !== false)
                $fields_name = explode(',', $fields);
            else
                $fields_name = $fields;
        }
        
        $line = 0;
        if (strpos(strtoupper($sql), 'LIMIT 1;') !== false)
            $line = 1;
        $vi = 1;
        if (is_array($fields_name))
            $fields_num = count($fields_name);
        else
            $fields_num = 0;
        if ($line == 0)
           while($row = $result->fetch_assoc()){
                if ($fields_num == 0)
                    $data[$fields_name][$vi] = $row[$fields_name];
                else
                    for ($fi = 0; $fi < $fields_num; $fi++) {
                        $current_field             = $fields_name[$fi];
                        $data[$vi][$current_field] = $row[$current_field];
                    }
                $vi++;
            } else if ($line == 1)
            if ($fields_num == 0) {
                $current_field = $fields_name;
                if ($row = $result->fetch_assoc())
                    $data = $row[$current_field];
            } else
                 while($row = $result->fetch_assoc())
                    for ($fi = 0; $fi < $fields_num; $fi++) {
                        $current_field        = $fields_name[$fi];
                        $data[$current_field] = $row[$current_field];
                    }
    }
   $db->close();
    if (isset($data))
        return $data;
    else return false;
}
function db_insert($table, $fields, $values)
{
global $config;
    if (is_array($fields)) {
        $count = count($fields) - 1;
		$fields_query = ''; $values_query = '';
        for ($i = 0; $i <= $count; $i++) {
            $field = '`' . $fields[$i] . '`';
            $value = db_escape($values[$i]);
            $fields_query .= $field;
            if (is_numeric($field))
                $values_query .= $value;
            else
                $values_query .= "'" . $value . "'";
            if ($count != $i) {
                $fields_query .= ',';
                $values_query .= ',';
            }
        }
    } else {
        $field_query = $field;
        $value_query = db_escape($values);
    }
    $sql = "INSERT into `$table` ($fields_query) VALUES ($values_query)";
    idb($sql);
}
function db_update($table, $fields, $values, $where = '')
{
global $config;
    if (is_array($fields)) {
        $count = count($fields) - 1;
        for ($i = 0; $i <= $count; $i++) {
            $field        = $fields[$i];
            $value        = db_escape($values[$i]);
            $fields_query = $field;
            $part_query .= '`' . $field . '`';
            if (is_numeric($field))
                $part_query .= ' = ' . $value;
            else
                $part_query .= " ='" . $value . "'";
            if ($count != $i)
                $part_query .= ' ,';
        }
    } else {
        $part_query = '`' . $fields . '`';
        if (is_numeric($values))
            $part_query .= ' = ' . $values;
        else
            $part_query .= " ='" . db_escape($values) . "'";
    }
    $sql = "UPDATE `$table` SET $part_query $where";
    idb($sql);
}
function db_delete($table, $field, $is)
{
global $config;
    $sql = "DELETE FROM $table where $field = '" . db_escape($is) . "'";
    idb($sql);
}
function db_select($table, $fields = "*", $arg1 = '', $arg2 = '', $arg3 = '')
{
global $config;
    if (!is_array($fields))
        if ($fields == '*')
            $fields_query = $fields;
        else
            $fields_query = '`' . $fields . '`';
    else {
        $count = count($fields) - 1;
        for ($i = 0; $i <= $count; $i++) {
            $fields_query .= '`' . $fields[$i] . '`';
            if ($count != $i)
                $fields_query .= ',';
        }
    }
    $sql = "SELECT $fields_query FROM `$table` $arg1 $arg2 $arg3";
    return idb($sql);
}
function db_select_one($table, $fields = "*", $arg1 = '', $arg2 = '')
{
global $config;
    if (!is_array($fields))
        if ($fields == '*')
            $fields_query = $fields;
        else
            $fields_query = '`' . $fields . '`';
    else {
        $count = count($fields) - 1;
        for ($i = 0; $i <= $count; $i++) {
            $fields_query .= '`' . $fields[$i] . '`';
            if ($count != $i)
                $fields_query .= ',';
        }
    }
    $sql = "SELECT $fields_query FROM `$table` $arg1 $arg2 LIMIT 1 ";
    return idb($sql);
}


function db_rows_num($sql)
{
    global $config;
   $con = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);
  mysqli_set_charset($con, 'UTF8');
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

if ($result=mysqli_query($con,$sql))
  {

  return mysqli_num_rows($result);

  }

}


function escape_string($value)
{
global $config;
$mydb = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);

if($mydb->connect_errno > 0){
    die('Unable to connect to database [' . $mydb->connect_error . ']');
}
  mysqli_set_charset($mydb, 'UTF8');
return $mydb->real_escape_string($value);
}
?>
