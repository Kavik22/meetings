<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m250510_110302_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'annotation' => $this->string(255),
            'description' => $this->text(),
            'location' => $this->string(255),
            'address' => $this->string(255),
            'contact' => $this->string(255),
            'is_media' => $this->boolean()->defaultValue(false),
            'tg_message_id' => $this->integer()->unsigned(),
            'vk_post_id' => $this->integer()->unsigned(),
            'date_of_event' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event}}');
    }
}
