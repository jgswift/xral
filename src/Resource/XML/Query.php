<?php
namespace xral\Resource\XML {
    use xral;
    
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
            return parent::assemble();
        }
        
        /**
         * Default XML query execution path
         * @return mixed
         */
        function execute() {
            $result = parent::execute();
            
            return $result->flatten();
        }
    }
}