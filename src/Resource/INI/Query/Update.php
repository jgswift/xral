<?php
namespace xral\Resource\INI\Query {
    use qio;
    
    class Update extends From {
        
        /**
         * Update statement
         * @param string $mode
         */
        function execute($mode=qio\Stream\Mode::ReadWrite) {
            parent::execute($mode);
        }
    }
}