package components;

import js.JQuery;
import jp.saken.utils.Handy;
import jp.saken.utils.Dom;
import jp.saken.ui.DragAndDrop;
import utils.Data;

class View {
	
	private static var _jFilename  :JQuery;
	private static var _jForm      :JQuery;
	private static var _jTestmail  :JQuery;
	private static var _jNote      :JQuery;
	private static var _jNum       :JQuery;
	private static var _jError     :JQuery;
	private static var _dragAndDrop:DragAndDrop;
	
	/* =======================================================================
	Public - Init
	========================================================================== */
	public static function init():Void {
		
		var jAll:JQuery = new JQuery('#all').show();
		
		_jFilename = jAll.find('#filename');
		_jForm     = jAll.find('#form');
		_jTestmail = _jForm.find('#testmail');
		_jNote     = jAll.find('#note');
		_jNum      = _jNote.find('.num');
		_jError    = _jNote.find('.error');
		
		_jForm.find('#sendMail').on('click',sendMail);
		
		_dragAndDrop = new DragAndDrop(Dom.jWindow,onDropped);
		
	}
	
		/* =======================================================================
		Public - Set Num
		========================================================================== */
		public static function setNum(success:Int,total:Int):Void {
			
			_jNum.html('正常：' + success + ' / ' + total);
			
		}
	
		/* =======================================================================
		Public - Add Error
		========================================================================== */
		public static function addError(name:String,mailaddress:String):Void {

			_jError.append('<p>' + name + '（' + mailaddress + '）' + '</p>');

		}
	
	/* =======================================================================
	On Dropped
	========================================================================== */
	private static function onDropped(data:String):Void {
		
		_jFilename.text(_dragAndDrop.getFilename());
		_jForm.add(_jNote).show();
		
		Data.init(data.split('\n'));
		
	}
	
	/* =======================================================================
	Send Mail
	========================================================================== */
	private static function sendMail(event:JqEvent):Void {
		
		var errorLength:Int = Data.getErrorLength();
		
		if (errorLength > 0) {
			Handy.alert('メールアドレスに誤りが見つかりました（' + errorLength + '件）。');
		}
		
		var testmail:String = _jTestmail.prop('value');
		
		Handy.confirm('メールを送信します。\nよろしいですか？',function():Void {
			
			if (testmail.length > 0) {
				
				Mailer.send(testmail);
			
			} else {
				
				Handy.confirm('本番配信を行います。',function():Void {
					Mailer.send();
				});
				
			}
			
		});
		
	}
	
}