<?php
namespace xral\Resource\INI {
    use xral;
    use qinq;
    use qio;
    use qtil;
    
    class Query extends xral\Stream\Query {
        /**
         * Raw array data from resource
         * @var array 
         */
        private $array = [];
        
        /**
         * Default INI query filters
         */
        function assemble() {
            if(!parent::assemble()) {
                return;
            }
            
            $this->addFilter(new Filter\Parse(true));
            
            $this->addFilter(function($array) {
                return $this->array = &$array;
            });
            
            if(!$this->hasObservers(self::SAVE)) {
                $this->attach(self::SAVE,function($s,$e) {
                    $this->save($e['result']);
                });
            }
        }
        
        /**
         * Updates resource using modified references
         * @param array $updates
         * @throws \InvalidArgumentException
         */
        protected function save($updates) {
            $out = '';
            
            if(qtil\ArrayUtil::isIterable($updates)) {
                foreach($updates as $section => $values) {
                    if(is_numeric($section)) {
                        throw new \InvalidArgumentException;
                    }
                    $this->array[$section] = $updates[$section];
                }
            }
            
            if(qtil\ArrayUtil::isIterable($this->array)) {
                foreach($this->array as $section => $values) {
                    $out .= '['.$section.']'.PHP_EOL;

                    foreach($values as $key => $value) {
                        $out .= $key.'='.$value.PHP_EOL;
                    }

                    $out .= PHP_EOL;
                }
            }
            
            $stream = $this->getStream();
            
            if(!$stream->isOpen()) {
                $stream->open();
            }
            
            $writer = new qio\File\Writer($stream);
            $writer->write($out);
            
            $stream->close();
        }
        
        /**
         * Default INI query execution method
         * @return \qinq\Collection
         */
        function execute() {
            $result = parent::execute();
            
            return new qinq\Collection($result[0]);
        }
    }
}