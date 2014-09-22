<?php
namespace xral\Resource\XML\Query {
    use xral;
    use qtil;
    
    class Insert extends xral\Resource\XML\Statement {
        
        /**
         * updates original document with changes
         */
        function execute() {
            $query = $this->getQuery();
            
            $arguments = $this->getArguments();
            
            if(count($arguments) == 1) {
                $value = $arguments[0];
                
                $query->attach(xral\Stream\Query::COMPLETE,function($query, $e)use($value) {
                    $parent = null;
                    if(isset($e['result'])) {
                        $parent = $e['result'][0];
                    } else {
                        $parent = $query->getDocument();
                    }
                    
                    if(!is_null($parent)) {
                        $this->insertNode($parent,$value);
                    }
                });
            }
        }
        
        protected function insertNode($node, $value) {
            if($node instanceof \SimpleXMLElement) {
                $this->appendSimple($value, $node);
            } elseif($node instanceof \DOMNodeList) {
                $this->appendDOM($value,$node->item(0));
            } elseif($node instanceof \DOMNode) {
                $this->appendDOM($value, $node);
            }
        }
        
        function appendSimple($mixed, $node) {
            foreach($mixed as $key => $value) {
                if(is_array($value)) {
                    if(!is_numeric($key)){
                        $subnode = $node->addChild("$key");
                        $this->appendSimple($value, $subnode);
                    }
                    else{
                        $subnode = $node->addChild("item$key");
                        $this->appendSimple($value, $subnode);
                    }
                }
                else {
                    $node->addChild("$key",htmlspecialchars("$value"));
                }
            }
        }
        
        protected function appendDOM($mixed, $domElement) {
            $document = $this->getQuery()->getDocument();
            
            if(is_array($mixed)) {
                foreach($mixed as $index => $mixedElement) {
                    if(is_int($index) ) {
                        if($index == 0) {
                            $node = $domElement;
                        } else {
                            $node = $document->createElement($domElement->tagName);
                            $domElement->parentNode->appendChild($node);
                        }
                    } else {
                        $node = $document->createElement($index);
                        $domElement->appendChild($node);
                    }

                    $this->appendDOM($mixedElement, $node);
                }
            } else {
                $domElement->appendChild($document->createTextNode($mixed));
            }
        }
    }
}