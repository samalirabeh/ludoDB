<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                'accessortest' => '/Tests/AccessorTest.php',
                'car' => '/Tests/classes/Car.php',
                'carcollection' => '/Tests/classes/CarCollection.php',
                'carproperties' => '/Tests/classes/CarProperties.php',
                'carproperty' => '/Tests/classes/CarProperty.php',
                'city' => '/Tests/classes/City.php',
                'client' => '/Tests/classes/Client.php',
                'collectiontest' => '/Tests/CollectionTest.php',
                'configparsertest' => '/Tests/ConfigParserTest.php',
                'configparsertestjson' => '/Tests/ConfigParserTestJSON.php',
                'country' => '/Tests/classes/Country.php',
                'dbtest' => '/Tests/DBTest.php',
                'findertest' => '/Tests/FinderTest.php',
                'forsqltest' => '/Tests/classes/ForSQLTest.php',
                'jsontest' => '/Tests/JSONTest.php',
                'ludodb' => '/LudoDB.php',
                'ludodbcollection' => '/LudoDBCollection.php',
                'ludodbconfigparser' => '/LudoDbConfigParser.php',
                'ludodbiterator' => '/LudoDBIterator.php',
                'ludodbobject' => '/LudoDBObject.php',
                'ludodbregistry' => '/LudoDBRegistry.php',
                'ludodbtable' => '/LudoDBTable.php',
                'ludofinder' => '/LudoFinder.php',
                'ludosql' => '/LudoSQL.php',
                'manager' => '/Tests/classes/Manager.php',
                'person' => '/Tests/classes/Person.php',
                'personforconfigparser' => '/Tests/classes/PersonForConfigParser.php',
                'phone' => '/Tests/classes/Phone.php',
                'phonecollection' => '/Tests/classes/PhoneCollection.php',
                'sqltest' => '/Tests/SQLTest.php',
                'testbase' => '/Tests/TestBase.php',
                'testgame' => '/Tests/classes/TestGame.php',
                'testtable' => '/Tests/classes/TestTable.php'
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require __DIR__ . $classes[$cn];
        }
    }
);
// @codeCoverageIgnoreEnd