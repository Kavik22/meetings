<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string $title
 * @property string|null $annotation
 * @property string|null $description
 * @property string|null $location
 * @property string|null $address
 * @property string|null $contact
 * @property int|null $tg_message_id
 * @property int|null $vk_post_id
 * @property string $date_of_event
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Image[] $images
 * @property Participant[] $participants
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['annotation', 'description', 'location', 'address', 'contact', 'tg_message_id', 'vk_post_id'], 'default', 'value' => null],
            [['is_media'], 'default', 'value'=> false],
            [['title', 'date_of_event'], 'required'],
            [['is_media'], 'boolean'],
            [['description'], 'string'],
            [['tg_message_id', 'vk_post_id'], 'integer'],
            [['date_of_event', 'created_at', 'updated_at'], 'safe'],
            [['title', 'annotation', 'location', 'address', 'contact'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'annotation' => 'Annotation',
            'description' => 'Description',
            'location' => 'Location',
            'address' => 'Address',
            'contact' => 'Contact',
            'is_media'=> 'Is media',
            'tg_message_id' => 'Tg message id',
            'vk_post_id' => 'VK post id',
            'date_of_event' => 'Date Of Event',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
        return parent::beforeSave($insert);
    }

    public function getImages()
    {
        return $this->hasMany(Image::class, ['id' => 'image_id'])
            ->viaTable('event_image', ['event_id' => 'id']);
    }

    /**
     * Gets query for [[Participants]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParticipants()
    {
        return $this->hasMany(Participant::class, ['id' => 'participant_id'])
            ->viaTable('event_participant', ['event_id' => 'id']);
    }
}
