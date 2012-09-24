<?php
namespace dslib;
class Form_Field_Chooser extends \Form_Field {
	
	// values
	public $empty_id = '';
	public $empty_text = 'No value';
	protected $text = '';
	
	// elements
	protected $bt_set;
	protected $bt_choose;
	protected $bt_delete;
	
	// init
	function init(){
		parent::init();
		
		// NOT WORKING !!!
		// Add intercept hook to page where this element/form is into in case if chooser is enabled and we should show CRUD form
		if(@$_GET[$this->name]=='choose') {
			//$page = $this->api->page_object;
			//var_dump($_GET);
			//$m = $page->add($_GET['model']);
			//$c = $page->add('CRUD');
			//$c->setModel($m);
		}
		
	}
	
	// Set empty value and text
	// Usage: emptyText() | emptyText('Please select...') | emptyText(array('0'=>'Please select...'))
	function emptyText($v=null){
		if(!$v) return $this;
		if(is_array($v)) {
			$this->empty_id = array_shift(array_keys($v));
			$this->empty_text = array_shift(array_values($v));
		} else {
			$this->empty_text = $v;
		}
		return $this;
	}

	// Return HTML of field/component
	function getInput($attr=array()){
		
		// get model
		$m = $this->form->model;
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
				$this->text = $n->get($n->getTitleField());
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

	function recursiveRender(){
		
		// Fake button to show ID - just for DEBUG
		$x = $this->add('Button',null,'before_input')->set('ID='.$this->value);
		
/*???*/ /*
		// Add text field
		// This object here is created, but it doesn't render.... Why? #@@%$&^%^$@$^&*%&$%#@!
		$x = $this->add('Form_Field_Line','nosaukums','after_input') // class,name,spot,template?
				->setForm($this->form)
				->set('aaaa value aaaa');
        $x->template->trySet('field_type','line');
        echo $x;
/*???*/
		
		// Add buttonset
		$this->bt_set = $this->add('ButtonSet',null,'after_input')
								->addClass('input-cell')
								->addStyle('white-space','nowrap');
		
		// Add buttons
		$this->bt_choose = $this->bt_set->addButton('')
								->setHTML('<span class="ui-button-icon-primary ui-icon ui-icon-pencil">&nbsp;</span>')
								->js('click')->univ()
									->frameURL('New',$this->api->getDestinationURL(null,array(
										$this->name=>'choose',
										'id'=>$this->js()->val(),
										'model'=>$this->form->model->short_name
									)))
								;
		$this->bt_delete = $this->bt_set->addButton('')
								->setHTML('<span class="ui-button-icon-secondary ui-icon ui-icon-close">&nbsp;</span>')
								->js('click',
									$this->js()
											->val($this->empty_id)
											//->css('border','2px solid yellow')
										->closest('form')
										->find('#'.$this->name.'_title')
											->val($this->empty_text)
											//->css('border','2px solid red')
										->univ()
											->successMessage('Removed')
								);
		
		return parent::recursiveRender();
	}

	// Render all field as buttonset
    function render(){
		// Enclose all component into .input-row block
		$this->template->trySetHTML('input_row_start','<div class="input-row">');
		$this->template->trySetHTML('input_row_stop','</div>');
		
		//$this->js(true)->buttonset(array('items'=>":input, :button, :submit, :reset, :checkbox, :radio, a, :data(button)"));
        parent::render();
    }
}
