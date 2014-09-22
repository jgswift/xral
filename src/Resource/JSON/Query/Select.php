<?php
namespace xral\Resource\JSON\Query {
    use xral;
    
    class Select extends xral\Resource\XML\Statement {
        function execute() {
            $query = $this->getQuery();
            $qinq = $query->getIntegratedQuery();
            
            $collection = $query->getCollection();
            
            $args = $this->getArguments();
            if(count($args) === 1) {
                if(is_string($args[0])) {
                    $name = $args[0];

                    $query->attach(xral\Stream\Query::COMPLETE,function($query,$e)use($name) {
                        $result = $e['result'];
                        if(isset($result[$name])) {
                            $e['result'] = $result[$name];
                        } else {
                            $e['result'] = $result;
                        }
                    });
                } elseif(is_callable($args[0])) {
                    $fn = $args[0];
                    $qinq->select($fn);
                }
            }
        }
    }
}