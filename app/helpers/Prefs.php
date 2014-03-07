<?php

class Prefs {

    public static $category;
    public static $faqcategory;

    public function __construct()
    {

    }

    public static function getCategory(){
        $c = Category::get();

        self::$category = $c;
        return new self;
    }

    public function catToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$category as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function catToArray()
    {
        return self::$category;
    }


    public static function getFAQCategory(){
        $c = Faqcat::get();

        self::$faqcategory = $c;
        return new self;
    }

    public function FAQcatToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$faqcategory as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function FAQcatToArray()
    {
        return self::$faqcategory;
    }



    public static function themeAssetsUrl()
    {
        return URL::to('/').'/'.Theme::getCurrentTheme();
    }

    public static function themeAssetsPath()
    {
        return 'themes/'.Theme::getCurrentTheme().'/assets/';
    }

    public static function getActiveTheme()
    {
        return Config::get('kickstart.default_theme');
    }

}
