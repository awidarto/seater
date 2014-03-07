<?php

class UserController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

        $this->model = new User();
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
            array('Full Name',array('search'=>true,'sort'=>true)),
            array('Role',array('search'=>true,'sort'=>true) ),
            array('Email',array('search'=>true,'sort'=>true)),
            array('Mobile',array('search'=>true,'sort'=>true)),
            array('Address',array('search'=>true,'sort'=>true)),
            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();
        $this->title = 'Administrator';

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('fullname',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('role',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('email',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander'))),
            array('mobile',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('address_1',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        return parent::postIndex();
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'email'=> 'required|unique:agents',
            'pass'=>'required|same:repass'
        );

        return parent::postAdd($data);
    }

    public function beforeSave($data)
    {
        unset($data['repass']);
        $data['pass'] = Hash::make($data['pass']);

        $data['fullname'] = $data['firstname'].' '.$data['lastname'];

        return $data;
    }

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

        $data['fullname'] = $data['firstname'].' '.$data['lastname'];

        //print_r($data);

        //exit();

        return $data;
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'email'=> 'required'
        );

        if($data['pass'] == ''){
            unset($data['pass']);
            unset($data['repass']);
        }else{
            $this->validator['pass'] = 'required|same:repass';
        }

        return parent::postEdit($id,$data);
    }

    public function makeActions($data)
    {
        $delete = '<span class="del" id="'.$data['_id'].'" ><i class="icon-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('user/edit/'.$data['_id']).'"><i class="icon-edit"></i>Update</a>';

        $actions = $edit.'<br />'.$delete;
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
        $name = HTML::link('products/view/'.$data['_id'],$data['productName']);
        if(isset($data['thumbnail_url']) && count($data['thumbnail_url'])){
            $display = HTML::image($data['thumbnail_url'][0].'?'.time(), $data['filename'][0], array('id' => $data['_id']));
            return $display.'<br />'.$name;
        }else{
            return $name;
        }
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
