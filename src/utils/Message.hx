package utils;

import js.JQuery;
import jp.saken.utils.Dom;
import jp.saken.utils.Ajax;

class Message {
	
	private static var _body:String;
	
	/* =======================================================================
	Public - Load
	========================================================================== */
	public static function load(onLoaded:Void->Void):Void {
		
		Ajax.getData('messages',['body'],function(data:Array<Dynamic>):Void {
			
			_body = data[0].body;
			onLoaded();
			
		},'id = 1');
		
	}
	
	/* =======================================================================
	Public - Get Body
	========================================================================== */
	public static function getBody():String {
		
		return _body;
		
	}
	
}