<?php
namespace xral\Resource\YML\Query {
    use xral;
    
    class Set extends xral\Resource\YML\Statement {
        
        /**
         * Set statement
         * @throws xral\Exception
         */
        function execute() {
            $query = $this->getQuery();
            $args = $this->getArguments();
            
            $name = $args[0];
            
            $value = $args[1];
                        
            $query->attach(xral\Stream\Query::COMPLETE,function($s,$e)use($name,$value) {
                $result = $e['result'];
                
                foreach($result as $k => $item) {
                    if(is_array($item)) {
                        if(isset($item[$name])) {
                            $result[$k][$name] = $value;
                        } else {
                            foreach($item as $k2 => $v2) {
                                if(is_array($v2) && isset($v2[$name]))
                                $result[$k][$k2][$name] = $value;
                            }
                        }
                    }
                }
            });
        }
    }
}