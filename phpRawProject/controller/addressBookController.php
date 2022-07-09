<?php
require 'model/addressbookModel.php';
require 'model/addressbook.php';
require 'model/allcities.php';
require_once 'config.php';

session_status() === PHP_SESSION_ACTIVE ? true : session_start();

class addressBookController
{


    function __construct()
    {
        $this->objconfig = new config();
        $this->objsm = new addressbookModel($this->objconfig);
    }


    // mvc handler request
    public function mvcHandler()
    {
        $act = isset($_GET['act']) ? $_GET['act'] : null;
        switch ($act) {
            case 'add':
                $this->insert();
                break;
            case 'addNewItem':
                $this->addingAnotherItem();
                break;
            case 'exportToJson':
                $this->exportDataToJson();
                break;
            case 'exportToXML':
                $this->exportDataToXml();
                break;
            case 'update':
                $this->update();
                break;
            case 'delete':
                $this->delete();
                break;
            case 'inserted':
                $this->selectcities();
                break;
            default:
             $this->list();
            
        }
    }


    // page redirection
    public function pageRedirect($url)
    {
        header('Location:' . $url);
    }


    // check validation
    public function checkValidation($details)
    {
        $noerror = true;
        // Validate category
        if (empty($details->category)) {
            $details->category_msg = 'Field is empty.';
            $noerror = false;
        } elseif (
            !filter_var($details->category, FILTER_VALIDATE_REGEXP, [
                'options' => ['regexp' => '/^[a-zA-Z\s]+$/'],
            ])
        ) {
            $details->category_msg = 'Invalid entry.';
            $noerror = false;
        } else {
            $details->category_msg = '';
        }


        // Validate name
        if (empty($details->name)) {
            $details->name_msg = 'Field is empty.';
            $noerror = false;
        } elseif (
            !filter_var($details->name, FILTER_VALIDATE_REGEXP, [
                'options' => ['regexp' => '/^[a-zA-Z\s]+$/'],
            ])
        ) {
            $details->name_msg = 'Invalid entry.';
            $noerror = false;
        } else {
            $details->name_msg = '';
        }
        



    // Validate street
        if (empty($details->street)) {
            $details->street_msg = 'Field is empty.';
            $noerror = false;
        } elseif (
            !filter_var($details->street, FILTER_VALIDATE_REGEXP, [
                'options' => ['regexp' => '/^[a-zA-Z\s]+$/'],
            ])
        ) {
            $details->street_msg = 'Invalid entry.';
            $noerror = false;
        } else {
            $details->street_msg = '';
        } 



    // Validate zip_code
        if (empty($details->zip_code)) {
            $details->zip_code_msg = 'Field is empty.';
            $noerror = false;
        }
        else {
            $details->zip_code_msg = '';
        }




        // Validate street
        if (empty($details->city)) {
            $details->city_msg = 'Field is empty.';
            $noerror = false;
        } elseif (
            !filter_var($details->city, FILTER_VALIDATE_REGEXP, [
                'options' => ['regexp' => '/^[a-zA-Z\s]+$/'],
            ])
        ) {
            $details->city_msg = 'Invalid entry.';
            $noerror = false;
        } else {
            $details->city_msg = '';
        } 


        return $noerror;
    }



 //Function that loads form for adding new item
    public function addingAnotherItem()  
    {
        $dropdownCity = new allCities();
        
        $res = $this->objsm->getCities();
       
            $myarray =array();
            while($data= mysqli_fetch_array($res)){
               
                array_push($myarray,$data["city_name"]);
            }
            $dropdownCity->citis = $myarray;
            $_SESSION['detailsDisplay'] = serialize($dropdownCity->citis);
             $this->pageRedirect('view/dataRetrieve.php');
    }



