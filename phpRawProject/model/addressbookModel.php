<?php

class addressbookModel
{
    // set database config for mysql
    function __construct($consetup)
    {
        $this->host = $consetup->host;
        $this->user = $consetup->user;
        $this->pass = $consetup->pass;
        $this->db = $consetup->db;
    }
    // open mysql data base
    public function open_db()
    {
        $this->condb = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );
        if ($this->condb->connect_error) {
            die('Erron in connection: ' . $this->condb->connect_error);
        }
    }


    // close database
    public function close_db()
    {
        $this->condb->close();
    }


    //get records for json
    public function getJSON(){
        $success = true;
        $this->open_db();
        $query = $this->condb->prepare('SELECT * FROM addressbooks');
            try{
                if ($query === false) {
                    die(mysqli_error($this->condb));
                } else {
                    $query->execute();
                    $res = $query->get_result();
                }


                while( $row = mysqli_fetch_array($res) )
                {
                    $category=$row['category'];
                    $name=$row['name'];
                    $street=$row['street'];
                    $zip_code=$row['zip_code'];
                    $city=$row['city']; 
                    $posts[] = array('category'=> $category, 'name'=> $name, 'street'=> $street, 'zip_code'=> $zip_code, 'city'=> $city);
                } 

            $response['address_book'] = $posts;
            $fp = fopen('addressbook.json', 'w');

 
            if (json_encode($response) != null){
                $success = true;
            }else{
                $success = false;
            }
   
            fwrite($fp, json_encode($response));
            fclose($fp);
            }catch(Exception $e){
                $this->close_db();
                throw $e;
            }

            return $success;
        }




        //get xml records
        public function getXML(){
            try{

                $success = true;

                $this->open_db();
                $query = $this->condb->prepare('SELECT * FROM addressbooks');
                
                if ($query === false) {
                    die(mysqli_error($this->condb));
                } else {
                    $query->execute();
                    $res = $query->get_result();
                }

                $str ="<?xml version='1.0' encoding='UTF-8'?>\n<addressbook>";


                while( $row = mysqli_fetch_array($res) )
                {
                    $category=$row['category'];
                    $name=$row['name'];
                    $street=$row['street'];
                    $zip_code=$row['zip_code'];
                    $city=$row['city']; 
                    
                    $str .= "\n\t<details>\n\t\t\t<category>$category</category>\n\t\t\t<name>$name</name> ";

                    $str .= "\n\t\t\t  <street>$street</street>\n\t\t\t<zipcode>$zip_code</zipcode>\n\t\t\t<city>$city</city>\n</details>";


                }
                
                $str.= "\n</addressbook>";
                
                // echo $str;

                if ($str != null) {
                    
                    $success = true;
                }else{
                     
                    $success = false;
                }
                
                
                $file_name="addressbooksXml.xml";   

                $fp = fopen ($file_name, "w");  
                
                fwrite ($fp,$str);          
                fclose ($fp);                                
                chmod($file_name,0777);  

            }catch(Exception $e){
                $this->close_db();
                throw $e;
            }

            return $success;
        }





    // getting all cities
    public function getCities(){
        try{

            $this->open_db();

            $query = $this->condb->prepare('SELECT * FROM city');


            if ($query === false) {
                die(mysqli_error($this->condb));
            } else {
                $query->execute();
            }
            $res = $query->get_result();
            // var_dump($res); exit();
            $query->close();
            $this->close_db();
            return $res;

        }catch(Exception $e){
            $this->close_db();
            throw $e;
        }
    }



    // insert record
    public function insertRecord($obj)
    {
        try {
            $this->open_db(); 

            $query = $this->condb->prepare(
                'INSERT INTO addressbooks (category,name, street, zip_code, city) VALUES (?, ?, ?, ?, ?)'
            );
            $query->bind_param('sssss', $obj->category, $obj->name, $obj->street, $obj->zip_code, $obj->city);
            $query->execute();
            $res = $query->get_result();
            $last_id = $this->condb->insert_id;
            $query->close();
            $this->close_db();
            return $last_id;
        } catch (Exception $e) {
            $this->close_db();
            throw $e;
        }
    }


    



    //update record
    public function updateRecord($obj)
    {
        try {
            $this->open_db();


            $quer = $this->condb->prepare('SELECT * FROM city'); 
            
            $query = $this->condb->prepare(
                'UPDATE addressbooks SET category=?,name=?,street=?,zip_code=?,city=? WHERE id=?'
            );
            $query->bind_param('sssssi', $obj->category, $obj->name, $obj->street, $obj->zip_code, $obj->city, $obj->id);
            $query->execute();
            $res = $query->get_result();
            $query->close();
            $this->close_db();
            return true;
        } catch (Exception $e) {
            $this->close_db();
            throw $e;
        }
    }



    // delete record
    public function deleteRecord($id)
    {
        try {
            $this->open_db();
            $query = $this->condb->prepare(
                'DELETE FROM addressbooks WHERE id=?'
            );
            $query->bind_param('i', $id);
            $query->execute();
            $res = $query->get_result();
            $query->close();
            $this->close_db();
            return true;
        } catch (Exception $e) {
            $this->closeDb();
            throw $e;
        }
    }

    

    // select record
    public function selectRecord($id)
    {
        try {
            $this->open_db();
            if ($id > 0) {
                $query = $this->condb->prepare(
                    'SELECT * FROM addressbooks WHERE id=?'
                );

                if ($query === false) {
                    die(mysqli_error($db = config::getInstance()));
                }

                $query->bind_param('i', $id);
            } else {
                $query = $this->condb->prepare('SELECT * FROM addressbooks');
            }

            if ($query === false) {
                die(mysqli_error($this->condb));
            } else {
                $query->execute();
            }
            $res = $query->get_result();
            $query->close();
            $this->close_db();
            return $res;
        } catch (Exception $e) {
            $this->close_db();
            throw $e;
        }
    }
}

?>
