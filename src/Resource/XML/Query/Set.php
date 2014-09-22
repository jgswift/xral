<?php
namespace xral\Resource\XML\Query {
    use xral;
    use qtil;
    
    class Set extends xral\Resource\XML\Statement {
        
        /**
         * updates original document with changes
         */
        function execute() {
            $query = $this->getQuery();
            
            $arguments = $this->getArguments();
            
            if(count($arguments) > 1) {
                $name = $arguments[0];
                    
                $value = null;

                if(isset($arguments[1])) {
                    $value = $arguments[1];
                }
                
                $query->attach(xral\Stream\Query::COMPLETE,function($query, $e)use($name, $value) {
                    $result = $e['result'];
                    $result->map(function($item)use($name,$value) {
                        if($item instanceof \DOMNodeList) {
                            foreach($item as $d) {
                                $this->updateNode($d,$d->tagName,$name,$value);
                            }
                        } elseif($item instanceof \SimpleXMLElement) {
                            $this->updateNode($item,$item->getName(),$name,$value);
                        }
                    });
                });
            }
        }
        
        protected function updateNode(&$node, $aggregateName, $name, $value) {
            $nodeName = $this->getElementName($node);
            if($nodeName === $name ||
               $aggregateName === $name || 
               qtil\StringUtil::endsWith($nodeName, $name)) {
                if($node instanceof \SimpleXMLElement) {
                    $node[0] = $value;
                } elseif($node instanceof \DOMNode) {
                    $node->nodeValue = $value;
                }
            }

            if($node instanceof \SimpleXMLElement) {
                $children = $node->children();
            } elseif($node instanceof \DOMNode) {
                $children = $node->childNodes;
            }

            if(!empty($children)) {
                foreach($children as $child) {
                    $this->updateNode($child,$aggregateName.'/'.$this->getElementName($child),$name,$value);
                }
            }
        }
        
        /**
         * Retrieves element tag name
         * @param mixed $element
         * @return string|null
         */
        protected function getElementName($element) {
            if($element instanceof \DOMElement) {
                return $element->tagName;
            } elseif($element instanceof \SimpleXMLElement) {
                return $element->getName();
            }
        }
    }
}