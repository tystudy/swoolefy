<?php
namespace Swoolefy\Core;

class Base {

	/**
	 * $server
	 * @var null
	 */
	public static $server = null;

	/**
	 * $_startTime 进程启动时间
	 * @var integer
	 */
	protected static $_startTime = 0;

	/**
	 * __construct
	 */
	public function __construct() {
		// check extensions
		self::checkVersion();
		// set timeZone
		self::setTimeZone(); 
		// record start time
		self::$_startTime = date('Y-m-d H:i:s',strtotime('now'));
		
	}
	/**
	 * setMasterProcessName设置主进程名称
	 */
	public static function setMasterProcessName($master_process_name) {

		swoole_set_process_name($master_process_name);
	}

	/**
	 * setManagerProcessName设置管理进程名称
	 */
	public static function setManagerProcessName($manager_process_name) {
		swoole_set_process_name($manager_process_name);
	}

	/**
	 * setWorkerProcessName设置worker进程名称
	 */
	public static function setWorkerProcessName($worker_process_name, $worker_id, $worker_num=1) {
		// 设置worker的进程
		if($worker_id >= $worker_num) {
            swoole_set_process_name($worker_process_name."-task".$worker_id);
        }else {
            swoole_set_process_name($worker_process_name."-worker".$worker_id);
        }

	}

	/**
	 * startInclude设置需要在workerstart启动时加载的配置文件
	 */
	public static function startInclude($includes = []) {
		if($includes) {
			foreach($includes as $filePath) {
				include_once $filePath;
			}
		}
	}

	/**
	 * 设置worker进程的工作组，默认是root
	 */
	public static function setWorkerUserGroup($worker_user=null) {
		if(!isset(static::$conf['user'])) {
			if($worker_user) {
				$userInfo = posix_getpwnam($worker_user);
				if($userInfo) {
					posix_setuid($userInfo['uid']);
					posix_setgid($userInfo['gid']);
				}
			}
		}
	}

	/**
	 * 检查是否安装基础扩展
	 */
	public static function checkVersion() {
		if(version_compare(phpversion(), '5.6.0', '<')) {
			throw new \Exception("php version must be > 5.6.0,we suggest use php7.0+ version", 1);
		}

		if(!extension_loaded('swoole')) {
			throw new \Exception("you are not install swoole extentions,please install it where version >= 1.9.17 or >=2.0.5 from https://github.com/swoole/swoole-src", 1);
		}

		if(!extension_loaded('swoole_serialize')) {
			throw new \Exception("you are not install swoole_serialize extentions,please install it where from https://github.com/swoole/swoole_serialize", 1);
		}

		if(!extension_loaded('pcntl')) {
			throw new \Exception("you are not install pcntl extentions,please install it", 1);
		}

		if(!extension_loaded('posix')) {
			throw new \Exception("you are not install posix extentions,please install it", 1);
		}

		if(!extension_loaded('zlib')) {
			throw new \Exception("you are not install zlib extentions,please install it", 1);
		}
	}

	/**
	 * getStartTime 服务启动时间
	 * @return   time
	 */
	public static function getStartTime() {
		return self::$_startTime;
	}

	/**
	 * getConfig 获取服务的全部配置
	 * @return   [type]        [description]
	 */
	public static function getConfig() {
		static::$config['setting'] = self::getSetting();
		return static::$config;
	}
	/**
	 * getSetting 获取swoole的配置项
	 * @return   array
	 */
	public static function getSetting() {
		return static::$setting;
	}

	/**
	 * setTimeZone 设置时区
	 */
	public static function setTimeZone() {
		if(isset(static::$config['time_zone'])) {
			date_default_timezone_set(static::$config['time_zone']);
		}
	}


}