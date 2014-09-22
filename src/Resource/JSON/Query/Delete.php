<?php
namespace xral\Resource\JSON\Query {
    use xral;
    
    class Delete extends xral\Resource\XML\Statement {
        function execute() {
            $query = $this->getQuery();
            
            $arguments = $this->getArguments();
            
            $query->attach(xral\Stream\Query::COMPLETE,function($query,$e) {
                $result = $e['result'];
                
                $result->map(function() {
                    return null;
                });

                $e['result'] = $result;
            });
        }
    }
}