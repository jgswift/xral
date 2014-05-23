<?php
namespace xral\Resource\XML\Query {
    use xral;
    use qio;
    
    class From extends xral\Resource\XML\Statement {
        /**
         * creates resource query will read from
         * @param string $mode
         * @throws xral\Exception
         */
        function execute($mode=qio\Stream\Mode::Read) {
            $query = $this->getQuery();
            
            $args = $this->getArguments();
            
            if(isset($args[0])) {
                if(is_string($args[0])) {
                    $resource = new qio\File($args[0]);
                } elseif($args[0] instanceof qio\Resource) {
                    $resource = $args[0];
                }
            }
            
            if($resource instanceof qio\Resource) {
                $query->setResource($resource,$mode);
                
                $reader = new \qio\File\Reader($query->getStream());
                
                $query->setIterator(new \qio\File\Reader\Iterator($reader));
            } else {
                throw new xral\Exception('Cannot locate XML query source path = ('.print_r($args,true).')');
            }
        }
    }
}