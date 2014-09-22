<?php
namespace xral\Resource\YML\Query {
    use xral;
    
    class Delete extends Ypath {
        
        /**
         * Delete statement
         * @throws xral\Exception
         */
        function execute() {
            $name = $this->getArguments()[0];
            parent::execute($name);
            
            $this->getQuery()->attach(xral\Stream\Query::COMPLETE,function($s,$e)use($name) {
                $result = $e['result'];
                if(!empty($name) && isset($result[$name])) {
                    if(is_array($result[$name])) {
                        foreach($result[$name] as $k => $item) {
                            $result[$name][$k] = null;
                        }
                    }
                    
                }
            });
        }
    }
}