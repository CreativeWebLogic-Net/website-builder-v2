<?php

class clsAddToDatabase{
    var $SQL;
    var $SQLFields;
    var $SQLData;
    var $Table;
    var $PostArray=array();
    var $FileArray=array();
    var $SkipArray=array();
    var $ValidArray=array();
    var $MoveArray=array();
    var $MoveToArray=array();
    var $Errors;
    var $NoDupes=array();
    var $DBFile="db-local.php";
    var $default_db="bubblelite2";
    var $FirstRun=true;
    var $InsertType="Insert";
    
    var $ExtraFields=array();
    
    var $ImageArray=array();
    var $ImageToArray=array();
    var $ImageSizeArray=array();
    var $ImageChangeTo=array();
    
    var $KImageArray=array();
    var $KSmallToArray=array();
    var $KBigToArray=array();
    var $KSmallDBArray=array();
    var $KBigDBArray=array();
    var $KImageSizeArray=array();
    var $FunctionArray=array();
    var $AutoIncrement="id";
    var $AutoIncVal=0;
    var $m;
    var $r;
    var $links;
    var $log;
    var $vs;
    
    
    function __construct(&$log=false){
        if($log){
            $this->log=$log;
        }
    }
    
    function Set_Database(&$r){
        $this->r = $r;
        //echo"233-----------------------------------------------------------".var_export($this->r,true)."------------------";
    
    }
    
    public function Set_Log(&$log){
        $this->log=$log;
        $this->log->general('Set Log Boot Success: $r->',1);
            
    }
    
    public function Set_Vs(&$vs){
        $this->vs=$vs;
        $this->log->general('Set Log vs->db Success: $r->',1);
            
    }
            
    function Reset(){
        $this->FirstRun=true;
        $this->SQLFields="";
        $this->SQLData="";
        $this->ExtraFields=array();
        $this->FunctionArray=array();
        $this->ValidArray=array();
        $this->AutoIncVal=0;
    }
    
