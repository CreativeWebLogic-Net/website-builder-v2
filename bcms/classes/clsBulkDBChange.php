<?php
class clsBulkDBChange{
    var $Table;
    var $RecordArray=array();
    var $MultiArray=array();
    var $WhatToChange;
    var $WhatToChangeTo;
    var $Target="id";
    var $Errors;
    var $DBFile="db-local.php";
    var $default_db="bubblelite2";
    var $m;
    var $r;
    var $links;
    
    function __construct(&$log=false){
        if($log){
            $this->log=$log;
        }
    }
    
    function Set_Database(&$r){
        $this->r=$r;
    }
    
    
    function AddTable($Table){
        $this->Table=$Table;
    }
    function AddIDMultiArray($DFiles){
        $this->MultiArray=$DFiles;
    }
    function AddIDArray($DFiles){
        $this->RecordArray=$DFiles;
    }
    function WhatToChange($var,$to=""){
        $this->WhatToChange=$var;
        $this->WhatToChangeTo=$to;
    }
    function ChangeTarget($var){
        $this->Target=$var;
        
    }
    
    function DoChange(){
        if(count($this->RecordArray)>0){
            foreach($this->RecordArray as $key => $value){
                
                $query= "UPDATE $this->Table SET $this->WhatToChange='$this->WhatToChangeTo' WHERE $this->Target='$value'";
                
                $result = $this->r->rawQuery($query);
                
            }
        }elseif(count($this->MultiArray)>0){
            //print_r($this->MultiArray);
            foreach($this->MultiArray as $key => $value){
                
                $query= "UPDATE $this->Table SET $this->WhatToChange='$this->WhatToChangeTo' WHERE $this->Target='$value'";
                
                $result = $this->r->rawQuery($query);
            }
        }else{
            $this->Errors.="No Items Selected";
        }
        return $this->Errors;
    }
}

//-----------------------------------------------------------------------------------------------------------

class DeleteFromDatabase{
    var $Table;
    var $RecordArray=array();
    var $WhatToDelete="id";
    var $Errors;
    var $DBFile="db-local.php";
    var $default_db="bubblelite2";
    var $m;
    var $r;
    var $links;
    
    
    function __construct(&$log=false){
        if($log){
            $this->log=$log;
        }
    }

    function Set_Database(&$r){
        //print "654->->".var_export($this->r,true);
        $this->r=$r;
        
                    
    }
    
    function AddTable($Table){
        $this->Table=$Table;
    }
    
    function AddIDArray($DFiles){
        $this->RecordArray=$DFiles;
    }
    function AltDeleteVar($var){
        $this->WhatToDelete=$var;
    }
    
    function DeletePhotos($Photos){
        if(is_array($Photos)){
            foreach($Photos as $field => $path){
                foreach($this->RecordArray as $key => $value){
                    $query= "SELECT $field FROM $this->Table WHERE $this->WhatToDelete='$value'";
                    $result = $this->r->rawQuery($query);
                    
                    while($myrow=$result->fetch_row()){
                        if($myrow[0]!=""){
                            if(file_exists($path.$myrow[0])){
                                unlink($path.$myrow[0]);
                            }
                        }
                    }
                }
            }
        }
    }
    
    function DoDelete(){
        try{
            $this->Errors="";
            if(is_array($this->RecordArray)){
                foreach($this->RecordArray as $key => $value){
                    
                    $query= "DELETE FROM $this->Table where $this->WhatToDelete='$value'";
                    //print "432->".$query."->".var_export($this->r,true);
                    $result = $this->r->rawQuery($query);
                    if(!$result){
                        //print $query;
                    }else{
                        //print $query."->".var_export($result,true);
                    }
                }
            }else{
                $this->Errors.="No Items Selected";
            }
            return $this->Errors;
        }catch(Exception $e){
            throw new Exception("143 Do Delete Failed=>".var_export($e,true));
        }
        
        
    }
}