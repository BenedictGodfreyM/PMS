<?php

/**
* Bootstrap: Gets the request and redirect to the right controller
*/
class Bootstrap {

	private $request;
	private $params;
	private $controller = null;

	public function __construct(){

		$this->request = new Request();
		$this->params = Request::getParams();

		$controller_file = '../app/controllers/'.$this->params['controller'].'.php';
		if(file_exists($controller_file)){
			require_once $controller_file;
			$this->controller = new $this->params['controller']();

			if(!empty($this->params["method"])){
				if(method_exists($this->controller, $this->params["method"])){
					call_user_func_array(
						array($this->controller, $this->params["method"]), $this->params['params']
					);
				}else{
					require_once '../app/controllers/Error404.php';
					$this->controller = new Error404;
					$this->params["method"] = 'index';
					$this->params['params'] = array();
					call_user_func_array(
						array($this->controller, $this->params["method"]), $this->params['params']
					);
				}
			}
		}else{
			require_once '../app/controllers/Error404.php';
			$this->controller = new Error404;
			$this->params["method"] = 'index';
			$this->params['params'] = array();
			call_user_func_array(
				array($this->controller, $this->params["method"]), $this->params['params']
			);
		}

	}

}

?>
