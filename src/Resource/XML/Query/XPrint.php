<?php
namespace xral\Resource\XML\Query {
    use xral;
    
    class XPrint extends xral\Resource\XML\Statement {
        
        /**
         * Explicity set query xpath with vsprintf
         * @see vsprintf
         */
        function execute() {
            $query = $this->getQuery();
            
            $args = $this->getArguments();
            
            $format = $args[0];
            array_shift($args);
            
            $query['xpath'] .= vsprintf($format, $args);
        }
    }
}