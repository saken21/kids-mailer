/**
* ================================================================================
*
* Kids Mailer ver 1.00.00
*
* Author : KENTA SAKATA
* Since  : 2016/02/12
* Update : 2016/02/29
*
* Licensed under the MIT License
* Copyright (c) Kenta Sakata
* http://saken.jp/
*
* ================================================================================
*
**/
package;

import js.JQuery;
import components.View;
import utils.Message;

class Main {
	
	public static function main():Void {
		
		new JQuery('document').ready(init);
		
    }

	private static function init(event:JqEvent):Void {
		
		Message.load(View.init);
		
	}

}