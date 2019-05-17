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


  function get_city_edit($city_id){
    $id=base64_decode($city_id)/98765;
    $select="SELECT * FROM city_master WHERE id='$id'";
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


    function checkcityexist($city_name,$id){
       $select="SELECT * FROM city_master Where city_name='$city_name' AND id!='$id'";
      $result=$this->db->query($select);
      if($result->num_rows()>0){
           echo "false";
        }else{
          echo "true";
      }
    }

    function checkcitytamilexist($city_ta_name,$id){
       $select="SELECT * FROM city_master Where city_ta_name='$city_ta_name' AND id!='$id'";
      $result=$this->db->query($select);
      if($result->num_rows()>0){
           echo "false";
        }else{
          echo "true";
      }
    }

    function update_locations($city_name,$city_ta_name,$latitude,$longitude,$status,$city_id,$user_id){
        $latlon="$latitude,$longitude";
        $id=base64_decode($city_id)/98765;
       $update="UPDATE city_master SET city_name='$city_name',city_ta_name='$city_ta_name',city_latlon='$latlon',status='$status',created_by='$user_id',updated_at=NOW() WHERE id='$id'";
      $result=$this->db->query($update);
      if($result){
          $data = array("status" => "success");
            return $data;
      }else{
        $data = array("status" => "failed");
          return $data;
      }
    }




    // Category section


    function category_creation($main_cat_name,$main_cat_ta_name,$status,$cat_pic,$user_id){
      $check="SELECT * FROM main_category WHERE main_cat_name='$main_cat_name'";
      $result=$this->db->query($check);
      if($result->num_rows()==0){

              $insert="INSERT INTO main_category(main_cat_name,main_cat_ta_name,cat_pic,status,created_at,created_by) VALUES('$main_cat_name','$main_cat_ta_name','$cat_pic','$status',NOW(),'$user_id')";
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


    function get_all_category(){
      $select="SELECT * FROM main_category ORDER BY id DESC";
      $result=$this->db->query($select);
      return $result->result();
    }

    function get_category_edit($cat_id){
      $id=base64_decode($cat_id)/98765;
      $select="SELECT * FROM main_category WHERE id='$id'";
      $result=$this->db->query($select);
      return $result->result();
    }

    function checkcategory($main_cat_name){
      $select="SELECT * FROM main_category Where main_cat_name='$main_cat_name'";
        $result=$this->db->query($select);
        if($result->num_rows()>0){
          echo "false";
          }else{
            echo "true";
        }
    }

    function checkcategorytamil($main_cat_ta_name){
      $select="SELECT * FROM main_category Where main_cat_ta_name='$main_cat_ta_name'";
        $result=$this->db->query($select);
        if($result->num_rows()>0){
          echo "false";
          }else{
            echo "true";
        }
    }

    function category_update($main_cat_name,$main_cat_ta_name,$status,$cat_pic,$user_id,$cat_id){
      $id=base64_decode($cat_id)/98765;
     $update="UPDATE main_category SET main_cat_name='$main_cat_name',main_cat_ta_name='$main_cat_ta_name',cat_pic='$cat_pic',status='$status',created_by='$user_id',updated_at=NOW() WHERE id='$id'";
    $result=$this->db->query($update);
    if($result){
        $data = array("status" => "success");
          return $data;
    }else{
      $data = array("status" => "failed");
        return $data;
    }
    }


    function checkcategoryexist($main_cat_name,$id){
       $select="SELECT * FROM main_category Where main_cat_name='$main_cat_name' AND id!='$id'";
      $result=$this->db->query($select);
      if($result->num_rows()>0){
           echo "false";
        }else{
          echo "true";
      }
    }

    function checkcategorytamilexist($main_cat_ta_name,$id){
       $select="SELECT * FROM main_category Where main_cat_ta_name='$main_cat_ta_name' AND id!='$id'";
      $result=$this->db->query($select);
      if($result->num_rows()>0){
           echo "false";
        }else{
          echo "true";
      }
    }





}
?>
