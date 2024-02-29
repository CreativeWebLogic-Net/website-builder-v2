<?php

class clsLog{
        
   var $MessageArray=array();
   var $PriorityMessages=array();
   var $clsFact;
   var $output_level=5;

   public function general($msg,$num=0,$log_array=array()){
       $log_msg=array($num,$msg,var_export($log_array,true));
       if($num>=$this->output_level){
         if(!in_array($log_msg,$this->PriorityMessages)){
            $this->PriorityMessages[]=$log_msg;
         }
           
       }else{
         if(!in_array($log_msg,$this->MessageArray)){
           $this->MessageArray[]=$log_msg;
         }
       }

   }
   
   public function user($msg,$num=1,$memberID=0,$member_name="") 
   { 
      $log_array=array("Msg"=>$msg,"Error_Code"=>$num,"memberID"=>$memberID,"member_name"=>$member_name);
      if($num>=3){
            $this->PriorityMessages[]=var_export($log_array,true);
      }else{
            $this->MessageArray[]=var_export($log_array,true);
      }
   }

   function display_all(){
      
   }

   function display_priority(){
      // print var_export($this->PriorityMessages,true);

   }

   
   function display_normal(){
      //print var_export($this->MessageArray,true);

   }

   private function print_msg_arrays($msg) 
   { 
      //print($msg);
   }

   function output_messages()
    {
      echo"--------------------All Logs-------------------------------<br><br>\n\n";
        
      
        
        echo"<pre>";
        if(count($this->PriorityMessages)>0){
            print_r($this->PriorityMessages);
         }
         echo"</pre>";
         
    }

}

?>