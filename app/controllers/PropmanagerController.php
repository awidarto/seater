<?php

class PropmanagerController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

        $this->model = new Propman();
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
            array('Name',array('search'=>true,'sort'=>true)),
            array('Property Count',array('search'=>true,'sort'=>true)),

            array('Min Lease Term',array('search'=>true,'sort'=>true)),
            array('Max Lease Term',array('search'=>true,'sort'=>true)),
            array('Avg Lease Term',array('search'=>true,'sort'=>false)),
            array('Sum of Lease Term',array('search'=>true,'sort'=>false)),

            array('Min Monthly Rental',array('search'=>true,'sort'=>true)),
            array('Max Monthly Rental',array('search'=>true,'sort'=>true)),
            array('Avg Monthly Rental',array('search'=>true,'sort'=>false)),
            array('Sum of Monthly Rental',array('search'=>true,'sort'=>false)),
            array('Total Annual Rental',array('search'=>true,'sort'=>false)),


            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Property Managements';

        $this->can_add = false;

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('name',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('count',array('kind'=>'numeric','query'=>'like','callback'=>'nodigit','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),

            array('min',array('kind'=>'numeric','query'=>'like','callback'=>'nodigit','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('max',array('kind'=>'numeric','query'=>'like','callback'=>'nodigit','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('avg',array('kind'=>'numeric','query'=>'like','callback'=>'onedigit','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('sum',array('kind'=>'numeric','query'=>'like','callback'=>'nodigit','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),

            array('monthlyMin',array('kind'=>'numeric','query'=>'like','callback'=>'tousd','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('monthlyMax',array('kind'=>'numeric','query'=>'like','callback'=>'tousd','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('monthlyAvg',array('kind'=>'numeric','query'=>'like','callback'=>'tousd','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('monthlyRental',array('kind'=>'numeric','query'=>'like','callback'=>'tousd','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('annualRental',array('kind'=>'numeric','query'=>'like','callback'=>'tousd','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),


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


    public function postDlxl(){

        $this->heads = array(
            array('Name',array('search'=>true,'sort'=>true)),
            array('Property Count',array('search'=>true,'sort'=>true)),

            array('Min Lease Term',array('search'=>true,'sort'=>true)),
            array('Max Lease Term',array('search'=>true,'sort'=>true)),
            array('Avg Lease Term',array('search'=>true,'sort'=>false)),
            array('Sum of Lease Term',array('search'=>true,'sort'=>false)),

            array('Min Monthly Rental',array('search'=>true,'sort'=>true)),
            array('Max Monthly Rental',array('search'=>true,'sort'=>true)),
            array('Avg Monthly Rental',array('search'=>true,'sort'=>false)),
            array('Sum of Monthly Rental',array('search'=>true,'sort'=>false)),
            array('Total Annual Rental',array('search'=>true,'sort'=>false)),

            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );


        $this->fields = array(
            array('name',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('count',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),

            array('min',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('max',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('avg',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('sum',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),

            array('monthlyMin',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('monthlyMax',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('monthlyAvg',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('monthlyRental',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),
            array('annualRental',array('kind'=>'numeric','query'=>'like','pos'=>'both','show'=>true)),


            array('createdDate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('lastUpdate',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
        );

        return parent::postDlxl();

    }

    public function fulladdress($data){
        if(isset($data['address_2'])){
            return $data['address'].' '.$data['address_2'];
        }else{
            return $data['address'];
        }
    }

    public function propAddress($data){
        return $data['number'].' '.$data['address'].' '.$data['state'].' '.$data['zipCode'];
    }

    public function tousd($data, $fieldname = null){
        $fieldname = is_null($fieldname)? 'total_purchase':$fieldname;
        return '$'.Ks::usd($data[$fieldname]);
    }

    public function nodigit($data, $fieldname = null){
        $fieldname = is_null($fieldname)? 'roi':$fieldname;
        return number_format($data[$fieldname],0);
    }

    public function onedigit($data, $fieldname = null){
        $fieldname = is_null($fieldname)? 'roi':$fieldname;
        return number_format($data[$fieldname],1);
    }

    public function twodigit($data, $fieldname = null){
        $fieldname = is_null($fieldname)? 'roi':$fieldname;
        return number_format($data[$fieldname],2);
    }

    public function usnumber($data, $fieldname = null){
        $fieldname = is_null($fieldname)? 'roi':$fieldname;
        return number_format( (double) $data[$fieldname],0,'.',',');
    }

    public function roi($data){
        return number_format($data['ROI'],1);
    }

    public function roistar($data){
        return number_format($data['ROIstar'],1);
    }

    public function rentalyield($data){
        return number_format($data['RentalYield'],1);
    }

    public function opr($data){
        return number_format($data['OPR'],2);
    }

    public function statcolor($data){
        return '<span class="'.$data['orderStatus'].'">'.$data['orderStatus'].'</span>';
    }

    public function makeActions($data)
    {
        $change = '<span class="chg act" data-status="'.$data['orderStatus'].'" rel="'.$data['orderNumber'].'" id="'.$data['_id'].'" ><i class="icon-edit"></i> Change Status</span>';
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


