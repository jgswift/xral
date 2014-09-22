<?php
namespace xral\Resource\JSON\Query {
    use xral;
    
    class Insert extends xral\Resource\XML\Statement {
        function execute() {
            $query = $this->getQuery();
            
            $arguments = $this->getArguments();
            
            if(count($arguments) == 1) {
                $value = $arguments[0];
                
                $query->attach(xral\Stream\Query::COMPLETE,function($query,$e)use($value) {
                    $result = $e['result'];
                    
                    $result[] = $value;
                    
                    $e['result'] = $result;
                });
            }
        }
    }
}