<?php

class clsUpdateDatabase{
    var $SQL;
    var $SQLData;
    var $Table;
    var $ID;
    var $PostArray=array();
    var $FileArray=array();
    var $SkipArray=array();
    var $MoveArray=array();
    var $MoveToArray=array();
    var $MoveToChange=array();
    var $ValidArray=array();
    var $Errors;
    var $NoDupes=array();
    var $FirstRun=true;
    var $ExtraFields=array();
    var $FunctionArray=array();
    
    var $PrimaryKey="id";		
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
    var $DBFile="db-local.php";
    var $default_db="bubblelite2";
    var $m;
    var $r;
    var $log;
    var $links;
    
    function __construct(&$log){
        $this->log=$log;
    }
    
    function Set_Database(&$r){
        $this->r = $r;
        //print_r($this->r);
    }
    
    
    public function Set_Log(&$log){
        $this->log=$log;
        $this->log->general('Set Log Boot Success: $r->',1);
            
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
    
    function AddFunctions($FunctionArray){
        $this->FunctionArray=$FunctionArray;
    }
    
    function AddNoDupe($NoDupe){
        $this->NoDupes=$NoDupe;
    }
    function AddExtraFields($FieldArray){
        $this->ExtraFields=array_merge($this->ExtraFields,$FieldArray);
    }
    function MoveFile($VarName,$MoveTo,$ChangeTo=""){
        $this->MoveArray[]=$VarName;
        $this->MoveToArray[]=$MoveTo;
        $this->MoveToChange[]=$ChangeTo;
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
    function AddDefaultCheckBoxes($CArray){
        foreach($CArray as $key => $value){
            if(!isset($this->PostArray[$value])){
                $this->PostArray[$value]="";
            }
        };
    }
    
    function AddSkip($SArray){
        $this->SkipArray=$SArray;
    }
    function ChangeAutoInc($newkey){
        $this->PrimaryKey=$newkey;
    }
    function AddTable($Table){
        $this->Table=$Table;
        $this->SetValid();
    }
    function AddID($id){
        $this->ID=$id;
    }
    function SetValid(){
        $sql="SHOW COLUMNS FROM ".$this->Table;
        //print $sql;
        $m_arg = $this->r->rawQuery($sql);
        //$this->ValidArray = mysql_fetch_array(mysql_query($m_arg));
        if($this->r->NumRows()>0){
            while ($myrow = $this->r->Fetch_Array()) {
                //print_r($myrow);
                $this->ValidArray[]=$myrow[0];
            };
            //print $sql."444 SetValid Success =>".var_export($this->ValidArray,true);
        }else{
            $this->ValidArray=array();
            //print $sql." 445 SetValid Failure=>".var_export($this->ValidArray,true);
        }
        
    //	print "<= Valid =>".count($this->ValidArray)."<=>";
    }
    
    function IsDupes(){
        $RetVal=false;
        if(is_array($this->NoDupes)){
            foreach($this->NoDupes as $val){
                $sq2 = $this->r->rawQuery("SELECT id FROM $this->Table WHERE $val='".$this->PostArray[$val]."'");
                while ($myrow = $this->links->Fetch_Array($sq2)) {
                    if($this->ID!=$myrow[0]){
                        $RetVal=true;
                        $this->Errors.="Duplicate field on $val ";
                    };
                };
            };
        };
        return $RetVal;
    }
    
    function DeletePhotos($Photos){
        if(is_array($Photos)){
            foreach($Photos as $field => $path){
                $sql= "SELECT $field FROM $this->Table WHERE $this->PrimaryKey='$this->ID'";
                $result = $this->r->rawQuery($sql,$this->links);
                while($myrow=$this->r->Fetch_Array($result)){
                    if($myrow[0]!=""){
                        if(file_exists($path.$myrow[0])){
                            unlink($path.$myrow[0]);
                        }
                    }
                }
            }
        }
    }
    
    function DoStuff(){
        if(!$this->IsDupes()){
            if($this->FirstRun){
                $First=true;
                foreach($this->PostArray as $key => $value){
                    //echo"key=$key -value=$value<br>";
                    if((!in_array($key,$this->SkipArray))&&(in_array($key,$this->ValidArray))){
                        if($First){
                            if(is_string($value)){
                                $value=$this->r->Escape(stripslashes($value));
                            };
                            $this->SQLData="$key='$value'";
                        }else{
                            if(is_string($value)){
                                $value=$this->r->Escape(stripslashes($value));
                            };
                            $this->SQLData.=",$key='$value'";
                        };
                        $First=false;
                    };
                };
                //echo"==============FILES===========";
                if(is_array($this->FileArray)){
                    foreach($this->FileArray as $key => $value){
                        $value['name']=preg_replace('/\\s/',"_",$value['name']); //get rid of spaces
                        if($value['name']){ // check to see if file actually sent
                            //echo"key=$key----------------<br>";
                            $MoveToKey=array_search($key,$this->MoveArray);
                            $ImageKeys=array_keys($this->ImageArray,$key);
                            $KImageKey=array_search($key,$this->KImageArray);
                            //echo"--$MoveToKey--";
                            if(is_numeric($MoveToKey)){
                                //echo"<br>Send File To ".$this->MoveToArray[$MoveToKey]." <br>";
                                if($this->MoveToChange[$MoveToKey]!="") $value['name']=$this->MoveToChange[$MoveToKey];
                                if($First){
                                    $this->SQLData="$key='".$value['name']."'";
                                }else{
                                    $this->SQLData.=",$key='".$value['name']."'";
                                };	
                                
                                copy($value['tmp_name'],$this->MoveToArray[$MoveToKey].$value['name']);
                                if($value['tmp_name']!="")	unlink($value['tmp_name']);
                                $First=false;
                            }
                            if((is_array($ImageKeys))&&(count($ImageKeys)>0)){
                                //echo"<br>Send File To ".$this->ImageToArray[$ImageKey]." and Resize To ".$this->ImageSizeArray[$ImageKey]."<br>";
                                foreach($ImageKeys as $IKey =>$IVal){
                                    if($value['name']!=""){
                                        $value['name']=$this->str_makerand(5).$value['name'];
                                        $value['name'] = preg_replace("[^A-Za-z0-9]", "", $value['name'] );
                                        if($value['tmp_name']!="") $ImgData=getimagesize($value['tmp_name']);
                                        if($ImgData['channels']==4){
                                            exec("convert -colorspace RGB -resize ".$this->ImageSizeArray[$IVal]." ".$value['tmp_name']." ".$this->ImageToArray[$IVal].$value['name']);
                                        }else{
                                            exec("convert -resize ".$this->ImageSizeArray[$IVal]." ".$value['tmp_name']." ".$this->ImageToArray[$IVal].$value['name']);
                                        }
                                        
                                        if($this->ImageChangeTo[$IVal]!="") $key=$this->ImageChangeTo[$IVal];
                                        if($First){
                                            $this->SQLData="$key='".$value['name']."'";
                                        }else{
                                            $this->SQLData.=",$key='".$value['name']."'";
                                        };
                                        $First=false;
                                    }
                                }
                                if($value['tmp_name']!="")	unlink($value['tmp_name']);
                                
                                
                            }elseif(is_numeric($KImageKey)){
                                if($value['name']!=""){
                                    $value['name']=$this->str_makerand(5).$value['name'];
                                    $value['name'] = preg_replace("[^A-Za-z0-9]", "", $value['name'] );
                                    $SmallFileName="Small-".$value['name'];
                                    $BigFileName="Big-".$value['name'];
                                    copy($value['tmp_name'],$this->KBigToArray[$KImageKey].$BigFileName);
                                    if($value['tmp_name']!="") $ImgData=getimagesize($value['tmp_name']);
                                    if($ImgData['channels']==4){ //CMYK Image
                                        exec("convert -colorspace RGB -resize ".$this->KImageSizeArray[$KImageKey]." ".$value['tmp_name']." ".$this->KSmallToArray[$KImageKey].$SmallFileName);
                                    }else{
                                        exec("convert -resize ".$this->KImageSizeArray[$KImageKey]." ".$value['tmp_name']." ".$this->KSmallToArray[$KImageKey].$SmallFileName);
                                    }
                                    if($value['tmp_name']!="")	unlink($value['tmp_name']);
                                    if($First){
                                        $this->SQLData=$this->KSmallDBArray[$KImageKey]."='".$SmallFileName."'";
                                    }else{
                                        $this->SQLData.=",".$this->KSmallDBArray[$KImageKey]."='".$SmallFileName."'";
                                    }
                                    $this->SQLData.=",".$this->KBigDBArray[$KImageKey]."='".$BigFileName."'";
                                    $First=false;
                                }
                            }
                        };
                        //foreach($value as $key2 => $value2){
                            //echo"key=$key2 -value=$value2<br>";
                        //};
                        
                        
                    };
                };
                // functions
                foreach($this->FunctionArray as $key => $value){
                    if($First){
                        if(is_string($value)){
                            $value=$this->r->Escape(stripslashes($value));
                        };
                        $this->SQLData="$key=$value";
                    }else{
                        if(is_string($value)){
                            $value=$this->r->Escape(stripslashes($value));
                        };
                        $this->SQLData.=",$key=$value";
                    };
                    $First=false;
                }
                // extra fields
                foreach($this->ExtraFields as $key => $value){
                    if($First){
                        if(is_string($value)){
                            $value=$this->r->Escape(stripslashes($value));
                        };
                        $this->SQLData="$key='$value'";
                    }else{
                        if(is_string($value)){
                            $value=$this->r->Escape(stripslashes($value));
                        };
                        $this->SQLData.=",$key='$value'";
                    };
                    $First=false;
                };
                $this->FirstRun=false;
            };
            //create and execute the query
            $this->SQL="UPDATE $this->Table SET $this->SQLData WHERE $this->PrimaryKey=$this->ID";
            $result = $this->r->rawQuery($this->SQL);
            if(!$result){
                //echo"error:="; 
            //	print $this->SQL;
            }else{
                //print "\n\n success=>".$this->SQL;
            }
        
        }
        return $this->Errors;
    }
    
}