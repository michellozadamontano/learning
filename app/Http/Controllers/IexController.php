<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use DPRMC\IEXTrading;

class IexController extends Controller
{
    //
    public function stockQuote($symbol){
        $stockQuote = \IEXTrading::stockQuote( $symbol );
        echo $stockQuote->companyName; // Apple Inc.
        
    }
}
