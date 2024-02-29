<?php

    class clsAssortedFunctions{
        private $tag_match_array;
        private $current_domain;
        private $callback_num;
        private $html_piece;
        private static $total_html;
        private $combined_total_html;
        private $current_dir="";
        function __construct(){
            $this->add_current_domain();
            
        }

        function set_log($var_array="",$message=""){
        //exit();
            //$message="fff";
            $output="";
            if(is_array($var_array)){
                $output=var_export($var_array,true);
            }else{
                $output=$var_array;
            }
            if($message!=""){
                $output.="-".$message;
            }
            $output="\n <br>".$output."<br> \n";
            //return $output;
            print($output);
            //exit();
        }

        function Get_HTML_Tags(){
            $tag_array=array("a","abbr","acronym","address","applet","article","aside","audio","b","basefont","bdi","bdo","big","blockquote","body","button",
            "canvas","caption","center","cite","code","colgroup","data","datalist","dd","del","details","dfn","dialog","dir","div","dl","dt","em","fieldset",
            "figcaption","figre","font","footer","form","frame","frameset","h1","head","header","html","i","iframe","ins","kbd","label","legend","li","main","map","mark","meter",
            "nav","noframes","noscript","object","ol","optgroup","option","output","p","param","picture","pre","progress","q","rp","rt","ruby","s","samp",
            "script","section","select","small","span","strike","strong","style","sub","summary","sup","svg","table","tbody","td","template","textarea",
            "tfoot","th","thead","time","title","tr","tt","u","ul","var","video");
            $tag_void_array=array("area","base","br","col","embed","hr","img","input","link","meta","source","track","wbr","!DOCTYPE");
            return array($tag_array,$tag_void_array);
        }
        
        //$all_html_tags=Get_HTML_Tags();
        //print_r($all_html_tags);
        //----------------------------------------------------------------
        function callback($buffer)
        {
            //$pos = strpos($buffer, 2);
            //$buffer=substr($buffer, $pos);
            $buffer="";
            return $buffer;
            
            
        }

        function Find_Current_Directory()
        {
            if($this->current_dir==""){
                $this->current_dir=pathinfo(__DIR__);
            }
            
            return $this->current_dir;
            
            
        }

        function tag_replace()
        {
            $tag_match_array=array("url"=>"localhost");
            return $tag_match_array;
            
        }
        
        function callback_template($buffer)
        {
            //$buffer = substr($buffer, 2, 1);  // returns "cde"
            
            //$pos = strpos($buffer, '{');
            //$buffer=substr($buffer, $pos);
            return $buffer;
            
            
        }
        
        function callback_output($buffer)
        {
            //$buffer = substr($buffer, 2, 1);  // returns "cde"
            
            //$pos = strpos($buffer, '{');
            //$buffer=substr($buffer, $pos);
            //global $text_data;
            //$text_data['debug'][]=$buffer;
            return $buffer;
            
            
        }
        
        function callback_main_output($buffer)
        {
            //$buffer = substr($buffer, 2, 1);  // returns "cde"
            
            //$pos = strpos($buffer, '{');
            //$buffer=substr($buffer, $pos);
            global $text_data;
            $text_data['output_html']=base64_encode($buffer);
            return $buffer;
            
            
        }

        function modify_tags($buffer,$tag_match_array)
        {
            // replace all the apples with oranges
            //return (str_replace("Yamba", "oranges", $buffer));
            //$buffer="\n".$buffer;
            //$buffer = trim($buffer," \t\n\r"););
            //echo"\n<br>666-----------------------------------------------------------------------------\n <br>";


            
            //global $tag_match_array;
            //print_r($tag_match_array);
            $all_html_tags=$this->Get_HTML_Tags();
            $html_tags=$all_html_tags[0];
            $html_void_tags=$all_html_tags[1];
            //print_r($all_html_tags);
            //$buffer=var_export($all_html_tags,true).$buffer;
            $sub_string_total="xx";
            $match_array=array();
            $inner_array=array();
            $search=0;
            $buffer_size=strlen($buffer);
            $query="";
            $str_match="";
            $cur_match=array(); 
            $inner_match=array();
            $start_count=array();
            $end_count=array();
            //$open_char=array("{","<");
            //$close_char=array("}",">");
            $open_char=array("{");
                $close_char=array("}");
            $custom_char=array($open_char[0],$close_char[0]);
            //$html_char=array($open_char[1],$close_char[1]);
            //$tag_array=array("custom"=>$custom_char,"html"=>$html_char);
            $tag_array=array("custom"=>$custom_char);
            //$tag_array_count=array("custom"=>2,"html"=>1);
            $tag_array_count=array("custom"=>2);
            $current_tag=0;
            
            while($search<=$buffer_size){
                $sub_string = substr($buffer, $search, 1);
                foreach($tag_array as $tag_type=>$tag_val){
                    //$start_count=0;
                    //$cur_match="";
                    //$buffer="\n tv-".$start_count."-".$end_count."-".$cur_match."-".var_export($tag_val,true).$buffer."\n";
                    if($sub_string==$tag_val[0]){
                        if(!isset($start_count[$tag_type])) $start_count[$tag_type]=0;
                        if(!isset($cur_match[$tag_type])) $cur_match[$tag_type]="";
                        $start_count[$tag_type]++;
                        $cur_match[$tag_type].=$sub_string;
                        //$buffer=$buffer."\n -22".$sub_string."-".$tag_val[0]."-33- \n";
                    }elseif($sub_string==$tag_val[1]){
                        if(!isset($end_count[$tag_type])) $end_count[$tag_type]=0;
                        if(!isset($cur_match[$tag_type])) $cur_match[$tag_type]="";
                        $end_count[$tag_type]++;
                        $cur_match[$tag_type].=$sub_string;
                        //$buffer=$buffer."\n -223".$sub_string."-".$tag_val[1]."-334- \n";
                    }else{
                        if(!isset($start_count[$tag_type])){
                            $start_count[$tag_type]=0;
                        }
                        if(!isset($end_count[$tag_type])){
                            $end_count[$tag_type]=0;
                        }
                        if(!isset($inner_match[$tag_type])){
                            $inner_match[$tag_type]="";
                        }
                        if($start_count[$tag_type]>0){
                            $cur_match[$tag_type].=$sub_string;
                            $inner_match[$tag_type].=$sub_string;
                            //$buffer=$buffer."\n -224".$sub_string."-".$cur_match[$tag_type]."-35- \n";
                        }
                    }
                    //if(($start_count[$tag_type]>$tag_array_count[$tag_type])&&($end_count[$tag_type]>$tag_array_count[$tag_type])){
                    $match_count=$tag_array_count[$tag_type]-1;
                    //if(($start_count[$tag_type]>0)&&($end_count[$tag_type]>0)){
                    if((isset($start_count[$tag_type]))&&(isset($end_count[$tag_type]))){
                        if(($start_count[$tag_type]>$match_count)&&($end_count[$tag_type]>$match_count)){
                            $current_tag++;
                            $match_array[$current_tag]['html']=$cur_match[$tag_type];
                            $inner_array[$current_tag]['html']=$inner_match[$tag_type];
                            $cur_match[$tag_type]="";
                            $inner_match[$tag_type]="";
                            $start_count[$tag_type]=0;
                            $end_count[$tag_type]=0;
                            $tag_string=$match_array[$current_tag]['html'];
                            $tag_string=substr($tag_string,1,-1);
                            if(substr($tag_string,-1)=='/'){
                                $tag_string=substr($tag_string,0,-1);
                            }
                            $tag_attributes=explode(' ',$tag_string);
                            $tag_attr_list = array_slice($tag_attributes, 1);
                            foreach($tag_attr_list as $attr_key=>$attri_val){
                                $pos = strpos($attri_val, '=');
                                /*
                                if (str_contains($attri_val, '=')) {
                                    $match_array[$current_tag]['attributes'][]=$attri_val;
                                }
                                */
                                if ($pos>-1) {
                                    $match_array[$current_tag]['attributes'][]=$attri_val;
                                }
                            }
                            //$match_array[$current_tag]['attributes']=$tag_attr_list;
                            $tag_name=$tag_attributes[0];
                            $match_array[$current_tag]['tag_name']=$tag_name;
                            if(in_array($tag_name,$html_tags)){
                                $match_array[$current_tag]['tag_type']="html";
                            }elseif(in_array($tag_name,$html_void_tags)){
                                $match_array[$current_tag]['tag_type']="void";
                            }
                        }
                    }
                    
                }
                //$buffer=$buffer."\n -1".var_export($match_array,true)."-2- \n";
                $search++;
                
            }

            /*
            for($x=0;$x<count($match_array);$x++){
                if(isset($inner_array[$x]['html'])){

                    if(isset($tag_match_array[$inner_array[$x]['html']])){
                            $buffer=str_replace($match_array[$x], $tag_match_array[$inner_array[$x]['html']], $buffer);
                        
                        //$query.="| ".$x." |\n ".$inner_array[$x]."\n--".$match_array[$x]."=>".$tag_match_array[$inner_array[$x]['html']];//var_export($tag_match_array[$inner_array[$x]],true);
                        
                    }else{
                        //$buffer=str_replace($match_array[$x], "", $buffer);
                    }
                }
                
            }
            */
            //print_r($inner_array);
            return array($match_array,$tag_match_array,$inner_array);//$buffer;
        }

        function swap_tags($template_code,$match_array,$tag_match_array,$inner_array)
        {
            $buffer=$template_code;
            //print_r($match_array);
            //print_r($tag_match_array);
            //print_r($inner_array);
            for($x=0;$x<=count($match_array);$x++){
                if(isset($inner_array[$x]['html'])){
                    //print($inner_array[$x]['html']);
                    if(isset($tag_match_array[$inner_array[$x]['html']])){
                            //$buffer=str_replace($match_array[$x], $tag_match_array[$inner_array[$x]['html']], $buffer);
                            $buffer=str_replace($match_array[$x], $tag_match_array[$inner_array[$x]['html']], $buffer);
                        
                        //$query.="| ".$x." |\n ".$inner_array[$x]."\n--".$match_array[$x]."=>".$tag_match_array[$inner_array[$x]['html']];//var_export($tag_match_array[$inner_array[$x]],true);
                        
                    }else{
                        //$buffer=str_replace($match_array[$x], "", $buffer);
                    }
                }
                
            }
            //print_r($buffer);
            return $buffer;
        }


        function callback_big($buffer)
        {
            // replace all the apples with oranges
            //return (str_replace("Yamba", "oranges", $buffer));
            //$buffer="\n".$buffer;
            //$buffer = trim($buffer," \t\n\r"););
            //echo"\n<br>666-----------------------------------------------------------------------------\n <br>";


            
            global $tag_match_array;
            //print_r($tag_match_array);
            $all_html_tags=Get_HTML_Tags();
            $html_tags=$all_html_tags[0];
            $html_void_tags=$all_html_tags[1];
            //print_r($all_html_tags);
            //$buffer=var_export($all_html_tags,true).$buffer;
            $sub_string_total="xx";
            $match_array=array();
            $inner_array=array();
            $search=0;
            $buffer_size=strlen($buffer);
            $query="";
            $str_match="";
            $cur_match=array(); 
            $inner_match=array();
            $start_count=array();
            $end_count=array();
            $open_char=array("{","<");
            $close_char=array("}",">");
            $custom_char=array($open_char[0],$close_char[0]);
            $html_char=array($open_char[1],$close_char[1]);
            $tag_array=array("custom"=>$custom_char,"html"=>$html_char);
            $tag_array_count=array("custom"=>2,"html"=>1);
            $current_tag=0;
            while($search<=$buffer_size){
                $sub_string = substr($buffer, $search, 1);
                foreach($tag_array as $tag_type=>$tag_val){
                    //$start_count=0;
                    //$cur_match="";
                    //$buffer="\n tv-".$start_count."-".$end_count."-".$cur_match."-".var_export($tag_val,true).$buffer."\n";
                    if($sub_string==$tag_val[0]){
                        if(!isset($start_count[$tag_type])) $start_count[$tag_type]=0;
                        if(!isset($cur_match[$tag_type])) $cur_match[$tag_type]="";
                        $start_count[$tag_type]++;
                        $cur_match[$tag_type].=$sub_string;
                        //$buffer=$buffer."\n -22".$sub_string."-".$tag_val[0]."-33- \n";
                    }elseif($sub_string==$tag_val[1]){
                        if(!isset($end_count[$tag_type])) $end_count[$tag_type]=0;
                        if(!isset($cur_match[$tag_type])) $cur_match[$tag_type]="";
                        $end_count[$tag_type]++;
                        $cur_match[$tag_type].=$sub_string;
                        //$buffer=$buffer."\n -223".$sub_string."-".$tag_val[1]."-334- \n";
                    }else{
                        if(!isset($start_count[$tag_type])){
                            $start_count[$tag_type]=0;
                        }
                        if(!isset($end_count[$tag_type])){
                            $end_count[$tag_type]=0;
                        }
                        if(!isset($inner_match[$tag_type])){
                            $inner_match[$tag_type]="";
                        }
                        if($start_count[$tag_type]>0){
                            $cur_match[$tag_type].=$sub_string;
                            $inner_match[$tag_type].=$sub_string;
                            //$buffer=$buffer."\n -224".$sub_string."-".$cur_match[$tag_type]."-35- \n";
                        }
                    }
                    //if(($start_count[$tag_type]>$tag_array_count[$tag_type])&&($end_count[$tag_type]>$tag_array_count[$tag_type])){
                    $match_count=$tag_array_count[$tag_type]-1;
                    //if(($start_count[$tag_type]>0)&&($end_count[$tag_type]>0)){
                    if(($start_count[$tag_type]>$match_count)&&($end_count[$tag_type]>$match_count)){
                        $current_tag++;
                        $match_array[$current_tag]['html']=$cur_match[$tag_type];
                        $inner_array[$current_tag]['html']=$inner_match[$tag_type];
                        $cur_match[$tag_type]="";
                        $inner_match[$tag_type]="";
                        $start_count[$tag_type]=0;
                        $end_count[$tag_type]=0;
                        $tag_string=$match_array[$current_tag]['html'];
                        $tag_string=substr($tag_string,1,-1);
                        if(substr($tag_string,-1)=='/'){
                            $tag_string=substr($tag_string,0,-1);
                        }
                        $tag_attributes=explode(' ',$tag_string);
                        $tag_attr_list = array_slice($tag_attributes, 1);
                        foreach($tag_attr_list as $attr_key=>$attri_val){
                            if (str_contains($attri_val, '=')) {
                                $match_array[$current_tag]['attributes'][]=$attri_val;
                            }
                        }
                        //$match_array[$current_tag]['attributes']=$tag_attr_list;
                        $tag_name=$tag_attributes[0];
                        $match_array[$current_tag]['tag_name']=$tag_name;
                        if(in_array($tag_name,$html_tags)){
                            $match_array[$current_tag]['tag_type']="html";
                        }elseif(in_array($tag_name,$html_void_tags)){
                            $match_array[$current_tag]['tag_type']="void";
                        }
                    }
                }
                //$buffer=$buffer."\n -1".var_export($match_array,true)."-2- \n";
                $search++;
                
            }
            $set_dimentions=true;
            $tag_count=0;
            $parent=0;
            /*
            while($set_dimentions==true){
                if($start_tag_count==0){
                    if($match_array[$tag_count]['tag_type']=="void"){
                        $match_array[$tag_count]['parent']=$parent;
                        $start_tag_count=0;
                    }else{
                        $tag_search_name=$match_array[$tag_count]['tag_name'];
                        $start_tag_count=$tag_count;
                    };
                }else{

                }
                
                if (str_contains($tag_search_name, '/')) {
                    
                    $end_tag_search_name=substr($tag_string,1);
                    if($end_tag_search_name==$tag_search_name){
                        
                    }
                        $end_tag_search_name=$tag_search_name;
                    }
                }
                if($tag_search_name)
                $tag_count++;
            }*/

            $tag_current_name="";
            $tag_search_name="";
            $end_tag_search_name="";
            $end_tag_search_array=array();
            $open_tag_num=0;
            $start_tag_count=0;
            $parentID=0;
            $debug="";
            while(($tag_count<count($match_array))&&($tag_count<100)){
                if(isset($tag_current_name)){
                    $match_array[$tag_count]['debug_0']=$tag_current_name."-".$tag_search_name."-".$end_tag_search_name."-|";

                }
                    
                if(isset($match_array[$tag_count]['tag_name'])){
                    $tag_current_name=$match_array[$tag_count]['tag_name'];
                }
                
                //$end_tag_search_name=$end_tag_search_array[$open_tag_num];
                if(!isset($match_array[$tag_count]['tag_name'])){
                    $match_array[$tag_count]['tag_name']="";
                }
                $match_array[$tag_count]['children']=array();
                
                if(!isset($match_array[$tag_count]['tag_type'])){
                    $match_array[$tag_count]['tag_type']="";
                }
                if(($tag_search_name=="")&&($match_array[$tag_count]['tag_type']!="void")){
                    //$match_array[$current_tag]['tag_type']="void";
                    
                    if (!str_contains($tag_current_name, '/')) {
                        
                        $tag_search_name=$tag_current_name;
                        $end_tag_search_name='/'.$tag_search_name;
                        
                        $start_tag_count=$tag_count;
                        $parentID=$tag_count;
                        $open_tagID=$tag_count;
                        
                        $match_array[$tag_count]['end_tag']=$end_tag_search_name;
                        
                    }else{
                        
                        $match_array[$tag_count]['end_tag']=$tag_current_name;
                        
                        $end_tag_search_name="";
                        $tag_search_name="";
                    }
                }
                
                if($match_array[$tag_count]['tag_type']!="void"){
                    $end_tag_search_name=$tag_current_name;
                    $end_tag_search_array[$open_tag_num]=$end_tag_search_name;
                    $open_tag_num++;
                }
                
                if($tag_current_name==$end_tag_search_name){

                    $match_array[$tag_count]['end_tag']=$end_tag_search_name;
                    $match_array[$tag_count]['open_tag_ID']=$open_tagID;
                    $match_array[$open_tagID]['tag_type']=$match_array[$tag_count]['tag_type'];
                    $open_tag_num--;
                    //$match_array[$tag_count]['children'][]=$tag_count;
                    /*
                    $tag_count=$start_tag_count;
                    $tag_search_name="";
                    $end_tag_search_name="";
                    $start_tag_count=0;
                    $match_array[$tag_count]['parent']=$parentID;
                    $parentID=0;
                    $tag_search_name="";
                    */
                }
                //$end_tag_search_name='/'.$tag_search_name;
                $match_array[$tag_count]['debug']=$tag_current_name."-".$tag_search_name."-".$end_tag_search_name."-".var_export($end_tag_search_array,true)."|";
                /*
                $match_array[$tag_count]['end_tag']=$end_tag_search_name;
                if($match_array[$tag_count]['end_tag']==$match_array[$tag_count]['tag_name']){
                    $match_array[$tag_count]['open_tag']=$open_tagID;
                }
                */
                //if(!isset($match_array[$tag_count]['debug'])){
                    //$match_array[$tag_count]['debug']=$debug;
                //}
                //$match_array[$tag_count]['debug_code']=$debug;
                //$match_array[0]=$tag_search_name;
                //$match_array['debug']=$debug;
                $tag_count++;

                if($tag_count>100) die("Too Many");
            }

            
            for($x=0;$x<count($match_array);$x++){
                if(isset($tag_match_array[$inner_array[$x]['html']])){
                    $query.="| ".$x." |\n ".$inner_array[$x]."\n--".$match_array[$x]."=>".$tag_match_array[$inner_array[$x]['html']];//var_export($tag_match_array[$inner_array[$x]],true);
                    $buffer=str_replace($match_array[$x], $tag_match_array[$inner_array[$x]['html']], $buffer);
                }else{
                    //$buffer=str_replace($match_array[$x], "", $buffer);
                }
                
            }
            
            
            /*
            //$buffer=$query."--".$buffer;
            $pos = strpos($buffer, "<");
            $buffer=substr($buffer, $pos);
            //$buffer=$pos.$buffer;
            //$buffer = trim($buffer);
            */
            $server_name=$_GET['dcmshost'];
            //$server_name=$_SERVER['SERVER_NAME'];
            if($server_name=="creativeweblogic.info"){
                //$buffer="\n\n | ".$server_name." |Hello World IWLNet | ".var_export($match_array,true).$buffer;
            }else{
                //$buffer=$server_name."\n\n X Hello World".$buffer;
            }
            
            //$buffer="\n 666all done".$buffer;
            $pos = strpos($buffer, "<");
            $buffer=substr($buffer, $pos);
            return $buffer;
        }


            
        function callback_all($buffer)
        {
            $this->html_piece[]=$buffer;
            $this->callback_num+=1;
            //print $buffer;
            //$buffer="";
            return $buffer;
        }

        function set_all_extras()
        {
            
            $this->html_piece=array();
            $this->callback_num=0;
            
            
            $this->total_html=array();
            $this->combined_total_html="";

        }

        
        function callback_everyone($buffer)
        {
            $buffer=implode($this->total_html);
            
            return $buffer;
        }
        function callback_combined($buffer,$order)
        {
            print $buffer;
            if(!isset($this->total_html[$order])){
                $this->total_html[$order]=$buffer;
                $this->combined_total_html=implode($this->total_html);
            }
            
            return $buffer;
        }


            

        function include_read($inc_dir)
        {
            global $log;
            global $r;
            
            if(file_exists($inc_dir)){
                print $inc_dir."<br>\n";
                //ob_start("callback_template");
                include($inc_dir);
                //$return_buffer = ob_get_contents();
                ob_end_clean();
            }else{
                $return_buffer="";
            }
            
            //return $return_buffer;
        }
            /*
            global $callback_num;
            global $html_piece;
            $html_piece=array();
            $callback_num=0;
            */
        function callback_vars_all($buffer)
        {
            $html_piece[]=$buffer;
            $callback_num+=1;
            //print $buffer;
            //$buffer="";
            return $buffer;
        }
        /*
        static $total_html;
        global $combined_total_html;
        $total_html=array();
        $combined_total_html="";
        */
        function callback_vars_everyone($buffer)
        {
            $buffer=implode($this->total_html);
            
            return $buffer;
        }

        function callback_vars_combined($buffer,$order)
        {
            print $buffer;
            if(!isset($total_html[$order])){
                $this->total_html[$order]=$buffer;
                $this->combined_total_html=implode($this->total_html);
            }
            
            return $buffer;
        }


	

        function add_tag_array($tag_match_array)
        {
            $this->tag_match_array=$tag_match_array;
        }

        function add_current_domain()
        {
            $this->current_domain=str_replace("www.", "",$_SERVER['HTTP_HOST']);
            
        }

        function GetModulesPermissions(){
            //echo"yyy";
            //$r=new ReturnRecord();
            global $r;
            $RetArr=array();
            $sql="SELECT modulesID FROM domains_modules WHERE domainsID=$_SESSION[domainsID]";
            //print $sql;
            $rslt=$r->RawQuery($sql);
            while($myrow=$r->Fetch_Array()){
                $RetArr[]=$myrow[0];
            }	
            return $RetArr;
        }

        function convert_high_ascii($s) {
            $HighASCII = array(
                "!\xc0!" => 'A',    # A`
                "!\xe0!" => 'a',    # a`
                "!\xc1!" => 'A',    # A'
                "!\xe1!" => 'a',    # a'
                "!\xc2!" => 'A',    # A^
                "!\xe2!" => 'a',    # a^
                "!\xc4!" => 'Ae',   # A:
                "!\xe4!" => 'ae',   # a:
                "!\xc3!" => 'A',    # A~
                "!\xe3!" => 'a',    # a~
                "!\xc8!" => 'E',    # E`
                "!\xe8!" => 'e',    # e`
                "!\xc9!" => 'E',    # E'
                "!\xe9!" => 'e',    # e'
                "!\xca!" => 'E',    # E^
                "!\xea!" => 'e',    # e^
                "!\xcb!" => 'Ee',   # E:
                "!\xeb!" => 'ee',   # e:
                "!\xcc!" => 'I',    # I`
                "!\xec!" => 'i',    # i`
                "!\xcd!" => 'I',    # I'
                "!\xed!" => 'i',    # i'
                "!\xce!" => 'I',    # I^
                "!\xee!" => 'i',    # i^
                "!\xcf!" => 'Ie',   # I:
                "!\xef!" => 'ie',   # i:
                "!\xd2!" => 'O',    # O`
                "!\xf2!" => 'o',    # o`
                "!\xd3!" => 'O',    # O'
                "!\xf3!" => 'o',    # o'
                "!\xd4!" => 'O',    # O^
                "!\xf4!" => 'o',    # o^
                "!\xd6!" => 'Oe',   # O:
                "!\xf6!" => 'oe',   # o:
                "!\xd5!" => 'O',    # O~
                "!\xf5!" => 'o',    # o~
                "!\xd8!" => 'Oe',   # O/
                "!\xf8!" => 'oe',   # o/
                "!\xd9!" => 'U',    # U`
                "!\xf9!" => 'u',    # u`
                "!\xda!" => 'U',    # U'
                "!\xfa!" => 'u',    # u'
                "!\xdb!" => 'U',    # U^
                "!\xfb!" => 'u',    # u^
                "!\xdc!" => 'Ue',   # U:
                "!\xfc!" => 'ue',   # u:
                "!\xc7!" => 'C',    # ,C
                "!\xe7!" => 'c',    # ,c
                "!\xd1!" => 'N',    # N~
                "!\xf1!" => 'n',    # n~
                "!\xdf!" => 'ss'
            );
            $find = array_keys($HighASCII);
            $replace = array_values($HighASCII);
            $s = preg_replace($find,$replace,$s);
            return $s;
       }

       function dirify($text)
        {
            $text=strtolower($text);
            $code_entities_match = array(' ','--','&quot;','!','@','#','$','%','^','&','*','(',')','_','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','.','*','+','~','`','=');
            $code_entities_replace = array('-','-','','','','','','','','','','','','','','','','','','','','','','','');
            $text = str_replace($code_entities_match, $code_entities_replace, $text);
            return $text;
        }

        function exceptions_error_handler($severity, $message, $filename, $lineno) {
            throw new ErrorException($message, 0, $severity, $filename, $lineno);
        }
        
        //set_error_handler('exceptions_error_handler');
        
        public function strip_capitals($var_array=array()){
            $output_array=array();
            foreach($var_array as $key=>$val){
                $key=strtolower($key);
                $output_array[$key]=$val;
            }
            $output_array= array_merge($output_array, $var_array);
        
            return $output_array;
        }

        function check_base64($s){
            // Check if there are valid base64 characters
            if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s)) return false;
        
            // Decode the string in strict mode and check the results
            $decoded = base64_decode($s, true);
            if(false === $decoded) return false;
        
            // Encode the string again
            if(base64_encode($decoded) != $s) return false;
        
            return true;
        }

        function is_base64($str){
        
            if($str === base64_encode(base64_decode($str))){
                print "is 64 ->".base64_encode($str)."-";
                print "2 is 64 ->".base64_encode(base64_encode($str))."-";
                return true;
            }
            return false;
        }

        function Set_Admin_Vars(){
            $commision_level = 5.00;
            $gst_percentage = 10.00;
            $new_user_credit=0.00;
            $new_referred_user_credit=20.00;
            $referrer_user_credit=20.00;
            $Message="";
        }
    }


?>