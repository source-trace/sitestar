<?php
if (!defined('IN_CONTEXT')) die('access violation error!');

/**
 * UserField object
 * 
 */
class UserField extends RecordObject {
    public $has_many = array();
    public $belong_to = array();
    public $has = array();
    
    protected $no_validate = array(
        'isEmpty' => array(
            
        )
    );
    
    protected $yes_validate = array(
        '_regexp_' => array(
           
        ),
        'isEmail' => array(
            
        )
    );
		
		public static function findAll2($where='',$orderby=''){
				$db=MysqlConnection::get();
				$return = $row = array();
				$table_name= Config::$tbl_prefix.'user_fields';

			     $find_keys = '*';

				$_sql = "SELECT $find_keys FROM {$table_name}";
				$_sql = empty($where) ? $_sql : $_sql." WHERE $where";
				$_sql = empty($orderby) ? $_sql : $_sql." $orderby";
				$res = $db->query($_sql);

				if(empty($res))
				{
					die('Select is failed.'.$_sql);
					return false;
				}

				$return=$res->fetchRows();
				//释放数据库结果集
				$res->free();
				return $return;
		}
		
		
		/**
     * 获取当前语言下自定义字段的label值
     *
     * @access public
     * @static
     */	
	public static function getMultilangLabel($labelarr,$cur_lang=null) {
		if(!is_array($labelarr)) return '';
		if(empty($cur_lang)) $cur_lang=SessionHolder::get('_LOCALE', '');
		if(!empty($labelarr[$cur_lang])) return $labelarr[$cur_lang];
		if(defined('DEFAULT_LOCALE')) $defaultlang=DEFAULT_LOCALE;
		
		if(!empty($labelarr[$defaultlang])) return $labelarr[$defaultlang];
		foreach($labelarr as $key=>$value){
			return $value;
		}
		return '';
	}
	
	 /**
     * 根据用户自定义字段信息和用户数据获取用户在这个字段显示的值
     *
     * @access public
     * @static
     */
	public static function getUserCustomValue($fieldinfo,$paramarr,$is_display=false) {
		$fieldname="field".$fieldinfo['id'];
		$fieldval=$paramarr[$fieldname];
		$fieldtype=$fieldinfo['field_type'];
		if($is_display){
			switch ($fieldtype) {
				case 3:
					if($fieldval=='1'){
						$fieldval=__('Yes');
					}else{
						$fieldval=__('No');
					}
					break;
			}
			$fieldval=  nl2br(htmlentities($fieldval,ENT_QUOTES,'UTF-8'));
		}
		return $fieldval;
	}
	
	 /**
     * 根据用户自定义字段信息生成对应控件html
     *
     * @access public
     * @static
     */
	public static function getUserCustomComponent($fieldinfo,$paramarr,$userinfo) {
		if(!is_array($paramarr)) $paramarr=array();
		$comp="";
		$fieldname="field".$fieldinfo['id'];
		$fieldval=$paramarr[$fieldname];
		$fieldtype=$fieldinfo['field_type'];
		$is_only_display=$fieldinfo['_only_display'];
		$validatestr='';
		if($fieldinfo['required']=='1') $validatestr.=' required ';
		$cur_lang=$fieldinfo['_cur_lang'];
		 if(empty($cur_lang)) $cur_lang=SessionHolder::get('_LOCALE', '');
		switch ($fieldtype) {
			case 0:
				$comp=self::getUserDefaultFieldComponent($fieldinfo, $userinfo);
				break;
			case 1:
				if($is_only_display){
					$comp=Html::input('text', 'extends['.$fieldname.']', $fieldval, ' readOnly');
				}else{
					$comp=Html::input('text', 'extends['.$fieldname.']', $fieldval, 'class="textinput fieldtype'.$fieldtype.'" '.$validatestr);
				}
				break;
			case 2:
				if($is_only_display){  
					$comp='<textarea name="extends['.$fieldname.']" rows="5" readOnly >'.$fieldval.'</textarea> ';
				}
				else{
					$comp='<textarea name="extends['.$fieldname.']" cols="76" rows="10" class="textinput fieldtype'.$fieldtype.'" '.$validatestr.'>'.$fieldval.'</textarea> ';
				}
				break;
			case 3:
				$field_option = unserialize($fieldinfo['options']);
				$opts_data=$field_option['data'];
				if(empty($opts_data)) $opts_data=array();
				if(empty($fieldval)) $fieldval=array();
				foreach($opts_data as $onedata){
					$checked='';
					if(in_array($onedata['key'],$fieldval)) $checked='checked';
					if($is_only_display){
						$comp.=" ".Html::input('checkbox', 'extends['.$fieldname.'][]', $onedata['key'], 'readOnly');
					}else{
						$comp.=" ".Html::input('checkbox', 'extends['.$fieldname.'][]', $onedata['key'], $checked.$validatestr." class='fieldtype$fieldtype' ");
					}
					$comp.=" ".self::getMultilangLabel($onedata['val']);
				}
				
				break;
			case 4:
				$field_option = unserialize($fieldinfo['options']);
				$opts_data=$field_option['data'];
				if(empty($opts_data)) $opts_data=array();
				foreach($opts_data as $onedata){
					$checked='';
					if($fieldval==$onedata['key']) $checked='checked';
					if($is_only_display){
						$comp.=" <input type='radio' name='extends[$fieldname]' readOnly  value='{$onedata['key']}'/> ";
					}else {
						$comp.=" <input type='radio' name='extends[$fieldname]' $checked $validatestr  class='fieldtype$fieldtype' value='{$onedata['key']}'/> ";
					}
					$comp.=self::getMultilangLabel($onedata['val']);
				}
				break;
			case 5:
				if($is_only_display){
					$comp=Html::input('text', 'user['.$propname.']',__('Date picker'),  ' readOnly');

				}else{
				$comp=Html::input('text', 'extends['.$fieldname.']',$fieldval,  'style="width:100px;" class="textinput date fieldtype'.$fieldtype.'" '.$validatestr);
				$comp.="<script>
$(function(){
_addDatePicker($('input[name=\"extends[{$fieldname}]\"]'));			
});
</script>";	
				}
				break;
		}
		return $comp;
	}
	
