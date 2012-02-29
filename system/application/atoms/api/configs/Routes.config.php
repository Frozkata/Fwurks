<?php

// literal
// :variable
// :?optional
// :variable~regexp~
// :?optional~regexp~

/**
 * Routes Map
 */
class Routes_Config
{
	public $custom		= array( 'a/:controller/:action/:?ddd~\d{4}-\d{4}~/'		, array('action' => 'asd_$action') );
	
	
	public $default		= array( ':?controller/:?action/:?id' 						, array('controller' => 'home', 'action' => 'index') );
}

?>