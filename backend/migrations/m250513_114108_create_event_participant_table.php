<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event_participant}}`.
 */
class m250513_114108_create_event_participant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event_participant}}', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer(),
            'participant_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        
        $this->addForeignKey(   
            'fk-event_participant-event_id',
            'event_participant',
            'event_id',
            'event',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-event_participant-participant_id',
            'event_participant',
            'participant_id',
            'participant',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-event_participant-event_id', 'event_participant');
        $this->dropForeignKey('fk-event_participant-participant_id', 'event_participant');       
        $this->dropTable('event_participant');
    }
}
