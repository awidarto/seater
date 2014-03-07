<?php

class ScannerController extends AdminController {

    public function __construct()
    {
        //parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        /*
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));
        */
        //$this->model = new Attendee();
        //$this->model = DB::collection('documents');

    }

    public function getIndex(){
        $this->title = 'Seating';

        $attending = Attendee::where('attending','=',1)->get()->toArray();

        $tabstat = array();

        foreach ($attending as $att) {
            $tidx = $att['type'].'-'.$att['tableNumber'];
            if(isset($tabstat[$tidx])){
                $tabstat[$tidx] = $tabstat[$tidx] + 1;
            }else{
                $tabstat[$tidx] = 1;
            }
        }

        return View::make('seating.chart')->with('title',$this->title)->with('tabstat',$tabstat);
    }

}