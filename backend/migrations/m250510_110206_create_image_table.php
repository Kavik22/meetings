<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image}}`.
 */
class m250510_110206_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'original_name' => $this->string(255),
            'filename' => $this->string(255),
            'path' => $this->string(255)->notNull(),
            'title' => $this->string(255),
            'description' => $this->text(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%image}}');
    }
}
