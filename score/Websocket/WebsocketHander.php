<?php
namespace Swoolefy\Websocket;

use Swoolefy\Core\Swfy;
use Swoolefy\Core\Swoole;
use Swoolefy\Core\HanderInterface;

class WebsocketHander extends Swoole implements HanderInterface {
	/**
	 * __construct 初始化
	 * @param    array  $config
	 */
	public function __construct(array $config=[]) {
		parent::__construct($config);
	}

	/**
	 * init 当执行run方法时,首先会执行init->bootstrap
	 * @param  mixed  $recv
	 * @return void       
	 */
	public function init($recv) {}

	/**
	 * bootstrap 当执行run方法时,首先会执行init->bootstrap
	 * @param  mixed  $recv
	 * @return void
	 */
	public function bootstrap($recv) {}


	/**
	 * run 完成初始化后,开始路由匹配和创建访问实例
	 * @param  int   $fd
	 * @param  mixed $recv
	 * @return mixed
	 */
	public function run($fd, $recv) {
		// 必须要执行父类的run方法
		parent::run($fd, $recv);
		var_dump($recv);
		// if(is_array($recv) && count($recv) == 2) {
		// 	list($callable, $params) = $recv;
		// }
		// if($callable && $params) {
		// 	$Dispatch = new RpcDispatch($callable, $params);
		// 	$Dispatch->dispatch();
		// }

		// 必须执行
		parent::end();
		return;
	}
}
