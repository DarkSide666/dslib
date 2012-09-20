<?php
namespace dslib;
class Form_Field_Chooser extends \Form_Field {
	
	function init(){
		parent::init();
	}
	
	function getInput($attr=array()){
		$r  = parent::getInput(array_merge(array('type'=>'hidden'),$attr));
		$r .= parent::getInput(array('type'=>'text','name'=>'xxx','id'=>'yyy','value'=>'zzz','disabled'=>'true'));
		return $r;
	}
	/*
	function render(){
        if($this->owner == $this->form){
            $this->form->template_chunks['form']->appendHTML('Content',$this->getInput());
        }else $this->output($this->getInput());
	}
	*/
	
}

/*
class Form_Field_MyDropDown extends Form_Field_ValueList {
    public $empty_value='';

    function emptyValue($v){
        $this->empty_value=$v;
        return $this;
    }
	function validate(){
		if(!$this->value)return parent::validate();
        $this->getValueList(); //otherwise not preloaded?
		if(!isset($this->value_list[$this->value])){
			   //if($this->api->isAjaxOutput()){
			   //$this->ajax()->displayAlert($this->short_name.": This is not one of the offered values")
			   //->execute();
			   //}
			$this->form->errors[$this->short_name]="This is not one of the offered values";
		}
		return parent::validate();
	}
	function getInput($attr=array()){
		$output=$this->getTag('select',array_merge(array(
						'name'=>$this->name,
						'id'=>$this->name,
						),
					$attr,
					$this->attr)
				);

        foreach($this->getValueList() as $value=>$descr){
            // Check if a separator is not needed identified with _separator<
            $output.=
                $this->getOption($value)
                .htmlspecialchars($descr)
                .$this->getTag('/option');
        }
		$output.=$this->getTag('/select');
		return $output;
	}
	function getOption($value){
		return $this->getTag('option',array(
					'value'=>$value,
					'selected'=>$value == $this->value
					));
	}
}
*/
