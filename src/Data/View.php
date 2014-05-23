<?php
namespace xral\Data {
    use xral;
    use qtil;
    use observr;
    use xral\Interfaces;
    
    abstract class View implements xral\Interfaces\View {
        use qtil\ArrayAccess, observr\Subject;
        
        const PREPARE = 0;
        
        /**
         * Stores view parameters
         * @var array
         */
        public $data = [];
        
        /**
         * Stores whether view is prepared
         * @var boolean
         */
        private $prepared = false;
        
        /**
         * Stores view adapter
         * @var xral\Data\Adapter
         */
        protected $adapter;
        
        /**
         * Stores view query
         * @var xral\Query
         */
        protected $query;
        
        /**
         * Default view constructor
         * @param \xral\Data\Adapter $adapter
         */
        public function __construct(Adapter $adapter = null) {
            $this->adapter = $adapter;
        }
        
        /**
         * Retrieve view adapter
         * @return xral\Data\Adapter
         */
        public function getAdapter() {
            return $this->adapter;
        }
        
        /**
         * Update view adapter
         * @param xral\Data\Adapter $adapter
         * @return xral\Data\Adapter
         */
        public function setAdapter($adapter) {
            if(!($adapter instanceof xral\Data\Adapter)) {
                throw new \InvalidArgumentException();
            }
            
            return $this->adapter = $adapter;
        }
        
        /**
         * Retrieve view query
         * @return xral\Interfaces\Query
         */
        public function getQuery() {
            return $this->query;
        }
        
        /**
         * Update view query
         * @param Interfaces\Query $query
         * @return Interfaces\Query
         */
        public function setQuery(Interfaces\Query $query) {
            return $this->query = $query;
        }
        
        /**
         * Checks if view has query
         * @return boolean
         */
        public function hasQuery() {
            return (isset($this->query));
        }
        
        /**
         * Updates prepared status to true
         * @return boolean
         */
        public function prepare() {
            return $this->prepared = true;
        }
        
        /**
         * Checks if view is prepared
         * @return boolean
         */
        public function isPrepared() {
            return $this->prepared;
        }
        
        /**
         * Resets view
         */
        public function unprepare() {
            unset($this->query);
            $this->prepared = false;
        }
    }
}