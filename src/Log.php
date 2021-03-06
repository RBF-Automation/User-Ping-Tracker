<?php

include_once 'DataAccessLayer/Fireball.php';
include_once 'User.php';

class Log extends Fireball\ORM {

    const TABLE_NAME  = 'Log';
    const PRIMARY_KEY = 'ID';
    const USER        = 'user';
    const IS_HOME     = 'isHome';
    const TIME        = 'time';

    private static $fields = array (
        self::PRIMARY_KEY,
        self::USER,
        self::IS_HOME,
        self::TIME,
    );

    //Override
    protected function setUp(Fireball\TableDef $def) {
        $def->setName(self::TABLE_NAME);
        $def->setKey(self::PRIMARY_KEY);
        $def->setCols(self::$fields);
    }

    public static function logAction($user, $isHome) {

        $data = array (
            self::USER    => $user,
            self::IS_HOME => $isHome,
            self::TIME => time(),
        );

        $ID = Fireball\ORM::newRecordAutoIncrement(self::TABLE_NAME, $data);

        if (is_numeric($ID)) {
            return new self($ID);
        } else {
            throw new Exception("Log entry failed");
        }

    }
    
    public static function getRawData($user) {
        $result = self::rawQuery('select * from ' . self::TABLE_NAME . ' where User = :user ORDER BY time ASC', array(":user" => $user->ID()));
        return $result;
    }

    public static function getLogRecent($lim) {
        $result = self::mapQuery(self::rawQuery('select * from ' . self::TABLE_NAME . ' ORDER BY time DESC limit :lim', array(":lim" => $lim), true));
        return $result;
    }
    
    public static function getUserLastAction($user) {
        $result = self::mapQuery(self::rawQuery('select * from ' . self::TABLE_NAME . ' where ' . self::USER . ' = :user order by time DESC limit 1', array(":user" => $user->ID()), true));
        return isset($result[0]) ? $result[0] : null;
    }

}

?>
