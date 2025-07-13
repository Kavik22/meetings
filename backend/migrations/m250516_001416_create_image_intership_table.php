<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image_intership}}`.
 */
class m250516_001416_create_image_intership_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%intership_image}}', [
            'id' => $this->primaryKey(),
            'intership_id' => $this->integer(),
            'image_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        
        $this->addForeignKey(
            'fk-intership_image-intership_id',
            'intership_image',
            'intership_id',
            'intership',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-intership_image-image_id',
            'intership_image',
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
        $this->dropForeignKey('fk-intership_image-intership_id', 'intership_image');
        $this->dropForeignKey('fk-intership_image-image_id', 'intership_image');
        $this->dropTable('intership_image');
    }
}
