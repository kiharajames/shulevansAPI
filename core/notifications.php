<?php
class MobileNotifications{
	public $title;
	public $body;
	public $token;

	

	public function  sendNotification(){
		$notification_header = ['Content-Type:application/json', 'Authorization: key=AAAAR-vEjnw:APA91bHmggpW6tg_7bGvNAy4AtcB3eFCISJFdrO8cvTXDydyLc174-E0E9U_fg85gNGZkHCm-xtsRiEEn5qobY3KGK7-e-LjnlfxnCpkVWwD84yW_WduRiAEaB4Wvm25QpAcXm8N9WYK'];
		$url = "https://fcm.googleapis.com/fcm/send";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $notification_header);

		$curl_post_data = array(
			'to' => $this->token, 
			'notification' => array(
				'title' => $this->title, 
				'body' => $this->body,
				),
			'data' => array(
				'title' => $this->title,
				'body' => $this->body,
				'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
			)
		);

		$data_string = json_encode($curl_post_data);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

		curl_exec($curl);

	}
}

?>