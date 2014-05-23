<?php
namespace xral\Resource\XML\Query {
    use qio;
    
    class Update extends From {
        
        /**
         * creates resource query will write to
         * @param string $mode
         */
        function execute($mode=qio\Stream\Mode::ReadWrite) {
            parent::execute($mode);
        }
    }
}