<?php
/**
 * Created by PhpStorm.
 * User: keller
 * Date: 2018/9/28
 * Time: 14:25
 */

namespace keller31\MonologAliyunLog;

use Monolog\Handler\AbstractProcessingHandler;

class AliyunLogHandler extends AbstractProcessingHandler
{
	public $sls_client = '';
	public $endpoint = '';
	public $accessKeyId = '';
	public $accessKey = '';
	public $topic = '';
	public $source = '';
	public $project = '';
	public $logstore = '';

	public function __construct(array $sls_config = array())
	{
		parent::__construct();

		$this->endpoint    = $sls_config['endpoint'];
		$this->accessKeyId = $sls_config['accessKeyId'];
		$this->accessKey   = $sls_config['accessKey'];
		$this->topic       = $sls_config['topic'];
		$this->source      = $sls_config['source'];
		$this->project     = $sls_config['project'];
		$this->logstore    = $sls_config['logstore'];

		$this->sls_client = new \Aliyun_Log_Client($this->endpoint, $this->accessKeyId, $this->accessKey);
	}

	/**
	 * write log
	 * @param array $record
	 */
	public function write(array $record)
	{
		$contents['channel'] = $record['channel'];
		$contents['level']   = $record['level_name'];
		$contents['message'] = $record['message'];
		$contents['context'] = json_encode($record['context'], 256);
		$contents['extra']   = json_encode($record['extra'], 256);
		//var_dump($contents);
		$log[] = new \Aliyun_Log_Models_LogItem(time(), $contents);

		$logsRequest = new \Aliyun_Log_Models_PutLogsRequest($this->project, $this->logstore, $this->topic, $this->source, $log);


		try {
			$this->sls_client->putLogs($logsRequest);
		} catch (\Exception $e) {
//			var_dump($e->getMessage());
		}

	}

}