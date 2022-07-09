<?php

class addressbook
{
    // table fields
    public $id;
    public $category;
    public $name;
    public $street;
    public $zip_code;
    public $city;

    // message string
    public $id_msg;
    public $category_msg;
    public $name_msg;
    public $street_msg;
    public $zip_code_msg;
    public $city_msg;
    // constructor set default value
    function __construct()
    {
        $id=0;$category=$name=$street=$zip_code=$city="";
        $id_msg=$category_msg=$name_msg=$street_msg=$zip_code_msg=$city_msg="";
    }
}

?>