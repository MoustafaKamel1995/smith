<?php

namespace App\Jobs;

class PushNotificationJob extends Job
{
	protected $topic, $push_message, $title, $data, $user, $settings, $type;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($topic, $push_message, $title, $data, $user, $settings, $type)
	{
		$this->topic = $topic;
		$this->push_message = $push_message;
		$this->title = $title;
		$this->data = $data;
		$this->user = $user;
		$this->settings = $settings;
		$this->type = $type;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		\Log::info("In push notification");

		$fields = array(
						'to' => $this->user->fcm_token,
						"notification" => [
							'body' 	=> $this->push_message,
							'title'		=> $this->title,
							'vibrate'	=> 0,
							'sound'		=> 1,
							"click_action"=> "OPEN_NOTIFICATIONS",
						],
						"data" => $this->data
		           	);

			$headers = array(
			             'Authorization'=>' key=AAAAu4J1MmU:APA91bGMcvFp8-A34LA8F5GnvtYyNXkEXbpp3uuvpAEWXPZLY35EgFDRF0WdxJRPRtNem7Z1O4guD_Nd7azC4vP-yJmBuIsO3K4pjvhpaiG7gPO5327OD8ghU6u922xAZyroicarc1SH',
			             'Content-Type'=>' application/json'
			           );

			$url = "https://fcm.googleapis.com/fcm/send";

			$response = \Httpful\Request::post($url)
			            ->sendsJson()
			            ->body(json_encode($fields))
			             ->addHeaders($headers)
			             ->send();
			\Log::info("Push notification api");
			\Log::info($response);
			return $response;
		
	}
}
