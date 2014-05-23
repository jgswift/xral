<?php
namespace xral\Resource\YML\Query {
    use xral;
    use qtil;
    
    class Where extends xral\Resource\YML\Statement {
        
        /**
         * Where statement
         * Modifies ypath to limit yml query results
         * @param string $attributeOperator
         */
        function execute($attributeOperator='|') {
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
                
                $operator = 'or';
                
                if(isset($args[2])) {
                    $operator = trim($args[2]);
                }
                
                if(!qtil\ArrayUtil::isIterable($value)) {
                    $value = [$value];
                }
                
                $nvalue = '';
                $c=0;
                foreach($value as $v) {
                    if($c>0) {
                        $nvalue .= ' '.$operator.' ';
                    }
                    $nvalue .= $name.'='.'"'.$v.'"';
                    $c++;
                }
                
                $value = $nvalue;
                
                if(strpos($query['ypath'],'[') !== false) {
                    $newPath = ' '.$attributeOperator.' '.$name;
                } else {
                    $newPath = '';
                }
                
                if(!is_null($value)) {
                    $newPath.='['.$value.']';
                }
            }
            
            if(isset($newPath)) {         
                $query['ypath'] = $query['ypath'] . $newPath;
            }
        }
    }
}