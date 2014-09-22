<?php
namespace xral\Resource\XML\Query {
    use xral;
    use qtil;
    
    class Delete extends XPath {
        
        /**
         * updates original document with changes
         */
        function execute() {
            $query = $this->getQuery();
            
            parent::execute();
            
            $query->attach(xral\Stream\Query::COMPLETE,function($query, $e) {
                $e['result']->map(function($xml) {
                    if($xml instanceof \SimpleXMLElement) {
                        $this->deleteNode($xml->xpath('parent::*')[0]);
                    } elseif($xml instanceof \DOMNodeList) {
                        foreach($xml as $item) {
                            $xpath = new \DOMXPath($item->ownerDocument);
                            $nodes = $xpath->query($item->getNodePath().'/parent::*');
                            foreach($nodes as $n) {
                                $this->deleteNode($n);
                            }
                        }
                    }
                });
            });
        }
        
        protected function deleteNode($node) {
            if($node instanceof \SimpleXMLElement) {
                $node = dom_import_simplexml($node);
            } 
            
            if($node instanceof \DOMElement) {
                $node->parentNode->removeChild($node);
            }
        }
    }
}