    // add new record
    public function insert()
    {
        try {
            $dropdownCity = new allCities();
        
            $res = $this->objsm->getCities();


            $myarray =array();
            while($data= mysqli_fetch_array($res)){
               
                array_push($myarray,$data["city_name"]);
            }
            $dropdownCity->citis = $myarray;
            $_SESSION['detailsDisplay'] = serialize($dropdownCity->citis);


            $details = new addressbook();
            if (isset($_POST['addbtn'])) {
                // read form value
                $details->category = trim($_POST['category']);
                $details->name = trim($_POST['name']);
                $details->street = trim($_POST['street']);
                $details->zip_code = trim($_POST['zip_code']);
                $details->city = trim($_POST['city']);



  

                //call validation
                $chk = $this->checkValidation($details);
               
                if ($chk) {
 
                    //call insert record function in addressbook model
                    $pid = $this->objsm->insertRecord($details);
                   
                    if ($pid > 0) {
                        $this->list();
                    } else {
                        echo 'Somthing is wrong..., try again.';
                    }
                } else {
                    echo 'Check errors and correct....';
                    $this->addingAnotherItem();
                    
                }
            }
        } catch (Exception $e) {
            $this->close_db();
            throw $e;
        }
    }

 
 

    // update record
    public function update()
    {
        try {
            if (isset($_POST['updatebtn'])) {
                $details = unserialize($_SESSION['detailsDisplay']);
                $details->id = trim($_POST['id']);
                $details->category = trim($_POST['category']);
                $details->name = trim($_POST['name']);
                $details->street = trim($_POST['street']);
                $details->zip_code = trim($_POST['zip_code']);
                $details->city = trim($_POST['city']);


                // check validation
                $chk = $this->checkValidation($details);
                if ($chk) {
                    $res = $this->objsm->updateRecord($details);
                    if ($res) {
                        $this->list();
                    } else {
                        echo 'Somthing is wrong..., try again.';
                    }
                } else {
                    $_SESSION['detailsDisplay'] = serialize($details);
                    $this->pageRedirect('view/update.php');
                }
            } elseif (isset($_GET['id']) && !empty(trim($_GET['id']))) {
                $id = $_GET['id'];
                $result = $this->objsm->selectRecord($id);
                $row = mysqli_fetch_array($result);
                $dropdownCity = new allCities();
                $details = new addressbook();
                $res = $this->objsm->getCities();
                $myarray =array();
                while($data= mysqli_fetch_array($res)){
                    array_push($myarray,$data["city_name"]);
                }
                $dropdownCity->citis = $myarray;
            
                $details->id = $row['id'];
                $details->category = $row['category'];
                $details->name = $row['name'];
                $details->street = $row['street'];
                $details->zip_code = $row['zip_code'];
                $details->city = $row['city'];
                $_SESSION['detailsDisplay'] = serialize($details);
                $_SESSION['spotbCities'] = serialize($dropdownCity->citis);
                $this->pageRedirect('view/update.php');
            } else {
                echo 'Invalid operation.';
            }
        } catch (Exception $e) {
            $this->close_db();
            throw $e;
        }
    }




    // delete record
    public function delete()
    {
        try {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $res = $this->objsm->deleteRecord($id);
                if ($res) {
                    $this->pageRedirect('index.php');
                } else {
                    echo 'Somthing is wrong..., try again.';
                }
            } else {
                echo 'Invalid operation.';
            }
        } catch (Exception $e) {
            $this->close_db();
            throw $e;
        }
    }




    //list cities
    public function list()
    {  
        $result = $this->objsm->selectRecord(0);
        include 'view/list.php';  
    }

 
    //Function that exports data to  xml format
    public function exportDataToXml()  
    { 
        $res = $this->objsm->getXML(); 
        if ($res) {
            echo "Successfully created xml."; 
           $this->list();
         } else {
            echo "Error creating xml."; 
       $this->list();
        }
            
    }



    //Function that exports data to json format
    public function exportDataToJson()  
    {
        $res = $this->objsm->getJSON(); 
         if ($res) {
            echo "Successfully created json."; 
           $this->list();
         } else {
            echo "Error creating json."; 
       $this->list();
        }
    }
 

 }

?>
