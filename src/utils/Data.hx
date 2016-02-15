package utils;

typedef Formated = Array<Map<String,String>>;

class Data {
	
	private static var _formated:Formated;
	
	/* =======================================================================
	Public - Init
	========================================================================== */
	public static function init(array:Array<String>):Void {
		
		_formated = [];
		array.shift();
		
		for (i in 0...array.length) {
			
			var splits:Array<String> = array[i].split('\t');
			_formated[i] = ['name'=>splits[1],'mailaddress'=>splits[2]];
			
		}
		
	}
	
	/* =======================================================================
	Public - Get Formated
	========================================================================== */
	public static function getFormated():Formated {
		
		return _formated;
		
	}
	
}