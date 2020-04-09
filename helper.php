<?php

/**
 * online helper
 */

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('DATA') or define('DATA', dirname(__FILE__) . DS . 'data');

date_default_timezone_set("Asia/Shanghai");

/**
 * 日期校验
 *
 * @param  string $date 日期
 * @return string
 */
function checkDateToTimestamp(string $date = '') {
	$date = trim($date);
	if (empty($date)) {
		return '';
	}
	$timestamp = strtotime($date);
	if (empty($timestamp)) {
		return false;
	}

	return $timestamp;
}

/**
 * 每天的在线数据写入一个文件
 *
 * @param  string $date 日期
 * @return string 文件路径
 */
function genDayilyFile(string $date = '') {
	$timestamp = checkDateToTimestamp($date);
	if (empty($timestamp)) {
		return '';
	}

	$dir = DATA . DS . date('Y-m', $timestamp);
	if (!is_dir($dir)) {
		mkdir($dir, 0755, true);
	}

	return $dir . DS . date('Y-m-d', $timestamp) . '.log';
}

/**
 * 保存昨天的在线数据到文件中
 * @return boolean
 */
function saveYesterdayData() {

	$date = date('Y-m-d',strtotime('yesterday'));

	$file = genDayilyFile($date);
	var_dump($file);
	if (empty($file)) {
		return false;
	}
	if (file_exists($file) && is_file($file)) {
		return true;
	}

	$online_yesterday = getOnline($date);
	if (empty($online_yesterday) || !is_array($online_yesterday)) {
		return false;
	}

	return file_put_contents($file, json_encode($online_yesterday));
}


/**
 * 在线数据的redis缓存key
 *
 * @param  string $date 日期 y-m-d
 * @return string
 */
function onlineRedisKey(string $date = '') {
	$timestamp = checkDateToTimestamp($date);
	if (empty($timestamp)) {
		return false;
	}
	return 'ONLINE_' . date('Ymd', $timestamp);
}

/**
 * 保存在线数据
 *
 * @param  int|integer  时间戳
 * @param  int|integer  在线人数
 * @return boolean
 */
function saveOnline(int $timestamp = 0, int $number = 0) {

	if (empty($timestamp)) {
		return false;
	}
	$time_point = date('H:i', $timestamp);

	if (empty($time_point)) {
		return false;
	}

	$redis = new Redis();
	$redis->connect('127.0.0.1', 6379);

	return $redis->hSet(onlineRedisKey(date('Y-m-d', $timestamp)), $time_point, (int) $number);
}

/**
 * 从redis中读取某一天的在线数据
 *
 * @param  string $date 日期 y-m-d
 * @return []
 */
function getOnline(string $date = '') {
	$timestamp = checkDateToTimestamp($date);
	if (empty($timestamp)) {
		return [];
	}
	$redis = new Redis();
	$redis->connect('127.0.0.1', 6379);
	$online_data = $redis->hGetAll(onlineRedisKey(date('Y-m-d', $timestamp)));
	if (empty($online_data) || !is_array($online_data)) {
		return [];
	}
	$ret = [];
	foreach ($online_data as $hi => $value) {
		$ret[$hi] = (int)$value;
	}
	return $ret;
}
