<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 18-3-11
 * Time: 上午11:43
 */

$page = isset($_GET['page'])?$_GET['page']:1;
$limit = isset($_GET['limit'])?$_GET['limit']:10;
$servername = "localhost";
$username = "root";
$password = "root";

$start_time = time();
try {
    $connect = new PDO("mysql:host=$servername;dbname=practice", $username, $password);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//    $connect->beginTransaction();
//    for ($i = 1; $i < 1000000; ++$i){
//        $score = $i/10;
//        $sql = "insert into user (username,age,score) value ('小明','$i','$score')";
//        $connect->exec($sql);
//    }
//    $connect->commit();

    //10000条数据的偏移量时使用limit，
    //大于10000条之后使用子查询
    $offset = ($page-1) * $limit;
    if ($offset > 10000){
        $sql = "select * from user where id >= ( select id from user order by id limit ".$offset.",1)  limit ".$limit;
    }else{
        $sql = "select * from user order by id limit ".$offset.","."$limit";
    }
    $result = $connect->prepare($sql);
    $result->execute();
    $content = "<div><ul>";
    while($res = $result->fetch(PDO::FETCH_ASSOC)){
        $content .= "<li>".$res['id']."<span>".$res['username']."</span><span style='margin-left: 20px;'>".$res['age']."</span><span style='margin-left: 20px'>".$res['score']."</span></li>";
    }
    $content .= "</ul></div>";

    //查询数量
    $sql = "select count(*) from user";
    $count = $connect->prepare($sql);
    //可以传入变量运行sql
    $count->execute();
    while ($res = $count->fetchAll(PDO::FETCH_ASSOC)){
        $num = $res[0]['count(*)'];
    }
//    print_r($num);
    $content .= "<div class='page'>";
    $p_num = (int)($num / $limit) + 1;
//    print_r($p_num);
    if ($page != 1) {
        $content .= "<a style='margin-left:20px;' href='page.php?page=".($page - 1)."&limit=".$limits."'>上一页</a>";
    }
    for ($i = $page; $i < $page + 10; ++$i){
        if ($i-5  > $p_num){
            continue;
        }
        if ($i - 5 < 1){
            continue;
        }
        $content .= "<a style='margin-left:20px;' href='page.php?page=".($i-5)."&limit=".$limit."'>".($i-5)."</a>";
    }
    if ($page != $p_num) {
        $content .= "<a style='margin-left:20px;' href='page.php?page=".($page + 1)."&limit=".$limit."'>下一页</a>";
    }
    echo $content;
}
catch(PDOException $e)
{
    echo $e->getMessage();
}
$connect = null;
$end_time = time();
echo "<p>耗时:".($end_time-$start_time)."</p>";
echo "<span>选择显示条数:</span>";
for ($i = 10; $i < 70; $i += 10) {
    echo "<a style='margin-left:20px;' href='page.php?limit=".$i."'>".$i."</a>";

}
