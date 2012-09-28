<?php
namespace dslib;
class Form_Field_Chau extends \Form_Field_Line {
	
	function init(){
		parent::init();
		
		echo $this->owner;
		
		// Add Chooser view - JUST TESTING. Probably I should use View for all these extended elements (text field, buttons)
		//$this->add('dslib/View_Chooser',null,'after_input');
	}
	
	//function getInput($attr=array()){
	//	return parent::getInput(array_merge(array('type'=>'text'),$attr));
	//}

	function setModel($m){
		parent::setModel($m);
		
		$data = $this->model->getRows(array($this->model->id_field,$this->model->title_field));
		
		$this->js()->autocomplete(array('source'=>$data));
	}
	
	
	
	
}

/*
	// Return HTML of field/component.
	function getInput($attr=array()){
		// This function returns HTML tag for the input field. Derived classes should inherit this and add
		// new properties if needed
		
		// get model
		$m = $this->form->getModel();
		//echo $this->form; // Sometimes this is set as Object dslib\Grid_ExtendedSearch WHY ???
		if(!$m) {
			$this->displayFieldError("Form doesn't have associated model!");
			return;
		}
		
		// check if field is of class Field_Reference
		if(!$m->getField($this->short_name) instanceof \Field_Reference){
			$this->displayFieldError(__CLASS__." can only be used for referenced fields!");
			return;
		}
		
		// Find text/name of value from related model
		if($this->value && $this->value!=$this->empty_id) {
			$n = $m->ref($this->short_name);
			if(!$n) {
				$this->displayFieldError("Can't load referenced Model data!");
			} else {
				$this->text = '['.$n->get('id').'] ' . $n->get($n->getTitleField());
			}
		} else {
			$this->value = $this->empty_id;
			$this->text = $this->empty_text;
		}
		
		// Create HTML markup for hidden input field (ID field)
		$f_id = parent::getInput(array_merge(array('type'=>'hidden'),$attr));
		
		// Create HTML markup for text field (value/name/title field) -- this probably should be moved to recursiveRender(), but there it doesn't render anymore
		$f_text = parent::getInput(array(
					'type'=>'text',
					'name'=>$this->name . '_title',
					'id'=>$this->name . '_title',
					'value'=>$this->text,
					'disabled'=>'disabled'
				));

		// Create complete output of fields
		$r  = '<div class="input-cell expanded">'.$f_text.$f_id.'</div>';
		
		// Return HTML output
		return $r;
	}

	// Render all field as buttonset
    function render(){
		// Enclose all component into .input-row block
		$this->template->trySetHTML('input_row_start','<div class="input-row">');
		$this->template->trySetHTML('input_row_stop','</div>');
		
		//$this->js(true)->buttonset(array('items'=>":input, :button, :submit, :reset, :checkbox, :radio, a, :data(button)"));
        parent::render();
    }
*/