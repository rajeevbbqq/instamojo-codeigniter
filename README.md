# Instamojo Payment api for codeigniter
Instamojo Payment api written for Codeigniter ( Tested with 2.x )

# Database support 

Supports database integration by setting `config/instamojo.php` `$config['mojo_db']  = true ;`

Read more on `config/instamojo.php`

# Installation 

Copy the three folders into application directory

Edit config/instamojo.php with your credentials

# Example Usage

    Initializing instamojo library
    $this->load->library('instamojo');

    Fetching all the payment request details.
		$result = $this->instamojo->all_payment_request();
		print_r($result);
    

    NB: Please go through controller/example.php to try basic checkout
 
