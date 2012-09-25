<?php
namespace dslib;
class Grid_Extended extends \Grid {
	
    function addExtendedSearch($fields,$class='dslib/Grid_ExtendedSearch'){
        return $this->add($class,null,'extended_search')
            ->useWith($this)
            ->useFields($fields);
    }

    function defaultTemplate(){
        return array('grid_extended'); // grid_extended | grid_extended_striped
    }
}
