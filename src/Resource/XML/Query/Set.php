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
                
                $query->attach(xral\Stream\Query::COMPLETE,function($query,$e)use($name, $value) {
                    $result = $e['result']->flatten();
                    
                    $nameFn = function($element) {
                        if($element instanceof \DOMElement) {
                            return $element->tagName;
                        } elseif($element instanceof \SimpleXMLElement) {
                            return $element->getName();
                        }
                    };

                    $nodeSetter = function(&$node,$recFn,$aggregateName)use($name,$value,$nameFn) {
                        $nodeName = $nameFn($node);
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
                                $recFn($child,$recFn,$aggregateName.'/'.$nameFn($child));
                            }
                        }
                    };

                    foreach($result as $r) {
                        if($r instanceof \DOMNodeList) {
                            foreach($r as $d) {
                                $nodeSetter($d,$nodeSetter,$d->tagName);
                            }
                        } else {
                            $nodeSetter($r,$nodeSetter,$r->getName());
                        }
                    }
                });
            }
        }
    }
}