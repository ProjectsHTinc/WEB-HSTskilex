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




    //Sub  Category creation


    function sub_category_creation($sub_cat_name,$sub_cat_ta_name,$status,$cat_pic,$user_id,$main_cat_id){
      $check="SELECT * FROM sub_category WHERE sub_cat_name='$sub_cat_name'";
      $result=$this->db->query($check);
      if($result->num_rows()==0){

              $insert="INSERT INTO sub_category(main_cat_id,sub_cat_name,sub_cat_ta_name,status,sub_cat_pic,created_at,created_by) VALUES('$main_cat_id','$sub_cat_name','$sub_cat_ta_name','$status','$cat_pic',NOW(),'$user_id')";
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


        function get_all_sub_category($id){
            $main_cat_id=base64_decode($id)/98765;
          $select="SELECT * FROM sub_category WHERE main_cat_id='$main_cat_id' ORDER BY id DESC";
          $result=$this->db->query($select);
          return $result->result();
        }

        function get_sub_category_edit($cat_id){
          $id=base64_decode($cat_id)/98765;
          $select="SELECT * FROM sub_category WHERE id='$id'";
          $result=$this->db->query($select);
          return $result->result();
        }

        function checksubcategory($sub_cat_name){
          $select="SELECT * FROM sub_category Where sub_cat_name='$sub_cat_name'";
            $result=$this->db->query($select);
            if($result->num_rows()>0){
              echo "false";
              }else{
                echo "true";
            }
        }

        function checksubcategorytamil($sub_cat_ta_name){
          $select="SELECT * FROM sub_category Where sub_cat_ta_name='$sub_cat_ta_name'";
            $result=$this->db->query($select);
            if($result->num_rows()>0){
              echo "false";
              }else{
                echo "true";
            }
        }


        function sub_category_update($sub_cat_name,$sub_cat_ta_name,$status,$cat_pic,$user_id,$cat_id){
          $id=base64_decode($cat_id)/98765;
         $update="UPDATE sub_category SET sub_cat_name='$sub_cat_name',sub_cat_ta_name='$sub_cat_ta_name',sub_cat_pic='$cat_pic',status='$status',created_by='$user_id',updated_at=NOW() WHERE id='$id'";
        $result=$this->db->query($update);
        if($result){
            $data = array("status" => "success");
              return $data;
        }else{
          $data = array("status" => "failed");
            return $data;
        }
        }

        function checksubcategoryexist($sub_cat_name,$id){
           $select="SELECT * FROM sub_category Where sub_cat_name='$sub_cat_name' AND id!='$id'";
          $result=$this->db->query($select);
          if($result->num_rows()>0){
               echo "false";
            }else{
              echo "true";
          }
        }

        function checksubcategorytamilexist($sub_cat_ta_name,$id){
           $select="SELECT * FROM sub_category Where sub_cat_ta_name='$sub_cat_ta_name' AND id!='$id'";
          $result=$this->db->query($select);
          if($result->num_rows()>0){
               echo "false";
            }else{
              echo "true";
          }
        }





        // Service section


        function service_creation($service_name,$service_ta_name,$status,$cat_pic,$user_id,$sub_cat_id,$rate_card,$rate_card_details,$rate_card_details_ta,$inclusions,$inclusions_ta,$exclusion,$exclusions_ta,$service_procedure,$service_procedure_ta,$others,$others_ta){

          $check="SELECT * FROM services WHERE service_name='$service_name'";
          $result=$this->db->query($check);
          if($result->num_rows()==0){

                  $insert="INSERT INTO services(sub_cat_id,service_name,service_ta_name,rate_card,rate_card_details,rate_card_details_ta,inclusions,inclusions_ta,exclusions,exclusions_ta,service_procedure,service_procedure_ta,others,others_ta,status,service_pic,created_at,created_by) VALUES('$sub_cat_id','$service_name','$service_ta_name','$rate_card','$rate_card_details','$rate_card_details_ta','$inclusions','$inclusions_ta','$exclusion','$exclusions_ta','$service_procedure','$service_procedure_ta','$others','$others_ta','$status','$cat_pic',NOW(),'$user_id')";
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


        function get_all_service($id){
            $sub_cat_id=base64_decode($id)/98765;
          $select="SELECT * FROM services WHERE sub_cat_id='$sub_cat_id' ORDER BY id DESC";
          $result=$this->db->query($select);
          return $result->result();
        }


        function get_service_edit($cat_id){
          $id=base64_decode($cat_id)/98765;
          $select="SELECT * FROM services WHERE id='$id'";
          $result=$this->db->query($select);
          return $result->result();
        }

        function checkservice($service_name){
          $select="SELECT * FROM services Where service_name='$service_name'";
            $result=$this->db->query($select);
            if($result->num_rows()>0){
              echo "false";
              }else{
                echo "true";
            }
        }

        function checkservicetamil($service_ta_name){
          $select="SELECT * FROM services Where service_ta_name='$service_ta_name'";
            $result=$this->db->query($select);
            if($result->num_rows()>0){
              echo "false";
              }else{
                echo "true";
            }
        }


        function checkserviceexist($service_name,$id){
           $select="SELECT * FROM services Where service_name='$service_name' AND id!='$id'";
          $result=$this->db->query($select);
          if($result->num_rows()>0){
               echo "false";
            }else{
              echo "true";
          }
        }

        function checkservicetamilexist($service_ta_name,$id){
           $select="SELECT * FROM services Where service_ta_name='$service_ta_name' AND id!='$id'";
          $result=$this->db->query($select);
          if($result->num_rows()>0){
               echo "false";
            }else{
              echo "true";
          }
        }


        function service_update($service_name,$service_ta_name,$status,$cat_pic,$user_id,$service_id,$rate_card,$rate_card_details,$rate_card_details_ta,$inclusions,$inclusions_ta,$exclusion,$exclusions_ta,$service_procedure,$service_procedure_ta,$others,$others_ta){
          $id=base64_decode($service_id)/98765;
          $update="UPDATE services SET  service_name='$service_name',service_ta_name='$service_ta_name',service_pic='$cat_pic',status='$status',created_by='$user_id',updated_at=NOW(),rate_card='$rate_card',rate_card_details='$rate_card_details',rate_card_details_ta='$rate_card_details_ta',inclusions='$inclusions',inclusions_ta='$inclusions_ta',exclusions='$exclusion',exclusions_ta='$exclusions_ta',service_procedure='$service_procedure',service_procedure_ta='$service_procedure_ta',others='$others',others_ta='$others_ta' WHERE id='$id'";
        

        $result=$this->db->query($update);
        if($result){
            $data = array("status" => "success");
              return $data;
        }else{
          $data = array("status" => "failed");
            return $data;
        }
        }




}
?>
