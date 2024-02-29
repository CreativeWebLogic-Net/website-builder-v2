<?php

    class clsOutputBuffer{
        public $total_html=array();
        public $html_pieces=0;

        public $bo_total_html=array();
        public $bo_html_pieces=0;

        public $bo_array=array();
        public $bo_total=0;

        function __construct (){



            

        }

        
        public function add_callback(){
            if($this->bo_total>0){
                $this->output_callback();
            }
            $bo = ob_start ("self::callback_ob") ;
            $this->add_bo_array($bo);
        }

        
        public function output_callback(){
            ob_end_clean();
        }

        
        function add_bo_array($bo){
            $this->bo_array[$this->bo_total] = $bo;
            $this->bo_total++;
        }
        
        function get_bo_array($bo_number){
            return $this->bo_array[$bo_number];
        }


        function callback_ob($buffer){
            //print $buffer;
            if(!isset($this->total_html[$this->html_pieces])){
                $this->total_html[$this->html_pieces]=$buffer;
                //$combined_total_html=implode($total_html);
                $this->html_pieces++;
            }
            
            return $buffer;
        }

        
        function return_all_html(){
            $all_html=implode($this->total_html);
            
            return $all_html;
        }

        
        
        function bo_add_html($html){
            $all_html=implode($this->total_html);
            
            return $all_html;
        }

    }

?>