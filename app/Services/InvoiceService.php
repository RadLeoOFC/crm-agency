<?php
namespace App\Services;
use App\Models\{Invoice, Payment};
use Carbon\Carbon;

class InvoiceService 
{
    public function TokenCreation(Invoice $invoice) 
    {
        $token = sprintf(
		    '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		    mt_rand(0, 0xffff),
		    mt_rand(0, 0xffff),
		    mt_rand(0, 0xffff),
		    mt_rand(0, 0x0fff) | 0x4000,
		    mt_rand(0, 0x3fff) | 0x8000,
		    mt_rand(0, 0xffff),
		    mt_rand(0, 0xffff),
		    mt_rand(0, 0xffff)
	    );
 
	    return $token;
    }
}