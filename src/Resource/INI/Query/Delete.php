<?php
namespace xral\Resource\INI\Query {
    use xral;
    
    class Delete extends xral\Resource\INI\Statement {
        
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
            
            $query->attach(xral\Stream\Query::COMPLETE,function($s,$e)use($name,$section) {
                $result = $e['result'];
                
                foreach($result as $key => $section) {
                    if(isset($section[$name])) {
                        unset($result[$key][$name]);
                    }
                }
            });
        }
    }
}