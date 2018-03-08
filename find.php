<?php

class wzg
{
    public $dir_arr = [];
    public function findPhp($path)
    {

        $source = scandir($path);
        foreach ($source as $dir) {
            if ($dir == '.' || $dir == '..'){
                continue;
            }
            if (is_dir($path.$dir)) {
                $this->findPhp($path.$dir);
                continue;
            }
            $info = pathinfo($dir,PATHINFO_EXTENSION);
            if ($info == 'php') {
                $this->dir_arr[] = $dir;
            }

        }
    }
    public function run(){
        return $this->dir_arr;
    }
}

$wzg = new wzg();
$wzg->findPhp('./');
$arr = $wzg->run();
//var_dump($arr);die();
$str = '';
//注释匹配
$regex = '/\/\*(.|\n)\*\//';
foreach ($arr as $item) {
    $str .= file_get_contents($item);
}
var_dump(preg_grep($regex,$str));