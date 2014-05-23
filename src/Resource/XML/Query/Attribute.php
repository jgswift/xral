<?php
namespace xral\Resource\XML\Query {
    use xral;
    
    class Attribute extends xral\Resource\XML\Statement {
        
        /**
         * modifies xpath to limit matching attribute
         */
        function execute() {
            $query = $this->getQuery();
            
            $args = $this->getArguments();
            
            if(!empty($args)) {
                $name = '';
                if(isset($args[0])) {
                    $name = $args[0];
                }
                
                $value = null;
                if(isset($args[1])) {
                    $value = $args[1];
                }
                
                $operator = '=';
                
                if(isset($args[2])) {
                    $operator = $args[2];
                }
                
                $negate = false;
                if(strpos($operator, '!') !== false)
                {
                    $negate = true;
                    $operator = str_replace('!','',$operator);
                }

                $newPath = '@'.$name;
                
                if(!is_null($value)) {
                    $newPath.=$operator.'"'.$value.'"';
                }
                
                if($negate) {
                    $newPath = 'not('.$newPath.')';
                }

                $newPath = '['.$newPath.']';
            }
            
            if(isset($newPath)) {         
                $query['xpath'] = $query['xpath'] . $newPath;
            }
        }
    }
}