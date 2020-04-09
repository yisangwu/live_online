<?php

require dirname(dirname((__FILE__))) . DIRECTORY_SEPARATOR . 'helper.php';

$timestamp = $_POST['timestamp']?? 0;
$number = $_POST['number']?? 0;


if (empty($timestamp)) {
    return false;
}
$number = (int)$number;

// 保存昨天的数据
saveYesterdayData();

// 保存在线数据
saveOnline($timestamp, $number);


/*
for test

$today = time();
$last = strtotime('-7 days');

for ($i = $today; $i >= $last; $i -= 300) {
    saveOnline($i, mt_rand(200, 600));
}
 */