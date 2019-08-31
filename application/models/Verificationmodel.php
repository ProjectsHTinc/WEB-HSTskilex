<?php

Class Verificationmodel extends CI_Model
{

  public function __construct()
  {
      parent::__construct();


  }




  function get_all_vendors(){
    $select="SELECT lu.id as user_master_id,lu.status as login_status,lu.*,spd.* FROM login_users as lu left join service_provider_details as spd on spd.user_master_id=lu.id where lu.user_type='3' and (spd.serv_prov_verify_status='Rejected' OR spd.serv_prov_verify_status='Pending' OR spd.serv_prov_verify_status='Approved') ORDER BY lu.id DESC";
    $result=$this->db->query($select);
    return $result->result();
  }

  function get_vendor_details($ser_pro_id){
    $id=base64_decode($ser_pro_id)/98765;
    $select="SELECT lu.id as user_master_id,lu.*,spd.* FROM login_users as lu left join service_provider_details as spd on spd.user_master_id=lu.id where lu.user_type='3' and lu.id='$id' and spd.user_master_id='$id'";
    $result=$this->db->query($select);
    return $result->result();
  }


  function update_deposit_status($status,$id){
      $pro_id=base64_decode($id)/98765;
     $update="UPDATE service_provider_details SET deposit_status='$status' WHERE user_master_id='$pro_id'";
      $result=$this->db->query($update);
      if($result){
          $data = array("status" => "success");
            return $data;
      }else{
        $data = array("status" => "failed");
          return $data;
      }
  }

  function update_serv_verify_status($status,$id){
    $pro_id=base64_decode($id)/98765;
    if($status=='Approved'){
      $update_lu="UPDATE login_users SET document_verify='Y' WHERE id='$pro_id'";
      $result_lu=$this->db->query($update_lu);
    }
    $update="UPDATE service_provider_details SET serv_prov_verify_status='$status' WHERE user_master_id='$pro_id'";
    $result=$this->db->query($update);
    if($result){
        $data = array("status" => "success");
          return $data;
    }else{
      $data = array("status" => "failed");
        return $data;
    }
  }

  function update_serv_display_status($status,$id){
    $pro_id=base64_decode($id)/98765;
    $update="UPDATE login_users SET status='$status' WHERE id='$pro_id'";
    $result=$this->db->query($update);
    if($result){
        $data = array("status" => "success");
          return $data;
    }else{
      $data = array("status" => "failed");
        return $data;
    }
  }


  function get_doc_details($ser_pro_id,$company_status){
     $pro_id=base64_decode($ser_pro_id)/98765;
    if($company_status=="Individual"){
      $query="SELECT dd.id,dd.doc_master_id,dm.doc_name,dm.company_doc_type,dd.user_master_id,dd.doc_proof_number,dd.file_name,dd.status,dd.created_at FROM document_master as dm left join document_details as dd on dm.id=dd.doc_master_id and dd.user_master_id='$pro_id' where dm.company_doc_type='$company_status' AND dm.status='Active'";
    }else{
      $query="SELECT dd.id,dd.doc_master_id,dm.doc_name,dm.company_doc_type,dd.user_master_id,dd.doc_proof_number,dd.file_name,dd.status,dd.created_at FROM document_master as dm left join document_details as dd on dm.id=dd.doc_master_id and dd.user_master_id='$pro_id' where dm.status='Active'";

    }
    $result=$this->db->query($query);
    return $result->result();

  }

  function update_doc_status($doc_status,$doc_dd_id,$notes,$user_id){
    $get_id="SELECT * FROM document_details WHERE id='$doc_dd_id'";
    $result_get_id=$this->db->query($get_id);
    $get_user_id=$result_get_id->result();
    foreach($get_user_id as $rows){}
    $user_master_id=$rows->user_master_id;


    $update="UPDATE document_details SET status='$doc_status',updated_at=NOW(),updated_by='$user_id' WHERE id='$doc_dd_id'";
    $result=$this->db->query($update);


    $insert="INSERT INTO document_notes (user_master_id,doc_detail_id,notes,status,created_at,created_by) VALUES('$user_master_id','$doc_dd_id','$notes','$doc_status',NOW(),'$user_id')";
    $result_ins=$this->db->query($insert);
    if($result_ins){
        $data = array("status" => "success");
          return $data;
    }else{
      $data = array("status" => "failed");
        return $data;
    }
  }


  function document_history($doc_detail_id){
    $id=$doc_detail_id;
    $query="SELECT ld.name,dn.* FROM document_notes AS dn LEFT JOIN document_details AS dd ON dd.id=dn.doc_detail_id
    LEFT JOIN login_admin AS ld ON ld.id=dn.created_by WHERE dn.doc_detail_id='$id' ORDER BY dn.id DESC";
    $result=$this->db->query($query);
    return $result->result();
  }


  function get_service_skils_for_user($ser_user_id){
    $id=base64_decode($ser_user_id)/98765;
    $select="SELECT mc.main_cat_name,sc.sub_cat_name,s.service_name,sppk.id,sppk.status,sppk.user_master_id FROM serv_prov_pers_skills as sppk left join main_category as mc on sppk.main_cat_id=mc.id left join sub_category as sc on sppk.sub_cat_id=sc.id left join services as s on sppk.service_id=s.id where sppk.user_master_id='$id'";
    $result=$this->db->query($select);
    return $result->result();
  }


  // Service Person

  function service_person_list($ser_pro_id){
      $pro_id=base64_decode($ser_pro_id)/98765;
      $select="SELECT lu.id as user_master_id,lu.*,spd.* FROM login_users as lu left join service_person_details as spd on spd.user_master_id=lu.id where lu.user_type='4'  and spd.service_provider_id='$pro_id' order by spd.id desc";
      $result=$this->db->query($select);
      return $result->result();
  }

  function person_doc_details($person_id){
      $id=base64_decode($person_id)/98765;
        $query="SELECT dd.id,dd.doc_master_id,dm.doc_name,dm.company_doc_type,dd.user_master_id,dd.doc_proof_number,dd.file_name,dd.status,dd.created_at
        FROM document_master AS dm LEFT JOIN document_details AS dd ON dm.id=dd.doc_master_id AND dd.user_master_id='$id' WHERE dm.status='Active' AND dm.doc_type='IdAddressProof' AND dm.company_doc_type='Individual'";
        $result=$this->db->query($query);
        return $result->result();

  }



  function service_person_details($person_id){
    $pro_id=base64_decode($person_id)/98765;
    $select="SELECT lu.status as login_status,lu.id as user_master_id,lu.*,spd.* FROM login_users as lu left join service_person_details as spd on spd.user_master_id=lu.id where lu.user_type='4'  and spd.user_master_id='$pro_id'";
    $result=$this->db->query($select);
    return $result->result();
  }

  function update_serv_person_verify_status($status,$id){
    $pro_id=base64_decode($id)/98765;
    if($status=='Approved'){
      $update_lu="UPDATE login_users SET document_verify='Y' WHERE id='$pro_id'";
      $result_lu=$this->db->query($update_lu);
    }

    $update="UPDATE service_person_details SET serv_pers_verify_status='$status' WHERE user_master_id='$pro_id'";
    $result=$this->db->query($update);
    if($result){
        $data = array("status" => "success");
          return $data;
    }else{
      $data = array("status" => "failed");
        return $data;
    }
  }

  function update_serv_person_display_status($status,$id){
    $pro_id=base64_decode($id)/98765;
    $update_lu="UPDATE login_users SET status='$status' WHERE id='$pro_id'";
    $result_lu=$this->db->query($update_lu);
    if($result_lu){
        $data = array("status" => "success");
          return $data;
    }else{
      $data = array("status" => "failed");
        return $data;
    }
  }



}
?>
