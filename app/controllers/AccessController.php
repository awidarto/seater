<?php

class AccessController extends AdminController {

    public function __construct()
    {
        parent::__construct();

        $this->controller_name = str_replace('Controller', '', get_class());

        //$this->crumb = new Breadcrumb();
        $this->crumb->append('Home','left',true);
        $this->crumb->append(strtolower($this->controller_name));

        $this->model = new Accesslog();
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
'REDIRECT_STATUS' => '200',
  'HTTP_HOST' => 'localhost',
  'HTTP_CONNECTION' => 'keep-alive',
  'CONTENT_LENGTH' => '1057',
  'HTTP_ACCEPT' => 'application/json, text/javascript, *; q=0.01',
  'HTTP_ORIGIN' => 'http://localhost',
  'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
  'HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.77 Safari/537.36',
  'CONTENT_TYPE' => 'application/x-www-form-urlencoded; charset=UTF-8',
  'HTTP_REFERER' => 'http://localhost/iadmin/public/agent',
  'HTTP_ACCEPT_ENCODING' => 'gzip,deflate,sdch',
  'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8,af;q=0.6,da;q=0.4,es;q=0.2,id;q=0.2,ms;q=0.2',
  'HTTP_COOKIE' => 'stage_jayon_admin=a%3A4%3A%7Bs%3A10%3A%22session_id%22%3Bs%3A32%3A%221e4fd9075855f751c4e7aecd8e90cdc0%22%3Bs%3A10%3A%22ip_address%22%3Bs%3A7%3A%220.0. [...]',
  'PATH' => '/usr/bin:/bin:/usr/sbin:/sbin',
  'SERVER_SIGNATURE' =>
  'SERVER_SOFTWARE' => 'Apache/2.2.24 (Unix) PHP/5.4.13 DAV/2',
  'SERVER_NAME' => 'localhost',
  'SERVER_ADDR' => '::1',
  'SERVER_PORT' => '80',
  'REMOTE_ADDR' => '::1',
  'DOCUMENT_ROOT' => '/Library/WebServer/Documents',
  'SERVER_ADMIN' => 'you@example.com',
  'SCRIPT_FILENAME' => '/Library/WebServer/Documents/iadmin/public/index.php',
  'REMOTE_PORT' => '49413',
  'REDIRECT_URL' => '/iadmin/public/agent',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'REQUEST_METHOD' => 'POST',
  'QUERY_STRING' => '',
  'REQUEST_URI' => '/iadmin/public/agent',
  'SCRIPT_NAME' => '/iadmin/public/index.php',
  'PHP_SELF' => '/iadmin/public/index.php',
  'REQUEST_TIME_FLOAT' => 1390369027.802,
  'REQUEST_TIME' => new MongoInt32(1390369027),
  'updated_at' => new MongoDate(1390369027, 990000),
  'created_at' => new MongoDate(1390369027, 990000),
*/
        $this->heads = array(
            array('REQUEST_TIME',array('search'=>true,'sort'=>true,'date'=>true)),
            array('HTTP_REFERER',array('search'=>true,'sort'=>false)),
            array('REQUEST_URI',array('search'=>true,'sort'=>true)),
            array('REMOTE_ADDR',array('search'=>true,'sort'=>true)),
            array('REDIRECT_STATUS',array('search'=>true,'sort'=>true)),
        );

        //print $this->model->where('docFormat','picture')->get()->toJSON();

        $this->title = 'Site Access';

        return parent::getIndex();

    }

    public function postIndex()
    {

        $this->fields = array(
            array('created_at',array('kind'=>'datetime','query'=>'like','pos'=>'both','show'=>true)),
            array('HTTP_REFERER',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('REQUEST_URI',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
            array('REMOTE_ADDR',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true,'attr'=>array('class'=>'expander'))),
            array('REDIRECT_STATUS',array('kind'=>'text','query'=>'like','pos'=>'both','show'=>true)),
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
        $edit = '<a href="'.URL::to('agent/edit/'.$data['_id']).'"><i class="icon-edit"></i>Update</a>';

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
