<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Twilio\TwiML\VoiceResponse;

class WebHookController extends Controller
{
    public function twilio_incoming(Request $request){
    	\Log::info($request->outgoing_caller_id);
    	$xml='<Response>
    				<Dial callerId="'.$request->outgoing_caller_id.'">
	    				<Client>
	    					'.$request->To.'
	    					<Parameter name="outgoing_caller_id" value="'.$request->outgoing_caller_id.'"/>
						</Client>
					</Dial>
				</Response>';
		return response($xml)->header('Content-Type', 'text/xml');
    }

}
