<?php
namespace xral\Resource\XML\Query {
    use xral;
    use qtil;
    
    class Where extends xral\Resource\XML\Statement {
        
        /**
         * modifies xpath to limit query
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
                    $nvalue .= 'text()='.'"'.$v.'"';
                    $c++;
                }
                
                $value = $nvalue;
                
                if(strpos($query['xpath'],'[') !== false) {
                    $newPath = ' '.$attributeOperator.' '.$name;
                } else {
                    $newPath = '/'.$name;
                }
                
                if(!is_null($value)) {
                    $newPath.='['.$value.']';
                }
            }
            
            if(isset($newPath)) {         
                $query['xpath'] = $query['xpath'] . $newPath;
            }
        }
    }
}