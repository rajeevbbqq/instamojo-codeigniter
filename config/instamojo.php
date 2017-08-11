<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* instamojo payment API v1 library for CodeIgniter
*
* @license Creative Commons Attribution 3.0 <http://creativecommons.org/licenses/by/3.0/>
* @version 1.0
* @author Rajeev bbqq <http://bbqq.me>
* @copyright Copyright (c) 2017, Rajeev bbqq
*/

/*
|--------------------------------------------------------------------------
| Mode
|--------------------------------------------------------------------------
|
| $config['mojo_mode'] = 'sandbox'; for testing
| $config['mojo_mode'] = 'live'   ; for production
|
*/


$config['mojo_mode']  = 'sandbox' ;


/*
|--------------------------------------------------------------------------
| API_KEY
|--------------------------------------------------------------------------
| API_KEY are different for test and production !
| $config['mojo_apikey'] = '650f7eed3d900273d6fafd635a';
|
*/

$config['mojo_apikey'] = 'f941c38e639bad1f4022423e95dbfa3d' ;


/*
|--------------------------------------------------------------------------
| AUTH_TOKEN
|--------------------------------------------------------------------------
| AUTH_TOKEN are different for test and production !
| $config['mojo_token'] = '650f7eed3d900273d6fafd635a';
|
*/


$config['mojo_token']  = 'c2d52ccf69f6973d9499a69bb03d19d6' ;


/*
|--------------------------------------------------------------------------
| REDIRECT_URL
|--------------------------------------------------------------------------
| AUTH_TOKEN are different for test and production !
| $config['mojo_url'] = 'https://github.com/Instamojo/instamojo-php';
|
*/

$config['mojo_url'] = 'https://github.com/Instamojo/instamojo-php';


/* End of file instamojo.php */
/* Location: ./application/config/instamojo.php */