    function str_makerand ($length) 
    { 
        $minlength=$length;
        $maxlength=$length;
        $charset = "abcdefghijklmnopqrstuvwxyz"; 
        $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ"; 
        $charset .= "0123456789"; 
        if ($minlength > $maxlength) $length = mt_rand ($maxlength, $minlength); 
        else                         $length = mt_rand ($minlength, $maxlength); 
        for ($i=0; $i<$length; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))]; 
        return $key; 
    } 
    
    function ChangeInsertType($to){
        $this->InsertType=$to;
    }
    
    function ChangeDBFile($db){
        
    }
    
    function AddNoDupe($NoDupe){
        $this->NoDupes=$NoDupe;
    }
    
    function AddID($id){
        $this->AutoIncVal=$id;
    }
    function AddFunctions($FunctionArray){
        $this->FunctionArray=$FunctionArray;
    }
    function AddExtraFields($FieldArray){
        $this->ExtraFields=array_merge($this->ExtraFields,$FieldArray);
    }
    function MoveFile($VarName,$MoveTo){
        $this->MoveArray[]=$VarName;
        $this->MoveToArray[]=$MoveTo;
    }
    function ResizeImage($VarName,$MoveTo,$Size,$ChangeTo=""){
        $this->ImageArray[]=$VarName;
        $this->ImageToArray[]=$MoveTo;
        $this->ImageSizeArray[]=$Size;
        $this->ImageChangeTo[]=$ChangeTo;
    }
    function KeepAndResizeImage($VarName,$DBSmall,$DBBig,$MoveToSmall,$MoveToBig,$Size){
        $this->KImageArray[]=$VarName;
        $this->KSmallDBArray[]=$DBSmall;
        $this->KBigDBArray[]=$DBBig;
        $this->KSmallToArray[]=$MoveToSmall;
        $this->KBigToArray[]=$MoveToBig;
        $this->KImageSizeArray[]=$Size;
    }
    
    function AddPosts($PArray,$FArray){
        $this->PostArray=$PArray;
        $this->FileArray=$FArray;
    }
    function AddSkip($SArray){
        $this->SkipArray=$SArray;
    }
    function AddTable($Table){
        
        $this->Table=$Table;
        $this->SetValid();
    }
    function ChangeAutoInc($to){
        $this->AutoIncrement=$to;
    }
    
    function ReturnID(){
        return $this->AutoIncVal;
    }
    function GetNextID(){
        if($this->AutoIncVal==0){
            
            $query= "SHOW TABLE STATUS LIKE '$this->Table'";
            $sq2 = $this->r->rawQuery($query);
            $result = $this->r->Fetch_Assoc();
            $this->AutoIncVal=$result['Auto_increment'];
        };
    }
    function IsDupes(){
        $RetVal=false;
        if(is_array($this->NoDupes)){
            foreach($this->NoDupes as $val){
                if($this->PostArray[$val]){
                    $SQL="SELECT id FROM $this->Table WHERE $val='".$this->PostArray[$val]."'";
                    //print $SQL;
                    $sq2 = $this->r->rawQuery($SQL);
                    while ($myrow = $this->r->Fetch_Array($sq2)) {
                        $RetVal=true;
                        $this->Errors.="Duplicate field on $val ";
                    };
                }
            };
        };
        return $RetVal;
    }
    
    
    function SetValid(){
        try{
            
            $sql="SHOW COLUMNS FROM ".$this->Table;
        //print $sql;
        $m_arg = $this->r->rawQuery($sql);
        //$this->ValidArray = mysql_fetch_array(mysql_query($m_arg));
        while ($myrow = $this->r->Fetch_Array($m_arg)) {
            //print_r($myrow);
            $this->ValidArray[]=$myrow[0];
        };
            

        }catch(Exception $e){
            throw new Exception('677 clsDb Failure.=>'.var_export($e,true));
        }
        
    }
    
    
    
    function DoStuff(){
        //echo"123-----------------------------------------------------------------------------\n";
        
        if(!$this->IsDupes()){
            if($this->FirstRun){
                $First=true;
                $this->GetNextID();
                foreach($this->PostArray as $key => $value){
                    //echo"key=$key -value=$value<br>";
                    if((!in_array($key,$this->SkipArray))&&(in_array($key,$this->ValidArray))){
                        if($First){
                            $this->SQLFields.="$key";
                            if(is_string($value)){
                                $value=$this->r->Escape(stripslashes($value));
                            };
                            $this->SQLData="'$value'";
                        }else{
                            $this->SQLFields.=",$key";
                            if(is_string($value)){
                                $value=$this->r->Escape(stripslashes($value));
                            };
                            $this->SQLData.=",'$value'";
                        };
                        //print $this->SQLData;
                        $First=false;
                    };
                };
                //echo"1234-----------------------------------------------------------------------------\n";
                //print $this->SQLData;
                //echo"==============FILES===========";
                if(isset($this->FileArray)){
                    if(is_array($this->FileArray)){
                        foreach($this->FileArray as $key => $value){
                            //echo"key=$key----------------<br>";
                            
                            $value['name']=eregi_replace(" ","_",$value['name']); //get rid of spaces
                            
                            $MoveToKey=array_search($key,$this->MoveArray);
                            //$ImageKey=array_search($key,$this->ImageArray);
                            $ImageKeys=array_keys($this->ImageArray,$key);
                            $KImageKey=array_search($key,$this->KImageArray);
                            //echo"--$MoveToKey--";
                            if(is_numeric($MoveToKey)){
                                //echo"<br>Send File To ".$this->MoveToArray[$MoveToKey]." <br>";
                                if($First){
                                    $this->SQLFields.="$key";
                                    $this->SQLData="'".$value['name']."'";
                                }else{
                                    $this->SQLFields.=",$key";
                                    $this->SQLData.=",'".$value['name']."'";
                                };
                                copy($value['tmp_name'],$this->MoveToArray[$MoveToKey].$value['name']);
                                if (file_exists($value['tmp_name'])) unlink($value['tmp_name']);
                                $First=false;
                            }elseif(is_array($ImageKeys)){
                                //echo"<br>Send File To ".$this->ImageToArray[$ImageKey]." and Resize To ".$this->ImageSizeArray[$ImageKey]."<br>";
                                foreach($ImageKeys as $IKey =>$IVal){
                                    //$value['name']=$this->str_makerand(5).$value['name'];
                                    if($value['name']!="") $value['name']=$this->str_makerand(5).$value['name'];
                                    $value['name'] = ereg_replace("[^A-Za-z0-9]", "", $value['name'] );
                                    //print $value['name'];
                                    if($value['name']!="") $value['name']=$this->str_makerand(5).$value['name'];
                                    if($value['tmp_name']!="") $ImgData=getimagesize($value['tmp_name']);
                                    if($ImgData['channels']==4){
                                        exec("convert -colorspace RGB -resize ".$this->ImageSizeArray[$IVal]." ".$value['tmp_name']." ".$this->ImageToArray[$IVal].$value['name']);
                                    }else{
                                        exec("convert -resize ".$this->ImageSizeArray[$IVal]." ".$value['tmp_name']." ".$this->ImageToArray[$IVal].$value['name']);
                                    }
                                    
                                    if($this->ImageChangeTo[$IVal]!="") $key=$this->ImageChangeTo[$IVal];
                                    if($First){
                                        $this->SQLFields.="$key";
                                        $this->SQLData="'".$value['name']."'";
                                    }else{
                                        $this->SQLFields.=",$key";
                                        $this->SQLData.=",'".$value['name']."'";
                                    };
                                    $First=false;
                                }
                                if (file_exists($value['tmp_name'])) unlink($value['tmp_name']);
                                
                            }elseif(is_numeric($KImageKey)){
                                //echo"<br>Send Small File To ".$this->KSmallToArray[$KImageKey]." and Insert FileName into".$this->KSmallDBArray[$KImageKey]." and Resize To ".$this->KImageSizeArray[$KImageKey]."<br>";
                                //echo"<br>Send Big File To ".$this->KBigToArray[$KImageKey]." and Insert FileName into".$this->KBigDBArray[$KImageKey]." <br>";
                                if($value['name']!="") $value['name']=$this->str_makerand(5).$value['name'];
                                $value['name'] = ereg_replace("[^A-Za-z0-9]", "", $value['name'] );
                                    
                                $SmallFileName="Small-".$value['name'];
                                $BigFileName="Big-".$value['name'];
                                copy($value['tmp_name'],$this->KBigToArray[$KImageKey].$BigFileName);
                                if($value['tmp_name']!="") $ImgData=getimagesize($value['tmp_name']);
                                if($ImgData['channels']==4){ //CMYK Image
                                    exec("convert -colorspace RGB -resize ".$this->KImageSizeArray[$KImageKey]." ".$value['tmp_name']." ".$this->KSmallToArray[$KImageKey].$SmallFileName);
                                }else{
                                    exec("convert -resize ".$this->KImageSizeArray[$KImageKey]." ".$value['tmp_name']." ".$this->KSmallToArray[$KImageKey].$SmallFileName);
                                }
                                if (file_exists($value['tmp_name'])) unlink($value['tmp_name']);
                                if($First){
                                    $this->SQLFields.=$this->KSmallDBArray[$KImageKey];
                                    $this->SQLData="'".$SmallFileName."'";
                                }else{
                                    $this->SQLFields.=",".$this->KSmallDBArray[$KImageKey];
                                    $this->SQLData.=",'".$SmallFileName."'";
                                }
                                $this->SQLFields.=",".$this->KBigDBArray[$KImageKey];
                                $this->SQLData.=",'".$BigFileName."'";
                                $First=false;
                            }
                            //foreach($value as $key2 => $value2){
                                //echo"key=$key2 -value=$value2<br>";
                            //};
                        };
                    };
                }
                //echo"12345-----------------------------------------------------------------------------\n";
                if(isset($this->ExtraFields)){
                    foreach($this->ExtraFields as $key => $value){
                        if($First){
                            $this->SQLFields.="$key";
                            if(is_string($value)){
                                $value=$this->r->Escape(stripslashes($value));
                            };
                            $this->SQLData="'$value'";
                        }else{
                            $this->SQLFields.=",$key";
                            if(is_string($value)){
                                $value=$this->r->Escape(stripslashes($value));
                            };
                            $this->SQLData.=",'$value'";
                        };
                        //print $this->SQLData;
                        $First=false;
                    }
                }
                if(isset($this->FunctionArray)){
                    foreach($this->FunctionArray as $key => $value){
                        if($First){
                            $this->SQLFields.="$key";
                            if(is_string($value)){
                                $value=$this->r->Escape(stripslashes($value));
                            };
                            $this->SQLData="$value";
                        }else{
                            $this->SQLFields.=",$key";
                            if(is_string($value)){
                                $value=$this->r->Escape(stripslashes($value));
                            };
                            $this->SQLData.=",$value";
                        };
                        $First=false;
                        //print $this->SQLData;
                    }
                }
                $this->FirstRun=false;
                //echo"123456-----------------------------------------------------------------------------\n";
            }
            //echo"1234567-----------------------------------------------------------------------------\n";
            if($this->AutoIncVal>0){
                $this->SQL="$this->InsertType INTO $this->Table ($this->SQLFields,$this->AutoIncrement) VALUES ($this->SQLData,$this->AutoIncVal)";
                //echo"1234567711------------------------------------".$this->SQL."-----------------------------------------\n";
            }else{
                $this->SQL="$this->InsertType INTO $this->Table ($this->SQLFields) VALUES ($this->SQLData)";
            }
            $result = $this->r->rawQuery($this->SQL);
            if(!$result){
                //echo"error-$this->SQL"; 
            }else{
                //echo"123456778------------------------------------".$this->SQL."-----------------------------------------\n";
            }
        }
        print $this->SQL."<br>";
        //echo"1234568-----------------------------------------------------------------------------\n";
        return $this->Errors;
        
    }
}