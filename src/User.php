<?php

include_once 'DataAccessLayer/Fireball.php';

class User extends Fireball\ORM {

    const TABLE_NAME  = 'Users';
    const PRIMARY_KEY = 'ID';
    const REMOTE_ID   = 'remoteId';
    const IP          = 'ip';
    const ACTIVE      = 'active';

    private static $fields = array (
        self::PRIMARY_KEY,
        self::REMOTE_ID,
        self::IP,
        self::ACTIVE,
    );

    //Override
    protected function setUp(Fireball\TableDef $def) {
        $def->setName(self::TABLE_NAME);
        $def->setKey(self::PRIMARY_KEY);
        $def->setCols(self::$fields);
    }

    public static function createNew($ip, $remoteId) {
        $data = array (
            self::REMOTE_ID => $remoteId,
            self::IP        => $ip,
            self::ACTIVE    => 1,
        );

        $ID = Fireball\ORM::newRecordAutoIncrement(self::TABLE_NAME, $data);

        return self::fromId($ID);

    }
    
    private static function fromId($ID) {
        if (is_numeric($ID)) {
            return new self($ID);
        } else {
            throw new Exception("user creation failed");
        }
    }
    
    public static function fromIp($ip) {
        $result = Fireball\ORM::dbSelect(self::PRIMARY_KEY, self::TABLE_NAME, self::IP, $ip);
        return self::fromId($result);
    }

    public static function getUsers() {
        $result = self::mapQuery(self::rawQuery('select * from ' . self::TABLE_NAME . ' where active = 1', null, true));
        return $result;
    }

}

?>
