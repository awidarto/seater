<?php

class AttendingController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

        $this->model = new Attendee();
        //$this->model = DB::collection('documents');

    }

    public function getTest()
    {
        $raw = $this->model->where('docFormat','like','picture')->get();

        print $raw->toJSON();
    }


    public function getIndex()
    {
        /*
'activeCart' => '5260f68b8dfa19da49000000',
'address_1' => 'jl cibaduyut lama komplek sauyunan mas 1 no 19',
'address_2' => '',
'agreetnc' => 'Yes',
'bankname' => 'bca',
'branch' => 'bandung',
'city' => 'bandung',
'country' => 'Indonesia',
'createdDate' => new MongoDate(1382086083, 795000),
'email' => 'emptyshalu@gmail.com',
'firstname' => 'shalu',
'fullname' => 'shalu hz',
'lastUpdate' => new MongoDate(1382086083, 795000),
'lastname' => 'shalu',
'mobile' => '0818229096',
'pass' => '$2a$08$9XwvZZVLsHSzu4MIX1ro3.X3cdhK0btglG7qqLGPgOA6/yYz5a51C',
'role' => 'shopper',
'salutation' => 'Ms',
'saveinfo' => 'No',
'shippingphone' => '02285447649',
'shopperseq' => '0000000019',
'zip' => '40235',
        */


        $this->heads = array(
            array('Photo',array('search'=>true,'sort'=>true)),
            array('Code',array('search'=>true,'sort'=>true, 'attr'=>array('class'=>'span2'))),
            array('Table',array('search'=>true,'sort'=>true)),
            array('Seat',array('search'=>true,'sort'=>true)),
            array('Name',array('search'=>true,'sort'=>true)),
            array('Title',array('search'=>true,'sort'=>true)),
            array('Work Unit',array('search'=>true,'sort'=>true)),
            array('Type',array('search'=>true,'sort'=>false)),
            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();
        $this->title = 'Attendance';

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('tableNumber',array('kind'=>'numeric','callback'=>'namePic', 'query'=>'like','pos'=>'both','show'=>true)),
            array('tableNumber',array('kind'=>'numeric','callback'=>'dispBar', 'query'=>'like','pos'=>'both','show'=>true)),
            array('tableNumber',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('seatNumber',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('name',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander'))),
            array('title',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('unit',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('type',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        $this->additionalQuery = array('attending'=>1);

        return parent::postIndex();
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'name' => 'required',
            'tableNumber' => 'required',
            'seatNumber'=> 'required',
            'type'=>'required'
        );

        return parent::postAdd($data);
    }

    public function beforeSave($data)
    {
        unset($data['repass']);
        $data['pass'] = Hash::make($data['pass']);
        return $data;
    }

    public function beforeUpdate($id,$data)
    {
        $defaults = array();

        $files = array();

        if( isset($data['file_id']) && count($data['file_id'])){
            $data['defaultpic'] = (isset($data['defaultpic']))?$data['defaultpic']:$data['file_id'][0];
            /*
            $data['brchead'] = (isset($data['brchead']))?$data['brchead']:$data['file_id'][0];
            $data['brc1'] = (isset($data['brc1']))?$data['brc1']:$data['file_id'][0];
            $data['brc2'] = (isset($data['brc2']))?$data['brc2']:$data['file_id'][0];
            $data['brc3'] = (isset($data['brc3']))?$data['brc3']:$data['file_id'][0];
            */

            for($i = 0 ; $i < count($data['file_id']); $i++ ){


                $files[$data['file_id'][$i]]['thumbnail_url'] = $data['thumbnail_url'][$i];
                $files[$data['file_id'][$i]]['large_url'] = $data['large_url'][$i];
                $files[$data['file_id'][$i]]['medium_url'] = $data['medium_url'][$i];
                $files[$data['file_id'][$i]]['full_url'] = $data['full_url'][$i];

                $files[$data['file_id'][$i]]['delete_type'] = $data['delete_type'][$i];
                $files[$data['file_id'][$i]]['delete_url'] = $data['delete_url'][$i];
                $files[$data['file_id'][$i]]['filename'] = $data['filename'][$i];
                $files[$data['file_id'][$i]]['filesize'] = $data['filesize'][$i];
                $files[$data['file_id'][$i]]['temp_dir'] = $data['temp_dir'][$i];
                $files[$data['file_id'][$i]]['filetype'] = $data['filetype'][$i];
                $files[$data['file_id'][$i]]['fileurl'] = $data['fileurl'][$i];
                $files[$data['file_id'][$i]]['file_id'] = $data['file_id'][$i];
                //$files[$data['file_id'][$i]]['caption'] = $data['caption'][$i];

                if($data['defaultpic'] == $data['file_id'][$i]){
                    $defaults['thumbnail_url'] = $data['thumbnail_url'][$i];
                    $defaults['large_url'] = $data['large_url'][$i];
                    $defaults['medium_url'] = $data['medium_url'][$i];
                }

                /*
                if($data['brchead'] == $data['file_id'][$i]){
                    $defaults['brchead'] = $data['large_url'][$i];
                }

                if($data['brc1'] == $data['file_id'][$i]){
                    $defaults['brc1'] = $data['large_url'][$i];
                }

                if($data['brc2'] == $data['file_id'][$i]){
                    $defaults['brc2'] = $data['large_url'][$i];
                }

                if($data['brc3'] == $data['file_id'][$i]){
                    $defaults['brc3'] = $data['large_url'][$i];
                }
                */


            }

        }else{

            $data['thumbnail_url'] = array();
            $data['large_url'] = array();
            $data['medium_url'] = array();
            $data['full_url'] = array();
            $data['delete_type'] = array();
            $data['delete_url'] = array();
            $data['filename'] = array();
            $data['filesize'] = array();
            $data['temp_dir'] = array();
            $data['filetype'] = array();
            $data['fileurl'] = array();
            $data['file_id'] = array();
            $data['caption'] = array();

            $data['defaultpic'] = '';
            /*
            $data['brchead'] = '';
            $data['brc1'] = '';
            $data['brc2'] = '';
            $data['brc3'] = '';
            */
        }

        $data['defaultpictures'] = $defaults;
        $data['files'] = $files;

        return $data;
    }

/*
    public function beforeUpdate($id,$data)
    {
        //print_r($data);

        if(isset($data['pass']) && $data['pass'] != ''){
            unset($data['repass']);
            $data['pass'] = Hash::make($data['pass']);

        }else{
            unset($data['pass']);
            unset($data['repass']);
        }

        //print_r($data);

        //exit();

        return $data;
    }
    */

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'name' => 'required',
            'tableNumber' => 'required',
            'seatNumber'=> 'required',
            'type'=>'required'
        );

        return parent::postEdit($id,$data);
    }

    public function getImport(){

        $this->importkey = 'propertyId';

        return parent::getImport();
    }

    public function postUploadimport()
    {
        return parent::postUploadimport();
    }

    public function beforeImportCommit($data)
    {

        return $data;
    }


    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="icon-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('attendee/edit/'.$data['_id']).'"><i class="icon-edit"></i>Update</a>';
        $print = '<a href="'.URL::to('attendee/print/'.$data['_id']).'"><i class="icon-print"></i>Print</a>';

        $actions = $print.'<br />'.$edit.'<br />'.$delete;
        return $actions;
    }

    public function splitTag($data){
        $tags = explode(',',$data['docTag']);
        if(is_array($tags) && count($tags) > 0 && $data['docTag'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['docTag'];
        }
    }

    public function splitShare($data){
        $tags = explode(',',$data['docShare']);
        if(is_array($tags) && count($tags) > 0 && $data['docShare'] != ''){
            $ts = array();
            foreach($tags as $t){
                $ts[] = '<span class="tag">'.$t.'</span>';
            }

            return implode('', $ts);
        }else{
            return $data['docShare'];
        }
    }

    public function namePic($data)
    {
        $name = HTML::link('attendee/view/'.$data['_id'],$data['type'].'-'.$data['tableNumber'].'-'.$data['seatNumber']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['type'].'-'.$data['tableNumber'].'-'.$data['seatNumber'], array('id' => $data['_id']));
            return $display;
        }else{
            return $name;
        }
    }

    public function dispBar($data)
    {
        $display = HTML::image(URL::to('barcode/'.$data['_id']), $data['tableNumber'].' '.$data['seatNumber'], array('id' => $data['_id'], 'style'=>'width:100px;height:auto;' ));
        return $display.'<br />'.$data['type'].'-'.$data['tableNumber'].'-'.$data['seatNumber'];
    }

    public function pics($data)
    {
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('style'=>'min-width:100px;','id' => $data['_id']));
            return $display.'<br /><span class="img-more" id="'.$data['_id'].'">more images</span>';
        }else{
            return $name;
        }
    }

    public function getViewpics($id)
    {

    }


}
