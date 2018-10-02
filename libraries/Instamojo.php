<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require 'instamojo/Instamojo.php';

class Instamojo {	


	public function __construct()
	{
		// get main CI object
		$this->_ci =& get_instance();
		$this->_ci->config->load('instamojo');

		$this->db = $this->_ci->config->item('mojo_db');

		if ($this->db) 
		{	
			$this->mojoTable = $this->_ci->config->item('mojo_table');	
    		$this->_ci->load->database();	
			$this->makeTable($this->mojoTable);
		}

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
		else if ($this->db) 
		{
			return $this->_ci->db->get($this->mojoTable)->result();
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

    public function pay_request( 
    							 $amount = "" , $purpose = "" , $buyer_name ="" , 
    							 $email = "" , $phone = "" ,  $send_email = 'TRUE' , 
    							 $send_sms = 'TRUE' , $repeated = 'FALSE' 
    						   )
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

		    		if ($this->db) 
					{
						$this->insertData($response);
					}

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

					if ($this->db) 
					{
						$this->insertData($response);
					}

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


    public function insertData($data)
    {
    	$transaction = [
							'phone'        => $data['phone'],
							'email'        => $data['email'],
							'buyer_name'   => $data['buyer_name'],
							'amount'       => $data['amount'],
							'purpose'      => $data['purpose'],
							'expires_at'   => $data['expires_at'],
							'status'       => $data['status'],
							'send_sms'     => $data['send_sms'],
							'send_email'   => $data['send_email'],
							'sms_status'   => $data['sms_status'],
							'email_status' => $data['email_status'],
							'shorturl'     => $data['shorturl'],
							'longurl'      => $data['longurl'],
							'redirect_url' => $data['redirect_url'],
							'webhook'      => $data['webhook'],
				'allow_repeated_payments'  => $data['allow_repeated_payments'],
							'customer_id'  => $data['customer_id'],
							'created_at'   => $data['created_at'],
							'modified_at'  => $data['modified_at']
				       ];

		$this->_ci->db->insert($this->_ci->config->item('mojo_table'), $transaction); 
    }


    public function makeTable($table)
    {
			if (!$this->_ci->db->table_exists($table)) 
			{
				$fields = array(
                        'id' => array(
                                                 'type' => 'INT',
                                                 'constraint' => 10, 
                                                 'unsigned' => TRUE,
                                                 'auto_increment' => TRUE
                                          ),
                        'phone' => array(
                                                 'type' => 'VARCHAR',
                                                 'constraint' => '25',
                                                  'null' => TRUE,
                                          ),
                        'email' => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '255',
                                                 'null' => TRUE,
                                          ),
                        'buyer_name' => array(
                                                 'type' => 'VARCHAR',
                                                 'constraint' => '255',
                                                 'null' => TRUE,
                                          ),
                        'amount'     => array(
                                                 'type' => 'DECIMAL',
                                                 'constraint' => '16,2'
                                          ),
                        'purpose'    => array(
                                                 'type' => 'TEXT',
                                                 'null' => TRUE,
                                          ),
                        'expires_at'     => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '255',
                                                 'null' => TRUE,
                                          ),
                        'status'     => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '255',
                                                 'null' => TRUE,
                                          ),
                        'send_sms'   => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '5',
                                                 'default' => 'false'
                                          ),
                        'send_email' => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '5',
                                                 'default' => 'false'
                                          ),
                        'sms_status' => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '255',
                                                 'null' => TRUE,
                                          ),
                        'email_status' => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '255',
                                                 'null' => TRUE,
                                          ),
                        'shorturl'     => array(
                                                 'type' =>'MEDIUMTEXT',
                                                 'null' => TRUE,
                                          ),
                        'longurl'      => array(
                                                 'type' =>'MEDIUMTEXT',
                                                 'null' => TRUE,
                                          ),
                        'redirect_url'=> array(
                                                 'type' =>'MEDIUMTEXT',
                                                 'null' => TRUE,
                                          ),
                        'webhook'     => array(
                                                 'type' =>'MEDIUMTEXT',
                                                 'null' => TRUE,
                                          ),
         'allow_repeated_payments'    => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '5',
                                                 'default' => 'false'
                                          ),
         			    'customer_id' => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '255',
                                                 'null' => TRUE,
                                          ),
         			    'created_at'  => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '255',
                                                 'null' => TRUE,
                                          ),
         			    'modified_at' => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '255',
                                                 'null' => TRUE,
                                          ),
                );

				$this->_ci->load->dbforge();
				$this->_ci->dbforge->add_field($fields);
				$this->_ci->dbforge->add_key('id', TRUE);
				$this->_ci->dbforge->create_table($table, TRUE);	
			}    	
    }
}

/* End of file Instamojo.php */