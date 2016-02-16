package components;

import haxe.Http;
import jp.saken.utils.Handy;
import utils.Data;
import utils.Message;

class Mailer {
	
	/* =======================================================================
	Public - Send
	========================================================================== */
	public static function send(testmail:String = null):Void {
		
		var formated:Formated = Data.getFormated();
		
		var body   :String = '\n' + Message.getBody();
		var counter:Int    = 0;
		var isTest :Bool   = testmail.length > 0;
		
		for (i in 0...formated.length) {
			
			counter++;
			
			var map:Map<String,String> = formated[i];
			var addedBody:String = map['name'] + body;
			
			if (isTest) {
				
				if (counter % 100 == 0) {
					request(testmail,addedBody);
				}
				
			} else {
				
				request(map['mailaddress'],addedBody);

				if (counter % 333 == 0) {
					request('sakata@graphic.co.jp',addedBody);
				}
				
			}
			
		}

	}
	
	/* =======================================================================
	Request
	========================================================================== */
	private static function request(to:String,body:String):Void {
		
		var http:Http = new Http('files/php/sendMail.php');
		
		http.onData = function(data:String):Void {
			onComplete(data == '1',to);
		};
		
		http.setParameter('to',to);
		http.setParameter('body',body);
		
		http.request(true);
		
	}
	
	/* =======================================================================
	On Complete
	========================================================================== */
	private static function onComplete(isSuccess:Bool,mailaddress:String):Void {
		
		trace(mailaddress + ' : 送信' + (isSuccess ? '成功' : '失敗') + '！');
		
	}
	
}