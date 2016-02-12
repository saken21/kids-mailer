package utils;

class Data {
	
	private static var _formated:Array<Array<String>>;
	
	/* =======================================================================
	Public - Init
	========================================================================== */
	public static function init(array:Array<String>):Void {
		
		trace(array);
		
	}
	
	/* =======================================================================
	Public - Get Formated
	========================================================================== */
	public static function getFormated():Array<Array<String>> {
		
		return _formated;
		
	}
	
}