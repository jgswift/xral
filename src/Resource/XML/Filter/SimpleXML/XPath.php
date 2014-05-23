<?php
namespace xral\Resource\XML\Filter\SimpleXML {
    use xral;
    use kfiltr;
    use qtil;
    
    class XPath {
        use kfiltr\Filter;
        
        /**
         * Stores filter query
         * @var xral\Resource\XML\Query 
         */
        private $query;
        
        /**
         * Stores local xpath
         * @var string
         */
        private $xpath;
        
        /**
         * Default xpath filter constructor
         * @param xral\Resource\XML\Query $query
         */
        function __construct(xral\Resource\XML\Query $query) {
            $this->query = $query;
            $this->xpath = $query['xpath'];
        }
        
        /**
         * Performs xpath limiter procedure
         * @param \SimpleXMLElement|array $input
         * @return mixed
         */
        function execute($input) {
            $xpath = $this->xpath;
            
            if($input instanceof \SimpleXMLElement &&
               isset($input->$xpath)) {
                return $input->$xpath;
            }
            
            if(is_object($input) && method_exists($input, 'xpath')) {
                $namespaces = $this->query->getNamespaces();
                
                foreach($namespaces as $prefix => $ns) {
                    $input->registerXPathNamespace($prefix, $ns);
                }
                
                return $input->xpath($xpath);
            }
            
            elseif(qtil\ArrayUtil::isIterable($input)) {
                $results = [];
                foreach($input as $i) {
                    if(is_object($i) && method_exists($i, 'xpath')) {
                        $results[] = $this->execute($i);
                    }
                }
                
                return $results;
            }
        }
    }
}