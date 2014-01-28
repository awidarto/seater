<?php

class TransactionController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

        $this->model = new Transaction();
        //$this->model = DB::collection('documents');

    }

    public function getTest()
    {
        $raw = $this->model->where('docFormat','like','picture')->get();

        print $raw->toJSON();
    }


    public function getIndex()
    {

        $this->heads = array(
            array('Order Number',array('search'=>true,'sort'=>true)),
            array('Property ID',array('search'=>true,'sort'=>true)),
            array('Total Purchase',array('search'=>true,'sort'=>true)),
            array('Order Status',array('search'=>true,'sort'=>true)),
            array('Agent',array('search'=>true,'sort'=>true)),
            array('First Name',array('search'=>false,'sort'=>false)),
            array('Last Name',array('search'=>false,'sort'=>false)),
            array('Number',array('search'=>true,'sort'=>true)),
            array('Address',array('search'=>true,'sort'=>true)),
            array('City',array('search'=>true,'sort'=>true)),
            array('State',array('search'=>true,'sort'=>true)),
            array('ZIP',array('search'=>true,'sort'=>true)),
            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Transactions';

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('orderNumber',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('propertyId',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('total_purchase',array('kind'=>'text','callback'=>'tousd','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('orderStatus',array('kind'=>'text', 'callback'=>'statcolor' ,'query'=>'like','pos'=>'both','show'=>true)),
            array('agentName',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('firstname',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('lastname',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('number',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('address',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('city',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('state',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('zipCode',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        return parent::postIndex();
    }

    public function beforeSave($data)
    {

        return $data;
    }

    public function beforeUpdate($id,$data)
    {

        return $data;
    }

    public function postAdd($data = null)
    {

        $this->validator = array(
            'title' => 'required',
            'venue' => 'required',
            'location' => 'required',
        );

        return parent::postAdd($data);
    }

    public function postEdit($id,$data = null)
    {
        $this->validator = array(
            'title' => 'required',
            'venue' => 'required',
            'location' => 'required',
        );

        return parent::postEdit($id,$data);
    }

    public function fulladdress($data){
        if(isset($data['address_2'])){
            return $data['address'].' '.$data['address_2'];
        }else{
            return $data['address'];
        }
    }

    public function tousd($data){
        return '$'.Ks::usd($data['total_purchase']);
    }

    public function statcolor($data){
        return '<span class="'.$data['orderStatus'].'">'.$data['orderStatus'].'</span>';
    }

    public function makeActions($data)
    {
        $change = '<span class="chg act" rel="'.$data['orderNumber'].'" id="'.$data['_id'].'" ><i class="icon-edit"></i> Change Status</span>';
        $delete = '<span class="del act" id="'.$data['_id'].'" ><i class="icon-trash"></i>Delete</span>';
        $edit = '<a href="'.URL::to('transaction/edit/'.$data['_id']).'"><i class="icon-edit"></i>Update</a>';
        $dl = '<a href="'.URL::to('pr/dl/'.$data['_id']).'" target="new"><i class="icon-download"></i> Download</a>';
        $print = '<a href="'.URL::to('pr/print/'.$data['_id']).'" target="new"><i class="icon-print"></i> Print</a>';

        $actions = $change.'<br />'.$dl.'<br />'.$print.'<br /><br />'.$delete;
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
        $name = HTML::link('property/view/'.$data['_id'],$data['address']);

        $thumbnail_url = '';

        if(isset($data['files']) && count($data['files'])){
            $glinks = '';

            $gdata = $data['files'][$data['defaultpic']];

            $thumbnail_url = $gdata['thumbnail_url'];
            foreach($data['files'] as $g){
                $glinks .= '<input type="hidden" class="g_'.$data['_id'].'" data-caption="'.$g['caption'].'" value="'.$g['fileurl'].'" >';
            }

            $display = HTML::image($thumbnail_url.'?'.time(), $thumbnail_url, array('class'=>'thumbnail img-polaroid','id' => $data['_id'])).$glinks;
            return $display;
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


