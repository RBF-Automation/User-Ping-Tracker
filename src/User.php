<?php

include_once 'DataAccessLayer/Fireball.php';

class User extends Fireball\ORM {

    const TABLE_NAME  = 'Users';
    const PRIMARY_KEY = 'ID';
    const IP          = 'ip';

    private static $fields = array (
        self::PRIMARY_KEY,
        self::IP,
    );

    //Override
    protected function setUp(Fireball\TableDef $def) {
        $def->setName(self::TABLE_NAME);
        $def->setKey(self::PRIMARY_KEY);
        $def->setCols(self::$fields);
    }

    public static function createNew($ip) {
        $data = array (
            self::IP    => $ip,
        );

        $ID = Fireball\ORM::newRecordAutoIncrement(self::TABLE_NAME, $data);

        if (is_numeric($ID)) {
            return new self($ID);
        } else {
            throw new Exception("user creation failed");
        }

    }
    
    public static function fromIp($ip) {
        $result = Fireball\ORM::dbSelect(self::PRIMARY_KEY, self::TABLE_NAME, self::IP, $ip);
        return new self($result);
    }

    public static function getUsers() {
        $result = self::mapQuery(self::rawQuery('select * from ' . self::TABLE_NAME, null, true));
        return $result;
    }

}

?>
