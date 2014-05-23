<?php
namespace xral\Interfaces {
    
    interface View extends \ArrayAccess, Statement {
        function getAdapter();
        function setAdapter($adapter);
        
        function hasQuery();
        
        function prepare();
        function isPrepared();
        function unprepare();
    }
}