<?php

namespace app\models;

use Yii;

use app\models\Country;

/**
 * This is the model class for table "intership".
 *
 * 
 * 
 * 
 * @property int $id
 * @property string $title
 * @property string $link_to_ru_site
 * @property string|null $annotation
 * @property string|null $description
 * @property string|null $location
 * @property string|null $address
 * @property string|null $contact
 * @property string $date_of_intership
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Image[] $images
 * @property Participant[] $participants
 */
class Intership extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'intership';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['annotation', 'description', 'price', 'date_start', 'date_end', 'contact'], 'default', 'value' => null],
            [['title', 'link_to_ru_site', 'date_start', 'date_end'], 'required'],
            [['description'], 'string'],
            [['price'], 'integer'],
            [['date_start', 'date_end', 'created_at', 'updated_at'], 'safe'],
            [['title', 'link_to_ru_site', 'annotation', 'contact'], 'string', 'max' => 255],
            [['country_id'], 'integer'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'id']],
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
            'link_to_ru_site' => 'Link to ru site',
            'annotation' => 'Annotation',
            'description' => 'Description',
            'price' => 'Price',
            'contact' => 'Contact',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'country_id' => 'Country',
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

    
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id' => 'country_id']);
    }

    public function getImages()
    {
        return $this->hasMany(Image::class, ['id' => 'image_id'])
            ->viaTable('intership_image', ['intership_id' => 'id']);
    }

    /**
     * Gets query for [[Participants]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParticipants()
    {
        return $this->hasMany(Participant::class, ['id' => 'participant_id'])
            ->viaTable('intership_participant', ['intership_id' => 'id']);
    }
}
