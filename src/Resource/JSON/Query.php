<?php
namespace xral\Resource\JSON {
    use xral;
    use qinq;
    use qio;
    
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
         * Updates resource using modified references
         * @param array $updates
         * @throws \InvalidArgumentException
         */
        protected function save($updates) {
            if($updates instanceof \qtil\Interfaces\Collection) {
                $updates = $updates->toArray();
                foreach($updates as $key => $values) {
                    if(array_key_exists($key, $this->array[0])) {
                        $this->array[0][$key] = $values;
                    }
                }
            }
            
            $encoder = new Filter\Encoder();
            $out = $encoder($this->array[0]);
            
            $resource = $this->getResource();
            $stream = new qio\File\Stream($resource,qio\Stream\Mode::ReadWriteTruncate);
            
            if(!$stream->isOpen()) {
                $stream->open();
            }
            
            $writer = new qio\File\Writer($stream);
            $writer->write($out);
            
            $stream->close();
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
            
            $this->array = $collection->toArray();
            
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
