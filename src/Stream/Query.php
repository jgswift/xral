<?php
namespace xral\Stream {
    use xral;
    use qio;
    use qinq;
    use kfiltr;
    use observr;
    
    abstract class Query extends xral\Query {
        use kfiltr\Filter {
            kfiltr\Filter::__invoke as invoke;
        }
        
        const COMPLETE = 'complete';
        const ASSEMBLE = 'assemble';
        const SAVE = 'save';
        
        /**
         * Stores stream that acts on query resource
         * @var qio\Resource\Stream
         */
        protected $stream;
        
        /**
         * Stores iterator that query uses to iterate over query results
         * @var \Iterator 
         */
        protected $iterator;
        
        /**
         * Stores whether or not this query is already assembled
         * @var boolean
         */
        protected $assembled = false;

        /**
         * Retrieves query stream
         * @return qio\Resource\Stream
         */
        public function getStream() {
            return $this->stream;
        }
        
        /**
         * Alias of Stream isOpen
         * @return boolean
         */
        public function isOpen() {
            if(!isset($this->stream)) {
                return false;
            }
            
            return $this->stream->isOpen();
        }
        
        /**
         * Update query stream
         * @param qio\Resource\Stream $stream
         * @return \qio\Resource\Stream
         */
        public function setStream(qio\Resource\Stream $stream) {
            $this->stream = $stream;
            $this->setResource($stream->getResource());
            return $stream;
        }
        
        /**
         * Update query resource
         * @param qio\Resource $resource
         * @param integer $mode
         */
        public function setResource(qio\Resource $resource, $mode = qio\Stream\Mode::ReadWrite) {
            if(empty($this->stream)) {
                if($resource instanceof qio\File) {
                    $this->stream = new qio\File\Stream($resource,$mode);
                } elseif($resource instanceof qio\Directory) {
                    $this->stream = new qio\Directory\Stream($resource);
                }
            }
            parent::setResource($resource);
        }
        
        /**
         * Retrieves query iterator
         * @return \Iterator
         */
        public function getIterator() {
            return $this->iterator;
        }
        
        /**
         * Update query iterator
         * @param \Iterator $iterator
         * @return \Iterator
         */
        public function setIterator(\Iterator $iterator) {
            return $this->iterator = $iterator;
        }
        
        /**
         * Default stream assembly method
         * @return boolean
         */
        protected function assemble() {
            if($this->assembled) {
                return false;
            }
            
            $links = $this->getLinks();
            
            $args = func_get_args();
            foreach($links as $link) {
                if(is_callable($link)) {
                    $this->process($link,$args);
                }
            }
            
            $this->setState(self::ASSEMBLE, $e = new observr\Event($this));
            
            if(!$e->canceled) {
                return $this->assembled = true;
            }
            
            return $this->assembled = false;
        }
        
        /**
         * Handles query statement execution results
         * @param callable|xral\Interfaces\Statement $link
         * @param array $args
         */
        protected function process($link,array $args = []) {
            if($link instanceof xral\Interfaces\Statement) {
                $link->setQuery($this);
            }
            
            $result = call_user_func_array($link,$args);
            if(is_callable($result)) {
                $this->addFilter($result);
            }
        }
        
        /**
         * Executes query
         * Assembles query if necessary
         * Opens stream if not already open
         * Closes stream after result iteration/translation
         * @return \qinq\Collection
         */
        public function execute() {
            $this->assemble();
            
            $result = [];
            $it = $this->getIterator();
            if($it instanceof \Iterator) {
                if(!$this->stream->isOpen()) {
                    $this->stream->open();
                }

                foreach($it as $key => $value) {
                    $result[$key] = $this->translate($value);
                }
                
                $this->stream->close();
                $this->assembled = false;
                $this->setFilters([]);
            }
            
            return new qinq\Collection($result);
        }
        
        /**
         * Handles query invocation
         * @return mixed
         */
        public function __invoke() {
            $result = call_user_func_array([$this,'invoke'], func_get_args());
            
            $e = new observr\Event($this,['result' => &$result]);
            
            if(!isset($this->stream)) {
                throw new xral\Exception('No resource specified');
            }
            
            if($this->stream->isWrite()) {
                $this->attach(self::COMPLETE,function()use($result,$e) {
                    if($this->hasObservers(self::SAVE)) {
                        $this->setState(self::SAVE, new observr\Event($this,['result' => $e['result']]));
                        $this->clearState(self::SAVE);
                    }
                });
            }
                    
            $this->setState(self::COMPLETE, $e);
            
            if(!$e->canceled) {
                $result = $e['result'];
            }
            
            if($this->stream->isOpen()) {
                $this->stream->close();
            }
            
            return $result;
        }
    }
}