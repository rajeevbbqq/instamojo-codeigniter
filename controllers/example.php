<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Controller {
	
	public function index()
	{
		$this->load->library('instamojo');

		$result = $this->instamojo->all_payment_request();

		print_r($result);
	}
}

/* End of file example.php */
/* Location: ./application/controllers/example.php */