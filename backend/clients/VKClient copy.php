<?php

namespace app\clients;

use Yii;
use yii\helpers\Url;
use app\clients\CURLClient;

class VKClient extends CURLClient
{
    public $access_token;
    public $user_access_token;
    public $group_id;


    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->access_token = Yii::$app->params['VKAccessToken'] ?? '';
        $this->user_access_token = Yii::$app->params['VKUserAccessToken'] ?? '';
        $this->group_id = Yii::$app->params['VKGroupId'] ?? '';
    }

    public function publishPost(?string $message, ?string $photo_path, string $api_version = '5.199'): ?array
    {
        if (!isset($photo_path)) {
            return $this->postToWallWithOutPhoto($message, $api_version);
        } else {
            return $this->postToWallWithPhoto($message, $photo_path, $api_version);
        }
    }

    
    public function postToWallWithOutPhoto($message, $api_version)
    {
        $post_response = $this->postToWall($this->group_id, $this->access_token, $message, null, null, $api_version);
        if ($post_response['success']) {
            Yii::$app->session->setFlash('vk_success', 'Пост успешно опубликован в ВК!');
            return $post_response['data'];
        } else {
            Yii::$app->session->setFlash('vk_error', 'Ошибка при публикации поста в ВК');
            return null;
        }
    }

    public function postToWallWithPhoto($message, $photo_path, $api_version)
    {
        // 1. Получение URL для загрузки
        $upload_url = $this->getWallUploadServer($this->group_id, $this->user_access_token, $api_version);
        if ($upload_url) {
            // 2. Загрузка фотографии
            $upload_response = $this->uploadPhoto($upload_url, $photo_path);
            if ($upload_response) {
                // 3. Сохранение фотографии
                $save_response = $this->saveWallPhoto($this->group_id, $this->user_access_token, $upload_response['photo'], $upload_response['server'], $upload_response['hash'], $api_version);
                if ($save_response) {
                    // 4. Публикация записи
                    $post_response = $this->postToWall($this->group_id, $this->access_token, $message, $save_response['owner_id'], $save_response['id'], $api_version);
                    if ($post_response['success']) {
                        Yii::$app->session->setFlash('vk_create_success', 'Пост успешно опубликован в ВК!');
                        return $post_response['data'];
                    } else {
                        Yii::$app->session->setFlash('vk_create_error', 'Ошибка при отправке публикации поста в ВК');
                        return null;
                    }
                } else {
                    Yii::$app->session->setFlash('vk_create_error', "Ошибка при сохранении изображения в ВК");
                    return null;
                }
            } else {
                Yii::$app->session->setFlash('vk_create_error', "Ошибка при загрузке изображения в ВК");
                return null;
            }
        } else {
            Yii::$app->session->setFlash('vk_create_error', "Ошибка при получении URL для загрузки изображения в ВК");
            return null;
        }
    }

    public function getWallUploadServer($group_id, $access_token, $api_version)
    {
        $data = [
            'group_id' => $group_id,
            'access_token' => $access_token,
            'v' => $api_version
        ];
        $url = 'https://api.vk.com/method/photos.getWallUploadServer';
        $response = $this->doRequest($url, $data);

        if (isset($response['data']['response']['upload_url'])) {
            return $response['data']['response']['upload_url'];
        } else {
            return null;
        }
    }

    public function uploadPhoto(string $upload_url, string $photo_path): ?array
    {
        $data = ['photo' => new \CURLFile($photo_path)];
        $response = $this->doRequest($upload_url, $data);

        if (isset($response['data']['hash']) && isset($response['data']['photo']) && isset($response['data']['server'])) {
            return $response['data'];
        } else {
            return null;
        }
    }

    public function saveWallPhoto(string $group_id, string $access_token, string $photo_string, string $photo_server, string $photo_hash, string $api_version): ?array
    {
        $post_data = [
            'group_id' => $group_id,
            'photo' => $photo_string,
            'server' => $photo_server,
            'hash' => $photo_hash,
            'access_token' => $access_token,
            'v' => $api_version
        ];

        $response = $this->doRequest('https://api.vk.com/method/photos.saveWallPhoto', $post_data);


        if (isset($response['data']['response'][0]['id']) && isset($response['data']['response'][0]['owner_id'])) {
            return [
                'id' => $response['data']['response'][0]['id'],
                'owner_id' => $response['data']['response'][0]['owner_id']
            ];
        } else {
            return null;
        }
    }


    public function postToWall(string $group_id, string $access_token, ?string $message, ?string $photo_owner_id, ?string $photo_id, string $api_version): ?array
    {
        $attachments = 'photo' . $photo_owner_id . '_' . $photo_id;
        $post_data = [
            'owner_id' => '-' . $group_id,  // Важно: group_id должен быть отрицательным
            'from_group' => 1,
            'message' => $message,
            'attachments' => $attachments,
            'access_token' => $access_token,
            'v' => $api_version
        ];

        $url = 'https://api.vk.com/method/wall.post';
        $response = $this->doRequest($url, $post_data);

        return $response;
    }

    public function updatePost($post_id, $new_text_message, $new_photo = null) {
        if (isset($new_photo)){
            $this->updatePostWithImage($post_id, $new_text_message, $new_photo);
        } else {
            $this->updatePostWithoutImage($post_id, $new_text_message);
        }   
    }

    public function updatePostWithoutImage($post_id, $new_text_message) {
        $data = [
            'message' => $new_text_message,
            'owner_id' => '-' . $this->group_id,
            'post_id' => $post_id,
            'access_token' => $this->user_access_token,
            'v' => '5.199',
        ];
        $url = 'https://api.vk.com/method/wall.edit';

        $response = $this->doRequest($url, $data);

        if ($response['success'] && isset($response['data']['response'])) {
            Yii::$app->session->setFlash('vk_update_success', 'Сообщение успешно обновлено в группе в ВК!');
        } else {
            Yii::$app->session->setFlash('tg_update_error', 'Ошибка при обновлении поста из группы в ВК');
        }

    }

    public function updatePostWithImage($post_id, $new_text_message, $new_photo) {}


    public function deletePost(int $post_id){
        $data = [
            'owner_id' => '-' . $this->group_id,
            'post_id' => $post_id,
            'access_token' => $this->user_access_token,
            'v' => '5.199',
        ];
        $response = $this->doRequest('https://api.vk.com/method/wall.delete', $data);
        
        if ($response['success']) {
            Yii::$app->session->setFlash('vk_delete_success', 'Сообщение успешно удалено из группы в ВК!');
        } else {
            Yii::$app->session->setFlash('tg_delete_error', 'Ошибка при удалении поста из группы в ВК: ' . $response['description']);
        }
    }
}

