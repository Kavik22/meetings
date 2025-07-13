<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%intership_paricipant}}`.
 */
class m250516_001225_create_intership_paricipant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%intership_participant}}', [
            'id' => $this->primaryKey(),
            'intership_id' => $this->integer(),
            'participant_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        
        $this->addForeignKey(   
            'fk-intership_participant-intership_id',
            'intership_participant',
            'intership_id',
            'intership',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-intership_participant-participant_id',
            'intership_participant',
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
        $this->dropForeignKey('fk-intership_participant-intership_id', 'intership_participant');
        $this->dropForeignKey('fk-intership_participant-participant_id', 'intership_participant');       
        $this->dropTable('intership_participant');
    }
}
