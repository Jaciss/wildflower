<?php
class ExamplesController extends AppController {
    
    function wf_index() {
        
    }
    
    function index(){
        $items = glob('../../app/controllers/*.php');
        foreach($items as $item){
            $link = str_replace('../../app/controllers/','',$item);
            $links[] = str_replace('_controller.php', '', $link);
        }
        $this->set('items', $links);
        //print_r($items);
    }
    
}