<?php
namespace xral\Resource\YML\Filter {
    use xral;
    use kfiltr;
    use qtil;
    
    class Ypath {
        use kfiltr\Filter;
        
        /**
         * Stores ypath query
         * @var xral\Resource\YML\Query 
         */
        private $query;
        
        /**
         * Stores ypath expression
         * @var string 
         */
        private $ypath;
        
        /**
         * Default ypath filter constructor
         * @param xral\Resource\YML\Query $query
         */
        function __construct(xral\Resource\YML\Query $query) {
            $this->query = $query;
            $this->ypath = $query['ypath'];
        }
        
        /**
         * Executes ypath expression
         * @param mixed $input
         * @return mixed
         */
        function execute($input) {
            $ypath = $this->ypath;
            
            $clauses = explode('|',$ypath);
            
            $results = [];
            foreach($clauses as $clause) {
                $results = array_merge($results,$this->search($input,trim($clause)));
            }
            
            return $results;
        }
        
        protected function search($input,$ypath) {
            return $this->recursiveSearch($input, explode('/',$ypath), 0);
        }
        
        protected function recursiveSearch($input,$cpaths,$depth) {
            $path = $cpaths[$depth];
                
            $matches = [];
            $hasclauses = preg_match("/\[(.*?)\]/", $path, $matches);
            $clauses = [];
            if($hasclauses) {
                $clauses = $this->parseClause($matches[1]);
                $path = str_replace($matches[0],'',$path);
            }
            $results = [];
            foreach($input as $k=>$v) {
                $this->searchResult($k,$v,$path,$results,$cpaths,$depth,$clauses);
            }

            return $results;
        }
        
        protected function searchResult($k, $v, $path, &$results, $cpaths, $depth,$clauses) {
            if(qtil\ArrayUtil::isIterable($v) && $k==$path) {
                if(count($cpaths)-1 > $depth) {
                    foreach($v as $i) {
                        $results = array_merge($results,$this->recursiveSearch($i,$cpaths,$depth+1));
                    }
                } else {
                    if(!empty($clauses)) {
                        $results = array_merge($results,$this->match($v,$clauses));
                    } else {
                        $results = array_merge($results,$v);
                    }
                }
            } elseif($k==$path) {
                $results[] = $v;
            }
        }
        
        protected function match($array,$clauses) {
            foreach($array as $k=>$v) {
                foreach($clauses as $name => $match) {
                    if(!isset($v[$name]) ||
                       (string)$v[$name] !== $match) {
                        unset($array[$k]);
                        break;
                    }
                }
            }
            
            return $array;
        }
        
        protected function parseClause($clause) {
            $clauses = preg_split("/ (and|or) /", $clause);
            
            $results = [];
            foreach($clauses as $c) {
                $sides = explode('=',$c);
                
                $sides[1] = str_replace('"','',$sides[1]);
                $results[$sides[0]] = $sides[1];
            }
            
            return $results;
        }
    }
}