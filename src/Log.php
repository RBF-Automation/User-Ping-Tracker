<?php

include_once 'DataAccessLayer/Fireball.php';

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

    public static function getLogRecent($lim) {
        $result = self::mapQuery(self::rawQuery('select * from ' . self::TABLE_NAME . ' ORDER BY time DESC limit :lim', array(":lim" => $lim), true));
        return $result;
    }

}

?>
