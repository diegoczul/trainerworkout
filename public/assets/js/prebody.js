function detectDevice(redirect){
	var isiDevice = /ipad|iphone|ipod|android|blackberry|windows phone/i.test(navigator.userAgent.toLowerCase());
	
	if (isiDevice && (screen.width <= 480)) 
	{
	  window.location = redirect;
	}
}