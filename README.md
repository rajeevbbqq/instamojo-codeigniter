# Instamojo Payment api for codeigniter
Instamojo Payment api written for Codeigniter 2.x

# Installation 

Copy the three folders into application directory

Edit config/instamojo.php with your credentials

# Example Usage

    Initializing instamojo library
    $this->load->library('instamojo');

    Fetching all the payment request details.
		$result = $this->instamojo->all_payment_request();
		print_r($result);
    

    NB: Please go through Insatmojo.php in Library folder to view all functions
 
