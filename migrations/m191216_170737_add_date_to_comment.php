<?php

use yii\db\Migration;

/**
 * Class m191216_170737_add_date_to_comment
 */
class m191216_170737_add_date_to_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
   // public function safeUp()
   // {

   // }

    /**
     * {@inheritdoc}
     */
    //public function safeDown()
    //{
    //    echo "m191216_170737_add_date_to_comment cannot be reverted.\n";

    //    return false;
   //}


    public function up()
    {
    $this->addColumn('comment','date',$this->date());
    }

    public function down()
    {
        $this->dropColumn('column','date');
    }

}