	 /**
     * 系统默认字段对应的label
     *
     * @access public
     * @static
     */
	public static function getUserDefaultFieldLabel($key){
			$labelkeyrel=array(
					'full_name'=>__('Full Name'),
					'gender'=>__('Gender'),
					'nickname'=>__('Nick Name'),
					'birthday'=>__('Birthday'),
					'country'=>__('Country'),
					'city'=>__('City'),
					'addr1'=>__('Address 1'),
					'addr2'=>__('Address 2'),
					'zipcode'=>__('Zip code'),
					'mobile'=>__('Mobile phone'),
					'telphone'=>__('Telephone'),
			);
			$label=$labelkeyrel[$key];
			return $label;
	}
	
	public static function getUserDefaultFieldComponent($fieldinfo,$userinfo){
				$comp="";
				$propname=$fieldinfo['label'];
				$validatestr='';
				$is_only_display=$fieldinfo['_only_display'];
				$val=$userinfo[$propname];
				if($fieldinfo['required']=='1') $validatestr=' required ';
				switch ($propname) {
						case 'full_name':case 'nickname':case 'country':
						case 'city':case 'addr1':case 'addr2':
						case 'zipcode':case 'mobile':case 'telphone':
								if($is_only_display){
									$comp=Html::input('text', 'user['.$propname.']',$val,  'readOnly');
								}else{
									$comp=Html::input('text', 'user['.$propname.']',$val,  'class="textinput" '.$validatestr);
								}
								break;
						case 'gender':
								$cur_lang=$fieldinfo['_cur_lang'];
								 if(empty($cur_lang)) $cur_lang=SessionHolder::get('_LOCALE', '');
								if($is_only_display){
									$comp.="<input name=user[{$propname}] type='radio' value='M' readOnly/> ".__('Male');
									$comp.="  <input name=user[{$propname}] type='radio' value='F' readOnly/> ".__('Female');
								}else{
									$comp.="<input name=user[{$propname}] class='fieldtype4' type='radio' value='M' ".($val=='M'?'checked':'')." {$validatestr}/> ".__('Male');
									$comp.="  <input name=user[{$propname}] class='fieldtype4' type='radio' value='F' ".($val=='F'?'checked':'')." {$validatestr}/> ".__('Female');
								}
								break;
						case 'birthday':
								if($is_only_display){
									$comp=Html::input('text', 'user['.$propname.']',__('Date picker'),  ' readOnly');

								}else{
								$comp=Html::input('text', 'user['.$propname.']',!empty($val)&&$val!='0000-00-00'?$val:'',  'style="width:100px;" class="textinput  date" '.$validatestr);
							$comp.="<script>
$(function(){
_addDatePicker($('input[name=\"user[{$propname}]\"]'));			
});
</script>";
								}
								break;

				}
				return $comp;
	}
	
	public static function getUserDefineLabel($fieldinfo){
		if(empty($cur_lang)) $cur_lang=SessionHolder::get('_LOCALE', '');
		$label=$fieldinfo['label'];
		if($fieldinfo['field_type']==0){
			return self::getUserDefaultFieldLabel($label);
		}else{
			return self::getMultilangLabel(unserialize($label));
		}
	}
	
	public static function showDisplayFieldStyle($fieldinfo){
		$fieldinfo['_only_display']=true;
		return self::getUserCustomComponent($fieldinfo, array(), array());
	}
	
	/*
	 * zjc 2013-03-01 兼容php5.3
	 */
	 public static function trim($arr){
	 	if(is_array($arr)){
	 		return 'Array';
	 	}
	 	return trim($arr);
	 }
	
	public static function getMaxOrder() {
        $db =& MySqlConnection::get();
        $sql = "SELECT MAX(i_order) AS max_order FROM ".Config::$tbl_prefix."user_fields";
        $rs =& $db->query($sql);
        if ($rs->getRecordNum() == 0) {
            return 0;
        } else {
            $row =& $rs->fetchRow();
            return intval($row['max_order']);
        }
    }
}
?>
