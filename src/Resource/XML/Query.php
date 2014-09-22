<?php
namespace xral\Resource\XML {
    use xral;
    use qtil;
    use qinq;
    
    class Query extends xral\Stream\Query {
        
        /**
         * Namespaces to be registered on query resource
         * @var array
         */
        private $namespaces = [];
        
        /**
         * Stores original document for limiting statements
         * @var mixed
         */
        private $document;

        /**
         * Retrieve query original content
         * @return mixed
         */
        function getDocument() {
            return $this->document;
        }
        
        /**
         * Update query original content
         * @param mixed $document
         * @return mixed
         */
        protected function setDocument($document) {
            return $this->document = $document;
        }
        
        /**
         * Retrieves registered namespaces
         * @return array
         */
        function getNamespaces() {
            return $this->namespaces;
        }
        
        /**
         * Add namespace for document registration
         * @param string $prefix
         * @param string $namespace
         */
        function addNamespace($prefix, $namespace) {
            $this->namespaces[$prefix] = $namespace;
        }
        
        /**
         * Default XML query assembly
         * @return boolean
         */
        protected function assemble() {
            $this['xpath'] = '';
            
            $this->attach(self::SAVE,function($s,$e) {
                $doc = $this->getDocument();
                if($doc instanceof \DOMDocument) {
                    $doc->save($this->getResource()->getPath());
                } elseif($doc instanceof \SimpleXMLElement) {
                    $doc->asXml($this->getResource()->getPath());
                }
            });
            
            return parent::assemble();
        }
        
        /**
         * Default XML query execution path
         * @return mixed
         */
        function execute() {
            $result = parent::execute();
            if(empty($result)) {
                return null;
            }
            
            $reduce = function($val) {
                if(is_scalar($val)) {
                    return $val;
                } elseif($val instanceof \DOMDocument) {
                    return $val;
                } elseif($val instanceof \SimpleXMLElement) {
                    return $val;
                }
                
                return $val;
            };
            
            if(empty($this['xpath'])) {
                return $reduce($result[0]);
            } 
            
            if(qtil\ArrayUtil::isMultiObject($result->toArray()) && count($result) == 1) {
                $val = $result[$result->keys()[0]];
                return new qinq\Collection($val);
            }
            
            return $result->filter();
        }
    }
}