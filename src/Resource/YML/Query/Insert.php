<?php
namespace xral\Resource\YML\Query {
    use xral;
    
    class Insert extends xral\Resource\YML\Statement {
        
        /**
         * Insert statement
         * @throws xral\Exception
         */
        function execute() {
            $query = $this->getQuery();
            $args = $this->getArguments();
            
            $value = $args[0];
            
            $query->attach(xral\Stream\Query::COMPLETE,function($s,$e)use($value) {
                $result = $e['result'];
                
                foreach($result as $k => $item) {
                    if(is_array($item)) {
                        $result[$k][] = $value;
                    }
                }
            });
        }
    }
}