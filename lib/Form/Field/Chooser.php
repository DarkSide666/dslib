<?php
namespace dslib;
class Form_Field_Chooser extends \Form_Field {
	
	// values
	public $empty_id = '';
	public $empty_text = 'No value';
	protected $text = '';

	// CRUD rights
	public $allow_add=false;
	public $allow_edit=false;
	public $allow_del=false;

	// elements
	protected $crud;
	protected $bt_set;
	protected $bt_choose;
	protected $bt_delete;
	
	//public $chooser; // Chooser view
	
	// init
	function init(){
		parent::init();
		
		// Add Chooser view - JUST TESTING. Probably I should use View for all these extended elements (text field, buttons)
		//$this->chooser = $this->add('dslib/View_Chooser',null,'after_input');
		
		// Add CRUD for dialog window
		if(isset($_GET[$this->name])) {
            $this->api->stickyGET($this->name);
			$this->crud = $this->add('CRUD',null,'after_input');
			$this->crud->allow_add = $this->allow_add;
			$this->crud->allow_edit = $this->allow_edit;
			$this->crud->allow_del = $this->allow_del;
            $this->api->cut($this->crud);

			// If Return value from dialog
			if(isset($_GET['choosed_id'])){
				$new_id = $_GET['choosed_id'];
				$this->api->stickyForget($this->name);
				
				$this->crud->js(true,$this->js()->reload())->univ()
					->closeDialog()
					->alert('Got ID = '.$new_id.'. Now I should reload ID Field: '.$this->name)
					->execute();
				//return;
			}

            return;
		}
		
	}
	
	function recursiveRender(){
		
		// If dialog window is open, then configure CRUD
		if($this->crud){
			$m = $this->form->getModel()->ref($this->short_name);
			$this->crud->setModel($m,null,array($m->id_field,$m->getTitleField()));
			if($this->crud->grid) {
				$this->crud->grid->addColumn('button','choosed_id','Izvçlçties'); /**/
				$this->crud->grid->addPaginator(5);
				$this->crud->grid->addQuickSearch(array($m->id_field,$m->getTitleField()));
			}
			
			return parent::recursiveRender();
		}
		// else add additional controls (text field + buttons)



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
								->addStyle('margin-right','-.3em')
								->js('click')->univ()
									->frameURL('New',$this->api->getDestinationURL(null,array(
										$this->name=>'chooser',
										$this->short_name=>$this->value, //$this->js()->val()
									)))
								;
		$this->bt_delete = $this->bt_set->addButton('')
								->setHTML('<span class="ui-button-icon-secondary ui-icon ui-icon-close">&nbsp;</span>')
								->addStyle('margin-right','-.3em')
								->js('click',
									$this->js()
											->val($this->empty_id)
										->closest('form')
										->find('#'.$this->name.'_title')
											->val($this->empty_text)
										->univ()
											->successMessage('Removed')
								);
		
		return parent::recursiveRender();
	}

	// Return HTML of field/component.
	// This method is called only by Field.render(), so it's not called when we only have CRUD - cool ;)
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
				$this->text = /*DEBUG*/'['.$n->get('id').'] './*DEBUG*/$n->get($n->getTitleField());
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

}
