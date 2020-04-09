<!DOCTYPE html>
<html>
<head>
	<title>实时在线 baidu123</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta name="description" content="baidu123 online"/>
	<meta name="keywords" content="baidu123 online"/>
	<meta name="author" content="depakin"/>
	<meta http-equiv="refresh" content="368952" />
	<script type="text/javascript" src="js/echarts.min.js"></script>
	<script type="text/javascript" src="js/shine.js"></script>
	<style type="text/css">
		h1#title{text-align: center;color: #0098D9}
		div#nowline{font-size: 60px;color:#FF9655;text-align: center;}
		div#charts{width:1280px; height: 600px; margin: 0 auto}
		footer{padding-top:50px; font-size: 12px; color: #5A5858;text-align: center;}
	</style>
</head>
<?php
require dirname((__FILE__)) . DIRECTORY_SEPARATOR . 'helper.php';

// 图表标题
$title = date('Y-m-d') . ' 实时在线';
// 时间点
$yesterday = strtotime('yesterday');
$today = strtotime('today');
for ($i = $yesterday; $i < $today; $i += 300) {
	$time_point[] = date('H:i', $i);
}
$time_point = json_encode($time_point);

$date_days = [
	date('Y-m-d', $today),
	date('Y-m-d', strtotime('-1 days')),
	date('Y-m-d', strtotime('-2 days')),
	date('Y-m-d', strtotime('-7 days')),
];

$legend = [];
$series = [];

$a = range(120, 600);
$color = ['#FF9655', '#058DC7', '#32CD32', '#DDDF00'];

foreach ($date_days as $k => $date) {

	$online_data = getOnline($date);
	if (empty($online_data) || !is_array($online_data)) {
		continue;
	}

	$legend[$k] = $date;
	$series[$k]['name'] = $date;
	$series[$k]['type'] = 'line';
	$series[$k]['stack'] = '总量';
	$series[$k]['data'] = array_values($online_data);
	$series[$k]['itemStyle']['normal']['lineStyle']['color'] = $color[$k];
	$series[$k]['itemStyle']['normal']['color'] = $color[$k];
}


$legend = json_encode($legend);
$series = json_encode($series);
?>

<body style="margin:auto">
	<h1 id="title"><?php echo $title; ?></h1>
	<div id="nowline">1234</div>
	<div id="charts">

	</div>
	<script>
	var chart = echarts.init(document.getElementById('charts'), 'infographic');

	chart.setOption( {
	    title: {
	        text:"<?php echo $title; ?>"
	    },
	    tooltip: {
	        trigger: 'axis'
	    },
	    legend: {
	        data: <?php echo $legend; ?>,
	        bottom:'bottom',
	    },
	    grid: {
	        left: '3%',
	        right: '4%',
	        bottom: '10%',
	        containLabel: true
	    },
	    toolbox: {
	        feature: {
	            saveAsImage: {}
	        }
	    },
	    xAxis: {
	        type: 'category',
	        boundaryGap: false,
	        data: <?php echo $time_point; ?>
	    },
	    yAxis: {
	        type: 'value'
	    },
	    series: <?php echo $series; ?>
	});
</script>
<footer>Copyright©<?php echo date('Y'); ?> baidu123.com All Rights Reserved.</footer>
</body>
</html>