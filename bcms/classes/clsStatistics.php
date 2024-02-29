<?php

    class clsStatistics{
        private $size_data=array();
        private $time_data=array();
        private $time_taken_data=array();
        

        function __construct(){
            
		}

        function start_timer($time_tag){
            $this->time_data[$time_tag]=hrtime();
        }

        function end_timer($time_tag){
            $this->time_taken_data[$time_tag]=hrtime()-$this->time_data[$time_tag];
        }

        function size_data($size_tag,$size){
            $this->size_data[$size_tag]=$size;
        }

        function generate_size_tag(){
            $this->size_data[$size_tag]=$size;
        }

        

        function variable_size_data($size_tag,$variable_array=array()){
            $serialized = serialize($variable_array);
            $size = strlen($serialized); // or mb_strlen($serialized, '8bit')
            
            $this->size_data[$size_tag]=$size;
        }
    }
