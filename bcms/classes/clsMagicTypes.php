<?php
    class clsMagicTypes
    {
        /**  Location for overloaded data.  */
        private $data = array();

        private $objects = array();

        private $data_prefix = 'data_';
        private $object_prefix = 'obj_';

        
        public function __set($name, $value)
        {
            if (is_object($value)) {
                $this->objects[$this->object_prefix.$name] = $value;
            }
            $this->data[$this->data_prefix.$name] = $value;
        }

        public function set_array($data_arr)
        {
           foreach($data_arr as $key=>$val){
                if (is_object($val)) {
                    $this->objects[$this->object_prefix.$key] = $val;
                }else{
                    $this->data[$this->data_prefix.$key] = $val;
                }
                
            }
        }

        public function __get($name)
        {
            if (array_key_exists($this->data_prefix.$name, $this->data)) {
                return $this->data[$this->data_prefix.$name];
            }
            if (array_key_exists($this->object_prefix.$name, $this->objects)) {
                return $this->objects[$this->object_prefix.$name];
            }
        }

        public function __isset($name)
        {
            return isset($this->data[$name]);
        }

        public function __unset($name)
        {
            unset($this->data[$name]);
        }
    }