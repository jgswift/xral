<?php
namespace xral\Resource\INI\Query {
    use xral;
    
    class Insert extends xral\Resource\INI\Statement {
        
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
                
                foreach($result as $key => $section) {
                    $result[$key][$name] = $value;
                }
            });
        }
    }
}