package utils;

import components.View;

typedef Formated = Array<Map<String,String>>;

class Data {
	
	private static var _formated   :Formated;
	private static var _errorLength:Int;
	
	/* =======================================================================
	Public - Init
	========================================================================== */
	public static function init(array:Array<String>):Void {
		
		_formated = [];
		array.shift();
		
		var eReg :EReg = ~/^([a-zA-Z0-9])+([a-zA-Z0-9¥._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9¥._-]+)+$/;
		var total:Int  = array.length;
		
		for (i in 0...array.length) {
			
			var splits:Array<String> = array[i].split('\t');
			
			var name       :String = splits[1];
			var mailaddress:String = splits[2];
			
			if (name == null) continue;
			
			if (eReg.match(mailaddress) && name.indexOf('〓') < 0) {
				_formated.push(['name'=>name,'mailaddress'=>mailaddress]);
			} else {
				View.addError(name,mailaddress);
			}
			
		}
		
		var length:Int = _formated.length;
		_errorLength = total - length;
		
		View.setNum(length,total);
		
	}
	
	/* =======================================================================
	Public - Get Formated
	========================================================================== */
	public static function getFormated():Formated {
		
		return _formated;
		
	}
	
	/* =======================================================================
	Public - Get Error Length
	========================================================================== */
	public static function getErrorLength():Int {
		
		return _errorLength;
		
	}
	
}