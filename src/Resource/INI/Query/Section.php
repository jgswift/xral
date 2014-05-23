<?php
namespace xral\Resource\INI\Query {
    use xral;
    use qinq;
    
    class Section extends xral\Resource\INI\Statement {
        
        /**
         * Section statement
         * limit query to individual section
         */
        function execute() {
            $query = $this->getQuery();
            $args = $this->getArguments();
            
            $section = $args[0];
                        
            $query->attach(xral\Stream\Query::COMPLETE,function($s,$e)use($section) {
                $result = $e['result'];
                
                if(isset($result[$section])) {
                    $result = new qinq\Collection([$section=>$result[$section]]);
                }
                
                $e['result'] = $result;
            });
        }
    }
}