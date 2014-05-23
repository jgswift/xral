<?php
namespace xral\Resource\XML\Query {
    use xral;
    
    class XPath extends xral\Resource\XML\Statement {
        /**
         * Explicitly set query xpath
         */
        function execute() {
            $query = $this->getQuery();
            
            $args = $this->getArguments();
            
            if(isset($args[0])) {         
                $query['xpath'] .= (string)$args[0];
            }
        }
    }
}