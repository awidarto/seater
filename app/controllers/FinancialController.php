<?php

class FinancialController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

        $this->model = new Property();
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
            array('Property ID',array('search'=>true,'sort'=>true)),
            array('Property Address',array('search'=>true,'sort'=>true)),
            array('Listing Price',array('search'=>true,'sort'=>true)),
            array('FMV',array('search'=>true,'sort'=>true)),
            array('Equity',array('search'=>false,'sort'=>false)),

            array('Bed',array('search'=>true,'sort'=>false)),
            array('Bath',array('search'=>true,'sort'=>true)),
            array('Pool',array('search'=>true,'sort'=>true)),
            array('Garage',array('search'=>true,'sort'=>true)),
            array('Basement',array('search'=>true,'sort'=>true)),

            array('Year Built',array('search'=>true,'sort'=>true)),
            array('Sqft',array('search'=>true,'sort'=>true)),
            array('Lot Size',array('search'=>true,'sort'=>true)),
            array('Section 8',array('search'=>true,'sort'=>true)),
            array('Monthly Rent',array('search'=>true,'sort'=>true)),
            array('Tax',array('search'=>true,'sort'=>true)),
            array('Insurance',array('search'=>true,'sort'=>true)),
            array('Prop Mgmt',array('search'=>true,'sort'=>true)),
            array('Rental Yield',array('search'=>true,'sort'=>true)),
            array('ROI',array('search'=>true,'sort'=>true)),
            array('ROI*',array('search'=>true,'sort'=>true)),
            array('OPR',array('search'=>true,'sort'=>true)),

            array('Created',array('search'=>true,'sort'=>true,'date'=>true)),
            array('Last Update',array('search'=>true,'sort'=>true,'date'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Financial Return';

        $this->can_add = false;

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('propertyId',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('address',array('kind'=>'text','callback'=>'propAddress','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('listingPrice',array('kind'=>'text' ,'query'=>'like','pos'=>'both','show'=>true)),
            array('FMV',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('equity',array('kind'=>'text', 'callback'=>'statcolor' ,'query'=>'like','pos'=>'both','show'=>true)),


            array('bed',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('bath',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('pool',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('garage',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('basement',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),

            array('yearBuilt',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('houseSize',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('lotSize',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('section8',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('monthlyRental',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('tax',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('insurance',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),

            array('propManagement',array('kind'=>'text','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('RentalYield',array('kind'=>'text','callback'=>'rentalyield','query'=>'like','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),

            array('ROI',array('kind'=>'text','query'=>'like','callback'=>'roi','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('ROIstar',array('kind'=>'text','query'=>'like','callback'=>'roistar','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),
            array('OPR',array('kind'=>'text','query'=>'like','callback'=>'opr','pos'=>'both','attr'=>array('class'=>'expander'),'show'=>true)),


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

    public function propAddress($data){
        return $data['number'].' '.$data['address'].' '.$data['state'].' '.$data['zipCode'];
    }

    public function etousd($data){
        return '$'.Ks::usd($data['earnestMoney']);
    }

    public function tousd($data){
        return '$'.Ks::usd($data['total_purchase']);
    }

    public function roi($data){
        return number_format($data['ROI'],0);
    }

    public function roistar($data){
        return number_format($data['ROIstar'],0);
    }

    public function rentalyield($data){
        return number_format($data['RentalYield'],1);
    }

    public function opr($data){
        return number_format($data['OPR'],1);
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


