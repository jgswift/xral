<?php
namespace xral\Resource\INI\Query {
    use xral;
    use qinq;
    
    class Where extends xral\Resource\INI\Statement {
        
        /**
         * Where statement
         * Limits query result using expression
         */
        function execute() {
            $query = $this->getQuery();
            $args = $this->getArguments();
            
            $name = $args[0];
            
            $section = null;
            if(strpos($name,'.') !== false) {
                list($section,$name) = explode('.',$name);
            }
            
            $value = null;
            
            if(isset($args[1])) {
                $value = $args[1];
            }
            
            $query->attach(xral\Stream\Query::COMPLETE,function($s,$e)use($name,$value,$section) {
                $result = $e['result'];
                
                if(!is_null($section)) {
                    if(isset($result[$section])) {
                        $result = new qinq\Collection([$section=>$result[$section]]);
                    }
                }
                
                $result->where(function($data)use($name,$value) {
                    if(!is_null($value)) {
                        return array_key_exists($name,$data) && $data[$name] == $value;
                    } else {
                        return array_key_exists($name,$data);
                    }
                });
                
                $e['result'] = $result;
            });
        }
    }
}