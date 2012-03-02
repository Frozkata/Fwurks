<?php

abstract class BaseController
{
	protected $__request;
	protected $__paths;
	
	protected $__view = null;
	
	private $__before_filters		= array();
	private $__after_filters		= array();
	
	public $__javascripts			= array();
	public $__styles				= array();
	
	
	
	public final static function run($controller, RouterRequest $request)
	{
		$controller = new $controller($request);
		
		$content = $controller->__executeController();
		
		//de(123);
		
		return self::{'__render_' . $request->response_type}($controller, $content);
		
	}
	
	public final static function __render_($controllers, $data)
	{
		return $data;
	}
	
	public final static function __render_html($controller, $data)
	{
		
		// Set view file for loading
		d($controller);
		de(get_object_vars($controller));
		
		$template = new Template();
		$template->assign(get_object_vars($controller));
		
		
		return $template->fetch($controller->__view().'.tpl');
	}
	
	public final static function __render_json($controllers, $data)
	{
		return json_encode(get_object_vars($data));
	}
	
	
	public final function __construct(RouterRequest $request)
	{
		$this->__request = $request;
		
		$res		= '/' . Dispatcher::$folder_resources . '/' . Application_Config::$folder_resources . '/';
		$res_atom	= $res . Atom_Config::$folder_resources . '/';
		
		$this->__paths = array
		(
			'styles'		=> $res_atom . 'styles/',
			'javascripts'	=> $res_atom . 'javascripts/',
		
			'public'		=> $res,
			'files'			=> $res . 'files/'
		);
		
		/*
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if(strchr($user_agent, 'MSIE'))		{ $this->__browser = 'msie'; }
		if(strchr($user_agent, 'Firefox'))	{ $this->__browser = 'firefox'; }
		if(strchr($user_agent, 'Safari'))	{ $this->__browser = 'safari'; }
		if(strchr($user_agent, 'Opera'))	{ $this->__browser = 'opera'; }
		*/
		
		$this->__initialize();
	}
	
	protected function __initialize(){}
	
	public final function __executeController()
	{
		/*
		if(Registry::$globals)
		{
			$this->__globals = Registry::$globals;
		}
		else
		{
			$this->__globals = Localizer::getGlobals();
			Registry::$globals = $this->__globals;
		}
		
		$this->__labels = Localizer::load($this->__controller);
		Registry::$labels = $this->__labels;
		*/
		
		foreach($this->__before_filters as $filter){ $this->$filter(); }
		
		$content = $this->{'action__' . $this->__request->route->action}();
		
		foreach($this->__after_filters as $filter){ $this->$filter(); }
		
		return $content;
	}
	
	
	protected final function __before_filter($filter)
	{
		$this->__before_filters = array_merge($this->__before_filters, func_get_args());
	}
	
	protected final function __after_filter($filter)
	{
		$this->__after_filters = array_merge($this->__after_filters, func_get_args());
	}
	
	protected final function __javascript()
	{
		$this->__javascripts = array_merge($this->__javascripts, func_get_args());
	}
	
	protected final function __style()
	{
		$this->__styles = array_merge($this->__styles, func_get_args());
	}
	
	protected final function __library($name)
	{
		require_once Paths_Config::$atom_library . $name . '.php';
	}
	
	public final function __view_file()
	{
		return $controller->__request->route->controller . '/' . ($view === null ? $controller->__request->route->action : $this->__view);
	}
}


?>
