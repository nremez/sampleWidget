<?php
/**
 * Created by PhpStorm.
 * User: nat
 * Date: 5/12/15
 * Time: 14:37
 */

namespace WidgetBundle\Entity;


interface UserRepository {

    public function findActiveUserByHash($userHash);
} 