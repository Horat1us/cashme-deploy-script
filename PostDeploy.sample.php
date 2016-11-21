<?php
/**
 * You will be need to create your own PostDeploy.php file, if you need it, of course
 */

/**
 * @param array $deploy with first element - instance of Deploy
 * @see Deploy
 * @return bool
 */
return function (array $deploy) {
    if(!$deploy[0] instanceof Deploy) {
        throw new UnexpectedValueException("PostDeploy must receive only Deploy instances");
    }

    /** @var Deploy $deploy */
    $deploy = $deploy[0];

    $deploy->log("Loaded default PostDeploy");

    return true;
};