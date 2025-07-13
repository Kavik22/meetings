<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%participanat}}`.
 */
class m250513_113949_create_participant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%participant}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'email' => $this->string(255)->unique(),
            'phone' => $this->string(255)->unique(),
            'tag' => $this->string(255),
            'found_out_about_us' => $this->string(255),
            'direction_of_study' => $this->string(255),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%participant}}');
    }
}
