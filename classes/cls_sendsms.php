<?php
class sms{
var $url;
var $html;
var $message;
var $to;
var $parameters;
function send(){
	$this->url="http://websms.one97.net/sendsms/push_sms_new.php?user=itgc1&pwd=itgc1";

	if($this->message!=="" && $this->to!=="" ){
		if($this->from==""){
			$this->from="TrackingExperts";
		}

 	$this->message=str_replace(" ","%20",$this->message);
	$this->message ;
	$this->parameters="&from=".$this->from."&to=".$this->to."&msg=".$this->message."";
	$this->url=$this->url.$this->parameters;


	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL,$this->url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$html = curl_exec ($ch);
	curl_close ($ch);

	return $html;	
	}
	else{
	return "Please Fill All Inputs";
	}

}
  
    
}

?>