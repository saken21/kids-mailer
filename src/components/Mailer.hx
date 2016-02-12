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
		
		request(['to'=>'sakata@graphic.co.jp','body'=>Message.getBody()]);
		return;
		
		var counter:Int = 0;
		
		var formatedData:Array<Array<String>> = Data.getFormated();
		var isTest:Bool = testmail.length > 0;

		for (i in 0...formatedData.length) {
			
			counter++;
			
			var replaced:Map<String,String> = getReplaced(formatedData[i],counter);
			
			if (isTest) {
				
				if (counter % 100 == 0) {
					
					replaced['mail'] = testmail;
					request(replaced);
					
				}
				
			} else {
				
				request(replaced);

				if (counter % 333 == 0) {
					
					replaced['mail'] = 'sakata@graphic.co.jp';
					request(replaced);
					
				}
				
			}
			
		}

	}
	
	/* =======================================================================
	Get Replaced
	========================================================================== */
	private static function getReplaced(info:Array<String>,num:Int):Map<String,String> {
		
		
		//body = StringTools.replace(body,'##1',corporate);
		
		//return ['mail'=>mail,'subject'=>message['subject'],'body'=>body,'staffFullname'=>staffFullname,'staffMail'=>staffMail];
		return new Map();
		
	}
	
	/* =======================================================================
	Request
	========================================================================== */
	private static function request(map:Map<String,String>):Void {
		
		var http:Http = new Http('files/php/sendMail.php');
		
		http.onData = function(data:String):Void { trace(data); };
		
		http.setParameter('to',map['to']);
		http.setParameter('body',map['body']);
		
		http.request(true);
		
	}
	
}