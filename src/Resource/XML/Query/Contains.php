<?php
namespace xral\Resource\XML\Query {
    use xral;
    use qtil;
    
    class Contains extends xral\Resource\XML\Statement {
        
        /**
         * modifies xpath to limit to elements which contain input
         * @throws \InvalidArgumentException
         */
        function execute() {
            $query = $this->getQuery();
            
            $args = $this->getArguments();
            
            $numArgs = count($args);
            if($numArgs == 2) {
                $name = $args[0];
                
                $value = $args[1];
            } else {
                throw new \InvalidArgumentException("XPath Contains must have 2 arguments");
            }
            
            $valueFn = function($value) {
                if($value!=='.') {
                    return "'$value'";
                } else {
                    return $value;
                }
            };
            
            if(qtil\ArrayUtil::isIterable($value)) {
                $nvalue = [];
                foreach($value as $v) {
                    $nvalue[] = $valueFn($v);
                }
                
                $value = implode(',',$nvalue);
            } else {
                $value = $valueFn($value);
            }
             
            $query['xpath'] .= '[contains('.$name.','.$value.')]';
        }
    }
}