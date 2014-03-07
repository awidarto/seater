<?php

class Html2pdf {

    public function __construct()
    {
        require_once('html2pdf/html2pdf.class.php');

        return new HTML2PDF();
    }


}