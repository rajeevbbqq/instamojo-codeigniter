<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'instamojo/Instamojo.php';

class Instamojo {	


	public function __construct()
	{
		// get main CI object
		$this->_ci =& get_instance();
		$this->_ci->config->load('instamojo');
	}

	/*
	 *
	 * General Functions of Instamojo
	 *
	*/


	/*
	 *
	 * Returns all payment request details.
	 *
	*/
	public function all_payment_request()
	{
		$mode   = strtolower($this->_ci->config->item('mojo_mode'));
		$apikey = $this->_ci->config->item('mojo_apikey');
		$token  = $this->_ci->config->item('mojo_token') ;

		if (strlen($apikey) <= 0)
		{
			return "Please set API";
		}
		elseif (strlen($token) <= 0)
		{
			return "Please set Auth_Token";
		}
		elseif ($mode == 'sandbox')
		{
			$api = new Instamojo\Instamojo($apikey, $token, 'https://test.instamojo.com/api/1.1/');
				try 
				{
		    		$response = $api->paymentRequestsList();
		    		return $response;
				}
				catch (Exception $e) 
				{
				    return $e->getMessage();
				}
		}
		elseif ($mode == 'live')
		{
			$api = new Instamojo\Instamojo($apikey, $token);
				try 
				{
		    		$response = $api->paymentRequestsList();
		    		return $response;
				}
				catch (Exception $e) 
				{
				    return $e->getMessage();
				}
		}
		else
		{
			return "Please set Mode";
		}			
		
	}

	/**
    * @param  $amount is ( required ).
    * @param  $purpose is ( required ).
    * @param  $email is ( required ).
    * @param  $phone is ( required ).
    * @param  $buyer_name.
    * @param  $email.
    * @param  $phone.
    * @param  $repeated is allow repeated payments ( default is false ) .
    * @return array single PaymentRequest object.
    */

    public function pay_request( $amount = "" , $purpose = "" , $buyer_name ="" , $email = "" , $phone = "" ,
     $send_email = 'TRUE' , $send_sms = 'TRUE' , $repeated = 'FALSE' )
    {
    	$mode   = strtolower($this->_ci->config->item('mojo_mode'));
		$apikey = $this->_ci->config->item('mojo_apikey');
		$token  = $this->_ci->config->item('mojo_token') ;
		$url    = $this->_ci->config->item('mojo_url')   ;

		if (strlen($apikey) <= 0)
		{
			return "Please set API";
		}
		elseif (strlen($token) <= 0)
		{
			return "Please set Auth_Token";
		}
		elseif (strlen($amount) <= 0)
		{
			return "Amount required";
		}
		elseif ($amount < 10)
		{
			return "Minimum amount is Rs. 10";
		}
		elseif (strlen($purpose) <= 0)
		{
			return "Please mention purpose";
		}
		elseif (strlen($url) <=0) 
		{
			return "Please set redirect url";
		}
		elseif ($mode == 'sandbox')
		{			
			$array = array('purpose' => $purpose, 'amount' => $amount, "redirect_url" => $url ,
			"buyer_name" => $buyer_name , "email" => $email , "send_email" => $send_email , 
			"phone" => $phone ,"send_sms" => $send_sms, "allow_repeated_payments" => $repeated,);
				$api = new Instamojo\Instamojo($apikey, $token, 'https://test.instamojo.com/api/1.1/');
				try 
				{
		    		$response = $api->paymentRequestCreate($array);
		    		return $response;
				}
				catch (Exception $e) 
				{
				    return $e->getMessage();
				}		
		}
		elseif ($mode == 'live')
		{				
				$array = array('purpose' => $purpose, 'amount' => $amount, "redirect_url" => $url );
				$api = new Instamojo\Instamojo($apikey, $token);
				try 
				{
		    		$response = $api->paymentRequestCreate($array);
		    		return $response;
				}
				catch (Exception $e) 
				{
				    return $e->getMessage();
				}			
		}
		else
		{
			return "Please set Mode";
		}	
    }

    /**
    * @param  $reqid ( " Payment request id " required ).
    * @return returns status of the payment id
    */


    public function status($reqid = '')
    {
    	$mode   = strtolower($this->_ci->config->item('mojo_mode'));
		$apikey = $this->_ci->config->item('mojo_apikey');
		$token  = $this->_ci->config->item('mojo_token') ;

		if (strlen($apikey) <= 0)
		{
			return "Please set API";
		}
		elseif (strlen($token) <= 0)
		{
			return "Please set Auth_Token";
		}
		elseif (strlen($reqid) <= 0)
		{
			return "Payment Request id required";
		}
		elseif ($mode == 'sandbox')
		{
			$api = new Instamojo\Instamojo($apikey, $token, 'https://test.instamojo.com/api/1.1/');
				try 
				{
		    		return $api->paymentRequestStatus($reqid);
				}
				catch (Exception $e) 
				{
				    return $e->getMessage();
				}
		}
		elseif ($mode == 'live')
		{
			$api = new Instamojo\Instamojo($apikey, $token);
				try 
				{
		    		return $api->paymentRequestStatus($reqid);
				}
				catch (Exception $e) 
				{
				    return $e->getMessage();
				}
		}
		else
		{
			return "Please set Mode";
		}
    }




    /**
    * @param  $reqid ( " Payment request id " required ).
    * @param  $payid ( " Payment id "eg.MOJOXXXX required ).
    * @return returns status of the payment id
    */

    public function payment_status($reqid , $payid = '')
    {
    	$mode   = strtolower($this->_ci->config->item('mojo_mode'));
		$apikey = $this->_ci->config->item('mojo_apikey');
		$token  = $this->_ci->config->item('mojo_token') ;

		if (strlen($apikey) <= 0)
		{
			return "Please set API";
		}
		elseif (strlen($token) <= 0)
		{
			return "Please set Auth_Token";
		}
		elseif (strlen($reqid) <= 0)
		{
			return "Payment Request id required";
		}
		elseif (strlen($payid) <= 0)
		{
			return "Payment id required";
		}
		elseif ($mode == 'sandbox')
		{
			$api = new Instamojo\Instamojo($apikey, $token, 'https://test.instamojo.com/api/1.1/');
				try 
				{
		    		return $api->paymentRequestStatus($reqid , $payid);
				}
				catch (Exception $e) 
				{
				    return $e->getMessage();
				}
		}
		elseif ($mode == 'live')
		{
			$api = new Instamojo\Instamojo($apikey, $token);
				try 
				{
		    		return $api->paymentRequestStatus($reqid , $payid);
				}
				catch (Exception $e) 
				{
				    return $e->getMessage();
				}
		}
		else
		{
			return "Please set Mode";
		}
    }
}

/* End of file Instamojo.php */