<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_image}}`.
 */
class m250510_120737_create_event_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event_image}}', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer(),
            'image_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        
        $this->addForeignKey(
            'fk-event_image-event_id',
            'event_image',
            'event_id',
            'event',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-event_image-image_id',
            'event_image',
            'image_id',
            'image',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-event_image-event_id', 'event_image');
        $this->dropForeignKey('fk-event_image-image_id', 'event_image');
        $this->dropTable('event_image');
    }
}
