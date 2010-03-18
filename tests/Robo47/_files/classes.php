<?php

class Robo47_Paginator_Adapter_DoctrineTestRecord extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('testPagination');
        $this->hasColumn('id', 'integer', 8, array(
                    'type' => 'integer',
                    'unsigned' => 1,
                    'primary' => true,
                    'autoincrement' => true,
                    'length' => '8',
        ));
        $this->hasColumn('message', 'string', 255, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '255',
        ));
    }

    public function setUp()
    {
        parent::setUp();
    }
}

class Robo47_Log_Writer_Doctrine_Test_Log extends Doctrine_Record
{

    public function setTableDefinition()
    {
        $this->setTableName('testLog');
        $this->hasColumn('id', 'integer', 8, array(
                    'type' => 'integer',
                    'unsigned' => 1,
                    'primary' => true,
                    'autoincrement' => true,
                    'length' => '8',
        ));
        $this->hasColumn('message', 'string', 2147483647, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '2147483647',
        ));
        $this->hasColumn('category', 'string', 255, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '255',
        ));
        $this->hasColumn('timestamp', 'string', 255, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '255',
        ));
        $this->hasColumn('priority', 'string', 255, array(
                    'type' => 'integer',
                    'unsigned' => 1,
                    'length' => '8',
        ));
    }

    public function setUp()
    {
        parent::setUp();
    }
}

class Robo47_Log_Writer_Doctrine_Test_Log2 extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('testLog2');
        $this->hasColumn('id', 'integer', 8, array(
                    'type' => 'integer',
                    'unsigned' => 1,
                    'primary' => true,
                    'autoincrement' => true,
                    'length' => '8',
        ));
        $this->hasColumn('foo', 'string', 2147483647, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '2147483647',
        ));
        $this->hasColumn('baa', 'string', 255, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '255',
        ));
        $this->hasColumn('baafoo', 'string', 255, array(
                    'type' => 'string',
                    'notnull' => true,
                    'length' => '255',
        ));
        $this->hasColumn('blub', 'string', 255, array(
                    'type' => 'integer',
                    'unsigned' => 1,
                    'length' => '8',
        ));
    }

    public function setUp()
    {
        parent::setUp();
    }
}

/**
 * @property integer $tag_id
 * @property integer $blogentry_id
 * @property Tag $Tag
 * @property Blogentry $Blogentry
 */
class Blogentry2Tag extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('blogentry2_tag');
        $this->hasColumn('tag_id', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => 1,
             'notnull' => true,
             'length' => '8',
             ));
        $this->hasColumn('blogentry_id', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => 1,
             'notnull' => true,
             'length' => '8',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Tag', array(
             'local' => 'tag_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Blogentry', array(
             'local' => 'blogentry_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}

/**
 * @property integer $id
 * @property string $message
 * @property integer $categoryId
 * @property Doctrine_Collection $Tag
 * @property Doctrine_Collection $Blogentry2Tag
 */
class Blogentry extends Doctrine_Record
{
    public function setTableDefinition()
    {
        parent::setTableDefinition();
        $this->setTableName('blogentry');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             'length' => '8',
             ));
        $this->hasColumn('message', 'string', 2147483647, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '2147483647',
             ));
        $this->hasColumn('categoryId', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => 1,
             'length' => '8',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Tag', array(
             'refClass' => 'Blogentry2Tag',
             'local' => 'blogentry_id',
             'foreign' => 'tag_id'));

        $this->hasMany('Blogentry2Tag', array(
             'local' => 'id',
             'foreign' => 'blogentry_id'));

        $this->hasOne('Category', array(
             'local' => 'categoryId',
             'foreign' => 'id'));
    }
}

/**
 * @property integer $id
 * @property string $tag
 * @property string $name
 * @property Doctrine_Collection $Blogentry
 * @property Doctrine_Collection $Blogentry2Tag
 */
class Tag extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('tag');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             'length' => '8',
             ));
        $this->hasColumn('tag', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => '255',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => '255',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Blogentry', array(
             'refClass' => 'Blogentry2Tag',
             'local' => 'tag_id',
             'foreign' => 'blogentry_id'));

        $this->hasMany('Blogentry2Tag', array(
             'local' => 'id',
             'foreign' => 'tag_id'));
    }
}

/**
 * @property integer $id
 * @property string $name
 * @property Doctrine_Collection $Blogentry
 */
class Category extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('category');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             'length' => '8',
             ));
        $this->hasColumn('name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => '255',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Blogentry', array(
             'local' => 'id',
             'foreign' => 'categoryId'));
    }
}