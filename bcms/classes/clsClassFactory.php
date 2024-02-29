<?php


    class clsClassFactory{

        public static $all_vars=array();
        public static $vars;
        public $name_map=array('clsLog'=>'log','clsDatabaseInterface'=>'r','clsSession'=>'sess','clsAssortedFunctions'=>'log','clsExceptionHandler'=>'ex');

        function __construct(){
            
            self::Initialize_Basic_Classes();
            
		}
        function Initialize_Basic_Classes(){
            //exit("todo");
            //self::$vars=new clsObjectAccess();

            //print("todo 001");
            $ex=new clsExceptionHandler();
            self::$all_vars['ex']=new clsGenericProxy($ex);
            //print("todo 002");
            $log=new clsLog();
            self::$all_vars['log']=new clsGenericProxy($log);
            //print("todo 003");
            $r=new clsDatabaseInterface();
            self::$all_vars['r']=new clsGenericProxy($r);
            ///print("todo 004");
            $sess=new clsSession();
            //print("todo 0041");
            self::$all_vars['sess']=new clsGenericProxy($sess);
            //print("todo 005");
            $a=new clsAssortedFunctions();
            self::$all_vars['a']=new clsGenericProxy($a);
            //print("todo 006");
            //print_r(self::$all_vars);
            //exit("todo");
		}

        static function Initialize_Class_Variables($name){
            foreach(self::$all_vars as $key=>$val){

            }
		}

        static function Get_Class_Variable($name){
            $pos = strpos('cls', $name);
            if($pos>-1){
                $target_name=self::$name_map[$name];
            }else{
                $target_name=$name;
            }
            return self::$all_vars[$target_name];
		}

        static function Set_Class_Variable($name,$object){
            self::$all_vars[$name]=$object;
		}


    }