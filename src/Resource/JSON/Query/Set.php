<?php
namespace xral\Resource\JSON\Query {
    use xral;
    use qtil;
    
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
                    
                    foreach($result as $k => $item) {
                        if(is_array($item) || $item instanceof \ArrayAccess) {
                            if(isset($item[$name])) {
                                $item[$name] = $value;
                                $result[$k][$name] = $value;
                            }
                        } elseif(is_object($item)) {
                            if(isset($item->$name)) {
                                $item->$name = $value;
                            }
                        }
                    }
                    
                    $e['result'] = $result;
                });
            }
        }
    }
}