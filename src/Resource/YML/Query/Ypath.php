<?php
namespace xral\Resource\YML\Query {
    use xral;
    
    class Ypath extends xral\Resource\YML\Statement {
        
        /**
         * Explicitly set ypath expression
         */
        function execute() {
            $query = $this->getQuery();
            
            $arguments = $this->getArguments();
            
            $ypath = $arguments[0];
            
            if(!empty($ypath)) {
                $query['ypath'] = $ypath;
            }
        }
    }
}
