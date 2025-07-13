<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%intership}}`.
 */
class m250510_110347_create_intership_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%intership}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'link_to_ru_site' => $this->string(255)->notNull(),
            'annotation' => $this->string(255),
            'description' => $this->text(),
            'price' => $this->integer()->unsigned(),
            'contact' => $this->string(255),
            'date_start' => $this->date(),
            'date_end' => $this->date(),
            'country_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-intership-country',
            'intership',
            'country_id',
            'country',
            'id',
            'CASCADE',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-intership-country', 'intership');
        $this->dropTable('{{%intership}}');
    }
}
