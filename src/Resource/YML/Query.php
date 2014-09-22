<?php
namespace xral\Resource\YML {
    use xral;
    use qinq;
    use qio;
    
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
        
        protected function save($updates) {
            $dumper = new Filter\Emit(2, 0);
            
            foreach($updates as $key => $value) {
                $value = $updates[$key];
                if(isset($this->array[$key])) {
                    if(is_array($value)) {
                        foreach($value as $k => $v) {
                            if(is_null($v)) {
                                 unset($this->array[$key][$k]);
                            } else {
                                $this->array[$key][$k] = $v;
                            }
                        }
                    } else {
                        if(is_null($value)) {
                            unset($this->array[$key]);
                        } else {
                            $this->array[$key] = $value;
                        }
                    }
                }
            }

            $out = $dumper($this->array);
            
            $stream = $this->getStream();
            
            if(!$stream->isOpen()) {
                $stream->open();
            }
            
            $stream->truncate(0);
            $writer = new qio\File\Writer($stream);
            $writer->write($out);
            
            $stream->close();
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
