<?php
namespace xral\Resource\JSON\Query {
    use xral;
    
    class Where extends xral\Resource\XML\Statement {
        function execute() {
            $query = $this->getQuery();
            $qinqQuery = $query->getIntegratedQuery();
            
            $args = $this->getArguments();
            $argCount = count($args);
            $callable = null;
            if($argCount > 0) {
                if($argCount == 1 && is_callable($args[0])) {
                    $callable = $args[0];
                } elseif($argCount == 2 && is_string($args[0]) && is_scalar($args[1])) {
                    $name = $args[0];
                    $value = $args[1];
                    
                    $callable = function($array)use($name,$value) {
                        return ($array[$name] == $value) ? true : false;
                    };
                }
            }
            
            if(is_callable($callable)) {
                $qinqQuery->where($callable);
            }
        }
    }
}