<?php
namespace dslib;
class Grid_Extended extends \Grid {
	
    function addAdvancedSearch($fields,$class='dslib/Grid_AdvancedSearch'){
        return $this->add($class,null,'advanced_search')
            ->useWith($this)
            ->useFields($fields);
    }

    function defaultTemplate(){
        return array('grid_advanced'); // grid_advanced | grid_advanced_striped
    }
}
