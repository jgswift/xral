<?php
namespace xral\Resource\JSON {
    use xral;
    use qinq;
    
    class Query extends xral\Stream\Query {
        /**
         * Stores original array result
         * @var array
         */
        public $array = [];
        private $query;
        
        function __construct(\qio\Resource $resource = null) {
            parent::__construct($resource);
            $this->query = new qinq\Object\Query;
        }
        
        /**
         * Default JSON query assembly
         */
        function assemble() {
            parent::assemble();
            
            $this->addFilter(new Filter\Decoder());
                        
            if(!$this->hasObservers(self::SAVE)) {
                $this->attach(self::SAVE,function($s,$e) {
                    $this->save($e['result']);
                });
            }
        }
        
        /**
         * Default JSON query execution path
         * @return \qinq\Collection
         */
        function execute() {
            $collection = parent::execute();
            
            $this->query->setCollection(new qinq\Collection($collection[0]));
            
            $result = $this->query->execute();

            return $result;
        }
        
        /**
         * Tries home chain and fail-over to object query
         * @param string $name
         * @param array $arguments
         * @return \xral\Resource\JSON\Query
         */
        function __call($name, array $arguments) {
            try {
                parent::__call($name, $arguments);
            } catch (\BadMethodCallException $e) {
                call_user_func_array([$this->query,$name],$arguments);
            }
            
            return $this;
        }
    }
}
