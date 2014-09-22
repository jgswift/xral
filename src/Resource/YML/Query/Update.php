<?php
namespace xral\Resource\YML\Query {
    use xral;
    use qio;
    
    class Update extends From {
        
        /**
         * Update statement
         * @param string $mode
         * @throws xral\Exception
         */
        function execute($mode=qio\Stream\Mode::ReadWrite) {
            parent::execute($mode);
        }
    }
}