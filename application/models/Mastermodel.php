<?php

Class Mastermodel extends CI_Model
{

  public function __construct()
  {
      parent::__construct();


  }



  function city_creation($city_name,$city_ta_name,$latitude,$longitude,$status,$user_id){


    $check="SELECT * FROM city_master WHERE city_name='$city_name'";
    $result=$this->db->query($check);
    if($result->num_rows()==0){
            $latlon="$latitude,$longitude";
            $insert="INSERT INTO city_master(city_name,city_ta_name,city_latlon,status,created_at,created_by) VALUES('$city_name','$city_ta_name','$latlon','$status',NOW(),'$user_id')";
            $result=$this->db->query($insert);
            if($result){
                $data = array("status" => "success");
                  return $data;
            }else{
              $data = array("status" => "failed");
                return $data;
            }

      }else{

        $data = array("status" => "Already exist");
          return $data;
    }

  }


  function get_all_locations(){
    $select="SELECT * FROM city_master ORDER BY id DESC";
    $result=$this->db->query($select);
    return $result->result();
  }



  function checkcity($city_name){
    $select="SELECT * FROM city_master Where city_name='$city_name'";
      $result=$this->db->query($select);
      if($result->num_rows()>0){
        echo "false";
        }else{
          echo "true";
      }
  }

  function checkcityname($city_ta_name){
    $select="SELECT * FROM city_master Where city_ta_name='$city_ta_name'";
      $result=$this->db->query($select);
      if($result->num_rows()>0){
        echo "false";
        }else{
          echo "true";
      }
  }




}
?>
