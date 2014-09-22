<?php
namespace xral\Resource\JSON\Query {
    use xral;
    
    class Set extends xral\Resource\XML\Statement {
        
        /**
         * updates original document with changes
         */
        function execute() {
            $query = $this->getQuery();
            
            $arguments = $this->getArguments();
            
            if(count($arguments) > 1) {
                $name = $arguments[0];
                    
                $value = null;

                if(isset($arguments[1])) {
                    $value = $arguments[1];
                }
                
                $query->attach(xral\Stream\Query::COMPLETE,function($query,$e)use($name, $value) {
                    $result = $e['result'];
                    
                    $result->map(function($item)use($name,$value) {
                        if(is_array($item) || $item instanceof \ArrayAccess) {
                            $item[$name] = $value;
                        } elseif(is_object($item)) {
                            $item->$name = $value;
                        }
                        
                        return $item;
                    });
                    
                    $e['result'] = $result;
                });
            }
        }
    }
}