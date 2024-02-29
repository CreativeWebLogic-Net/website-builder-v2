<?php

class clsGenericProxy
{
    public $obj;
    private $handler;

    
    //public function __construct($target, callable $exceptionHandler = null)
    public function __construct($target, callable $exceptionHandler=null)
    {
        $this->obj = $target;
        
        $this->handler = $exceptionHandler;
    }
    
    public function __get($name)
        {
            /*
            if (array_key_exists($name, $this->data)) {
                return $this->data[$name];
            }
            */
            return $this->obj;
            
        }
        
        public function get_object()
        {
            
            return $this->obj;
            
        }
    
    
    public function __call($method, $arguments)
    {
        try {
            return call_user_func_array([$this->obj, $method], $arguments);
        } catch (Exception $e) {
            // catch all
            if (!is_null($this->handler)) {
                throw call_user_func($this->handler, $e);
            } else {
               throw $e;
            }
        }
    }
}
