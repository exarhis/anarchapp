<!doctype html>
<head>
<meta charset="utf-8">
<title>idb PHP MySql Framework</title>
</head>
<body>
<pre>
db table : table
            field 1 : id
            field 2 : name
            field 3 : telephone
query : $one = idb("SELECT `name` FROM `table` WHERE `id` = 1;");  returns "myname1";

query : $row = idb("SELECT * FROM `table` WHERE `id` = 1;"); returns array( 'id' = 1, 'name' = 'myname1' , 'telephone' = '01003345355435');

query : table = idb("SELECT * FROM `table`;"); returns array( [1] -> array( 'id' = 1, 'name' = 'myname1' , 'telephone' = '01003345355435') 
                                                              [2] -> array( 'id' = 2, 'name' = 'myname2' , 'telephone' = '01032325425665');
