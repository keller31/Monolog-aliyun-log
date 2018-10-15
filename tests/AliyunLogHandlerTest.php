<?php
/**
 * Created by PhpStorm.
 * User: keller
 * Date: 2018/9/28
 * Time: 14:47
 */

namespace keller31\MonologAliyunLog\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

define("ROOT_PATH", dirname(__DIR__) . "/");

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use keller31\MonologAliyunLog\AliyunLogHandler;

use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
	public function testPushAndPop()
	{

		for ($i = 1;$i<=10;$i++){
			$this->assertEquals(true, $this->Log()->error('hello'));
		}
	}

	public function Log()
	{
		$log = new Logger('Tester');
		$log->pushHandler(new StreamHandler(ROOT_PATH . 'storage/logs/app.log', Logger::WARNING));

		$aliyun_log_config['endpoint']    = '';
		$aliyun_log_config['accessKeyId'] = '';
		$aliyun_log_config['accessKey']   = '';
		$aliyun_log_config['project']     = '';
		$aliyun_log_config['logstore']    = '';
		$aliyun_log_config['topic']       = '';
		$aliyun_log_config['source']      = '';

		$log->pushHandler(new AliyunLogHandler($aliyun_log_config));

		$log->pushProcessor(new IntrospectionProcessor());
		$log->pushProcessor(new WebProcessor());
		$log->pushProcessor(new MemoryUsageProcessor());

		$log->error("Error");
		return $log;
	}
}
