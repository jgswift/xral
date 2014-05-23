<?php
namespace xral\Resource\YML {
    use xral;
    use qinq;
    use qio;
    use qtil;
    
    class Query extends xral\Stream\Query {
        /**
         * Stores original array result
         * @var array
         */
        public $array = [];
        
        /**
         * Default YML query assembly
         */
        function assemble() {
            $this['ypath'] = '';
            parent::assemble();
            
            $this->addFilter(new Filter\Parse(true));
            
            $this->addFilter(function($array) {
                return $this->array = &$array;
            });
            
            if(isset($this['ypath']) &&
               !empty($this['ypath'])) {
                $this->addFilter(new Filter\Ypath($this));
            }
            
            if(!$this->hasObservers(self::SAVE)) {
                $this->attach(self::SAVE,function($s,$e) {
                    $this->save($e['result']);
                });
            }
        }
        
        /**
         * Default YML query execution path
         * @return \qinq\Collection
         */
        function execute() {
            $result = parent::execute();
            
            return new qinq\Collection($result[0]);
        }
    }
}
