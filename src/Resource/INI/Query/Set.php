<?php
namespace xral\Resource\INI\Query {
    use xral;
    
    class Set extends xral\Resource\INI\Statement {
        
        /**
         * Set statement
         * specify variable and new value
         */
        function execute() {
            $query = $this->getQuery();
            $args = $this->getArguments();
            
            $name = $args[0];
            
            $section = null;
            if(strpos($name,'.') !== false) {
                list($section,$name) = explode('.',$name);
            }
            
            $value = $args[1];
                        
            $query->attach(xral\Stream\Query::COMPLETE,function($s,$e)use($name,$value,$section) {
                $result = $e['result'];
                $updateFn = function($items)use($name,$value) {
                    foreach($items as $k=>$v) {
                        if($name==$k) {
                            $items[$k] = $value;
                        }
                    }
                    
                    return $items;
                };
                
                foreach($result as $s => $values) {
                    if(!is_null($section)) {
                        if($s==$section) {
                            $result[$section] = $updateFn($values);
                        } else {
                            continue;
                        }
                    } else {
                        $result[$s] = $updateFn($values);
                    }
                }
                
                $e['result'] = $result;
            });
        }
    }
}