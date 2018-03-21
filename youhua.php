<?php
$servername = "localhost";
$username = "root";
$password = "root";
try{
	$connect = new PDO("mysql:host=$servername;dbname=practice", $username, $password);
    	$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    	   $connect->beginTransaction();
    	   $areaid = 1;
    	   $postid = 1;
	   for ($i = 1; $i < 3000000; ++$i){
	       $age = rand(18,120);
	       $sql_apply= "insert into apply (age,update_time) value (".$age.",".time().")";
	       $connect->exec($sql_apply);
	       $rand_a = rand(2,4);
	       $rand_p= rand(3,5);
	       for ($x=0; $x < $rand_a; $x++) { 
	       	$sql_area  = "insert into area(applyid,name) value(".$i.",'hahahaha')";
	       	$connect->exec($sql_area);
	       	for ($y=0; $y < $rand_p; $y++) { 
	       		$num = $postid+1+$y;
		       	$sql_relation  = "insert into relation(applyid,areaid,postid,update_time) value(".$i.",".$areaid.",".$num.",".time().")";
		       	$connect->exec($sql_relation);
	      	 }
	      	 ++$areaid;
	       }
	       
	       for ($y=0; $y < $rand_p; $y++) { 
	       	$sql_post  = "insert into post(applyid,name) value(".$i.",'lalalalla')";
	       	$connect->exec($sql_post);
		 ++$postid;
	       }
	   }
	   $connect->commit();

}catch(PDOException $e){
	echo $e->getMessage();
}