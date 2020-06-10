<?php

Class Mastermodel extends CI_Model
{

  public function __construct()
  {
      parent::__construct();
		$this->load->model('smsmodel');
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



    // Tax

    function get_all_tax_commission(){
      $select="SELECT * FROM tax_commission";
      $result=$this->db->query($select);
      return $result->result();
    }


    function get_referal_details(){
      $select="SELECT * FROM referral_master";
      $result=$this->db->query($select);
      return $result->result();
    }


	function update_referal_details($referal_points,$minimum_points_claim,$division_points,$user_id){
     $update="UPDATE referral_master SET division_points='$division_points',minimum_points_to_claim='$minimum_points_claim',referral_points='$referal_points',updated_by='$user_id',updated_at=NOW() WHERE id='1'";

     $result=$this->db->query($update);
     if($result){
         $data = array("status" => "success");
           return $data;
     }else{
       $data = array("status" => "failed");
         return $data;
     }
    }

    function update_tax_percentage($sgst,$cgst,$user_id){
     $update="UPDATE tax_commission SET sgst='$sgst',cgst='$cgst',updated_by='$user_id',updated_at=NOW() WHERE id='1'";
     $result=$this->db->query($update);
     if($result){
         $data = array("status" => "success");
           return $data;
     }else{
       $data = array("status" => "failed");
         return $data;
     }
    }


    // Commission

    function update_commission_percentage($internal_commission,$external_commission,$user_id){
      $update="UPDATE tax_commission SET internal_commission='$internal_commission',external_commission='$external_commission',updated_by='$user_id',updated_at=NOW() WHERE id='1'";
      $result=$this->db->query($update);
      if($result){
          $data = array("status" => "success");
            return $data;
      }else{
        $data = array("status" => "failed");
          return $data;
      }
    }


    function update_deposit_amt($deposit_amt,$user_id){
      $update="UPDATE tax_commission SET deposit_amt='$deposit_amt',updated_by='$user_id',updated_at=NOW() WHERE id='1'";
      $result=$this->db->query($update);
      if($result){
          $data = array("status" => "success");
            return $data;
      }else{
        $data = array("status" => "failed");
          return $data;
      }
    }

    function update_trending_count($trending_count,$user_id){
       $update="UPDATE tax_commission SET trending_count='$trending_count',updated_by='$user_id',updated_at=NOW() WHERE id='1'";
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
      // $check="SELECT * FROM main_category WHERE main_cat_name='$main_cat_name'";
      // $result=$this->db->query($check);
      // if($result->num_rows()==0){
              $get_cat_postion="SELECT * FROM main_category order by id desc limit 1";
              $result_cat_postion=$this->db->query($get_cat_postion);
              foreach($result_cat_postion->result() as $rows_cat_position){}
              $cat_postion=$rows_cat_position->cat_position+1;

              $insert="INSERT INTO main_category(main_cat_name,main_cat_ta_name,cat_pic,status,cat_position,created_at,created_by) VALUES('$main_cat_name','$main_cat_ta_name','$cat_pic','$status','$cat_postion',NOW(),'$user_id')";
              $result=$this->db->query($insert);
              if($result){
                  $data = array("status" => "success");
                    return $data;
              }else{
                $data = array("status" => "failed");
                  return $data;
              }

      //   }else{
      //
      //     $data = array("status" => "Already exist");
      //       return $data;
      // }
    }



    function update_cat_position($data = array()){
      $i=1;
      foreach ($data as $key => $value) {
               $sql = "UPDATE main_category SET cat_position=".$i." WHERE id=".$value;

            $query = $this->db->query($sql);
      $i++;
      }
    }


    function get_all_category(){
      $select="SELECT * FROM main_category ORDER BY cat_position asc";
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
      // $check="SELECT * FROM sub_category WHERE sub_cat_name='$sub_cat_name'";
      // $result=$this->db->query($check);
      // if($result->num_rows()==0){
      $get_cat_postion="SELECT * FROM sub_category where main_cat_id='$main_cat_id' order by id desc limit 1";
      $result_cat_postion=$this->db->query($get_cat_postion);
      foreach($result_cat_postion->result() as $rows_cat_position){}
      $sub_cat_postion=$rows_cat_position->sub_cat_position+1;


              $insert="INSERT INTO sub_category(main_cat_id,sub_cat_name,sub_cat_ta_name,status,sub_cat_position,sub_cat_pic,created_at,created_by) VALUES('$main_cat_id','$sub_cat_name','$sub_cat_ta_name','$status','$sub_cat_postion','$cat_pic',NOW(),'$user_id')";
              $result=$this->db->query($insert);
              if($result){
                  $data = array("status" => "success");
                    return $data;
              }else{
                $data = array("status" => "failed");
                  return $data;
              }

      //   }else{
      //
      //     $data = array("status" => "Already exist");
      //       return $data;
      // }
    }


          function update_sub_cat_position($data = array()){
            $i=1;
            foreach ($data as $key => $value) {
                    $select="SELECT * FROM sub_category where id='$value'";
                    $result=$this->db->query($select);
                    foreach($result->result() as $rows_sub){}
                      $main_cat_id=$rows_sub->main_cat_id;
                    $sql = "UPDATE sub_category SET sub_cat_position='$i' WHERE id='$value' AND main_cat_id='$main_cat_id'";
                  $query = $this->db->query($sql);
            $i++;
            }
          }



        function get_all_sub_category($id){
            $main_cat_id=base64_decode($id)/98765;
          $select="SELECT * FROM sub_category WHERE main_cat_id='$main_cat_id' ORDER BY sub_cat_position asc";
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


        function service_creation($service_name,$service_ta_name,$status,$cat_pic,$user_id,$sub_cat_id,$is_advance_payment,$advance_amount,$rate_card,$rate_card_details,$rate_card_details_ta,$inclusions,$inclusions_ta,$exclusion,$exclusions_ta,$service_procedure,$service_procedure_ta,$others,$others_ta){

          // $check="SELECT * FROM services WHERE service_name='$service_name'";
          // $result=$this->db->query($check);
          // if($result->num_rows()==0){
          $get_cat_postion="SELECT * FROM services  where sub_cat_id='$sub_cat_id' order by id desc limit 1";
          $result_cat_postion=$this->db->query($get_cat_postion);
          foreach($result_cat_postion->result() as $rows_cat_position){}
          $service_postion=$rows_cat_position->service_position+1;


                  $get_main_cat_id="SELECT * FROM sub_category WHERE id='$sub_cat_id'";
                  $result_main=$this->db->query($get_main_cat_id);
                  $res=$result_main->result();
                  foreach($res as $rows){} $main_cat_id=$rows->main_cat_id;
                  $insert="INSERT INTO services(service_position,main_cat_id,sub_cat_id,service_name,service_ta_name,is_advance_payment,advance_amount,rate_card,rate_card_details,rate_card_details_ta,inclusions,inclusions_ta,exclusions,exclusions_ta,service_procedure,service_procedure_ta,others,others_ta,status,service_pic,created_at,created_by) VALUES('$service_postion','$main_cat_id','$sub_cat_id','$service_name','$service_ta_name','$is_advance_payment','$advance_amount','$rate_card','$rate_card_details','$rate_card_details_ta','$inclusions','$inclusions_ta','$exclusion','$exclusions_ta','$service_procedure','$service_procedure_ta','$others','$others_ta','$status','$cat_pic',NOW(),'$user_id')";
                  $result=$this->db->query($insert);
                  if($result){
                      $data = array("status" => "success");
                        return $data;
                  }else{
                    $data = array("status" => "failed");
                      return $data;
                  }

          //   }else{
          //
          //     $data = array("status" => "Already exist");
          //       return $data;
          // }

        }

        function update_service_position($data = array()){
          $i=1;
          foreach ($data as $key => $value) {
                  $select="SELECT * FROM services where id='$value'";
                  $result=$this->db->query($select);
                  foreach($result->result() as $rows_sub){}
                    $sub_cat_id=$rows_sub->sub_cat_id;
                  $sql = "UPDATE services SET service_position='$i' WHERE id='$value' AND sub_cat_id='$sub_cat_id'";
                 $query = $this->db->query($sql);
          $i++;
          }
        }

        function get_all_service($id){
          $sub_cat_id=base64_decode($id)/98765;
          $select="SELECT * FROM services WHERE sub_cat_id='$sub_cat_id' ORDER BY service_position asc";
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


        function service_update($service_name,$service_ta_name,$status,$cat_pic,$user_id,$service_id,$is_advance_payment,$advance_amount,$rate_card,$rate_card_details,$rate_card_details_ta,$inclusions,$inclusions_ta,$exclusion,$exclusions_ta,$service_procedure,$service_procedure_ta,$others,$others_ta){
			$id=base64_decode($service_id)/98765;
			  if($is_advance_payment=='Y'){
				$advance=$advance_amount;
			  }else{
				  $advance='0';
			  }
			$update="UPDATE services SET  service_name='$service_name',service_ta_name='$service_ta_name',service_pic='$cat_pic',status='$status',created_by='$user_id',updated_at=NOW(),is_advance_payment='$is_advance_payment',advance_amount='$advance',rate_card='$rate_card',rate_card_details='$rate_card_details',rate_card_details_ta='$rate_card_details_ta',inclusions='$inclusions',inclusions_ta='$inclusions_ta',exclusions='$exclusion',exclusions_ta='$exclusions_ta',service_procedure='$service_procedure',service_procedure_ta='$service_procedure_ta',others='$others',others_ta='$others_ta' WHERE id='$id'";
			$result=$this->db->query($update);
			if($result){
				$data = array("status" => "success");
				  return $data;
			}else{
				$data = array("status" => "failed");
				return $data;
			}
        }

        // Banner section

        function get_banner(){
          $check="SELECT * FROM banners order by id desc";
          $result=$this->db->query($check);
          return $result->result();

        }


        function get_banner_edit($ban_id){
          $id=base64_decode($ban_id)/98765;
          $check="SELECT * FROM banners WHERE id='$id'";
          $result=$this->db->query($check);
          return $result->result();
        }


        function create_banner($banner_title,$pic,$status,$user_id){
          $check="SELECT * FROM banners WHERE banner_title='$banner_title'";
          $result=$this->db->query($check);
          if($result->num_rows()==0){
            $insert="INSERT INTO banners(banner_title,banner_img,status,created_at,created_by) VALUES('$banner_title','$pic','$status',NOW(),'$user_id')";
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


        function update_banner($banner_title,$pic,$status,$ban_id,$user_id){
            $id=base64_decode($ban_id)/98765;
          $query="UPDATE banners SET banner_title='$banner_title',banner_img='$pic',status='$status',updated_at=NOW(),updated_by='$user_id' WHERE id='$id'";
          $result=$this->db->query($query);
            if($result){
                $data = array("status" => "success");
                return $data;
            }else{
              $data = array("status" => "failed");
              return $data;
            }

        }

	   function send_sms_content($sms_content){
		    $select="SELECT *  FROM `login_users` WHERE `user_type` = 5 AND `mobile_verify` LIKE 'Y' ORDER BY `user_type` ASC ";
            $result=$this->db->query($select);
                  foreach($result->result() as $rows_sub){
                    $phone_no=$rows_sub->phone_no;
					$this->smsmodel->send_sms($phone_no,$sms_content);
                }
			if($result){
					$data = array("status" => "success");
				}else{
					$data = array("status" => "failed");
				}
			return $data;
       }

        function distance_rates(){
          $check="SELECT * FROM surge_master order by id";
          $result=$this->db->query($check);
          return $result->result();
        }

		function check_km($frm_km)
		{
			$select="SELECT * FROM surge_master Where surge_distance='$frm_km'";
			$result = $this->db->query($select);
			  if($result->num_rows()>0){
					echo "false";
				}else{
					echo "true";
			  }
		}


		function check_km_exist($rate_id,$frm_km)
		{
			$select="SELECT * FROM surge_master WHERE id != '$rate_id' AND surge_distance = '$frm_km'";
			$result = $this->db->query($select);
			  if($result->num_rows()>0){
					echo "false";
				}else{
					echo "true";
			  }
        }

		function add_distance_rates($from_km,$rates,$status,$user_id){
          $check="SELECT * FROM surge_master WHERE surge_distance='$from_km'";
          $result=$this->db->query($check);
          if($result->num_rows()==0){
            $insert="INSERT INTO surge_master(surge_distance,surge_price,status,created_at,created_by) VALUES('$from_km','$rates','$status',NOW(),'$user_id')";
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


        function edit_distance_rates($rate_id){
		  $id=base64_decode($rate_id)/98765;
          $check="SELECT * FROM surge_master WHERE id='$id'";
          $result=$this->db->query($check);
          return $result->result();
        }


		function update_distance_rates($rate_id,$from_km,$rates,$status,$user_id){
			$id=base64_decode($rate_id)/98765;
			$update="UPDATE surge_master SET surge_distance='$from_km',surge_price='$rates',status='$status',updated_by='$user_id',updated_at=NOW() WHERE id='$id'";
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
