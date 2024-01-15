<?php

    foreach ( HELPER_STACK_GLOBAL as $needle ) {
        \system\Services\Helper::addSystemHelper($needle);
    }



