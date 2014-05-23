<?php
namespace xral\Resource\XML\Filter\DOMDocument {
    use xral;
    use kfiltr;
    
    class XPath {
        use kfiltr\Filter;
        
        private $query;
        private $xpath;
        
        function __construct(xral\Resource\XML\Query $query) {
            $this->query = $query;
            $this->xpath = $query['xpath'];
        }
        
        function execute($input) {
            $xpath = $this->xpath;
            
            if($input instanceof \DOMDocument) {
                $dompath = new \DOMXPath($input);
                
                $namespaces = $this->query->getNamespaces();
                
                foreach($namespaces as $prefix => $ns) {
                    $dompath->registerNamespace($prefix, $ns);
                }
                
                return $dompath->query($xpath);
            }
            
            return new \DOMNodeList();
        }
    }
}