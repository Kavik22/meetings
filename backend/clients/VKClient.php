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
    public $api_version;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->access_token = Yii::$app->params['VKAccessToken'] ?? '';
        $this->user_access_token = Yii::$app->params['VKUserAccessToken'] ?? '';
        $this->group_id = Yii::$app->params['VKGroupId'] ?? '';
        $this->api_version = '5.199';
    }

    public function publishPost(?string $message, ?string $photo_path): ?array
    {
        if (!isset($photo_path)) {
            return $this->postToWallWithOutPhoto($message);
        } else {
            return $this->postToWallWithPhoto($message, $photo_path);
        }
    }

    
    public function postToWallWithOutPhoto($message)
    {
        return $this->postToWall($message, null, null);

        dd($post_response);
        if (isset($post_response['success']) && $post_response['success']) {
            dd('yes');
            Yii::$app->session->setFlash('vk_success', 'Пост успешно опубликован в ВК!');
            return $post_response['data'];
        } else {
            dd('afsdaf');
            Yii::$app->session->setFlash('vk_error', 'Ошибка при публикации поста в ВК');
            return null;
        }
    }

    public function postToWallWithPhoto($message, $photo_path)
    {
        $response = $this->getWallUploadServer($photo_path);
        dd($response);
        if ($response){
            return $this->postToWall($message, $response['owner_id'], $response['id']);
        }
        return null;
    }

    public function postToWall(?string $message, ?string $photo_owner_id, ?string $photo_id): ?array
    {
        $attachments = 'photo' . $photo_owner_id . '_' . $photo_id;
        $post_data = [
            'owner_id' => '-' . $this->group_id,  // Важно: group_id должен быть отрицательным
            'from_group' => 1,
            'message' => $message,
            'attachments' => $attachments,
            'access_token' => $this->user_access_token,
            'v' => $this->api_version,
        ];

        $url = 'https://api.vk.com/method/wall.post';
        $response = $this->doRequest($url, $post_data);

        if ($response['success']) {
            Yii::$app->session->setFlash('vk_create_success', 'Пост успешно опубликован в ВК!');
            return $response['data'];
        } else {
            Yii::$app->session->setFlash('vk_create_error', "Ошибка при публикации поста в ВК");
            return null;
        }
    }

    public function getWallUploadServer($photo_path)
    {
        $data = [
            'group_id' => $this->group_id,
            'access_token' => $this->user_access_token,
            'v' => $this->api_version,
        ];
        $url = 'https://api.vk.com/method/photos.getWallUploadServer';

        $response = $this->doRequest($url, $data);

        if (isset($response['data']['response']['upload_url'])) {
            // return $response['data']['response']['upload_url'];
            $upload_url = $response['data']['response']['upload_url'];
            return $this->uploadPhoto($upload_url, $photo_path);
        } else {
            Yii::$app->session->setFlash('vk_create_error', "Ошибка при получении URL для загрузки изображения в ВК");
            return null;
        }
    }

    public function uploadPhoto(string $upload_url, string $photo_path): ?array
    {
        $data = ['photo' => new \CURLFile($photo_path)];
        $response = $this->doRequest($upload_url, $data);

        if (isset($response['data']['hash']) && isset($response['data']['photo']) && isset($response['data']['server'])) {
            // return $response['data'];
            return $this->saveWallPhoto($response['data']['photo'], $response['data']['server'], $response['data']['hash']);
        } else {
            Yii::$app->session->setFlash('vk_create_error', "Ошибка при загрузке изображения в ВК");
            return null;
        }
    }

    public function saveWallPhoto(string $photo_string, string $photo_server, string $photo_hash): ?array
    {
        $post_data = [
            'group_id' => $this->group_id,
            'photo' => $photo_string,
            'server' => $photo_server,
            'hash' => $photo_hash,
            'access_token' => $this->user_access_token,
            'v' => $this->api_version,
        ];

        $response = $this->doRequest('https://api.vk.com/method/photos.saveWallPhoto', $post_data);

        if ($response['success'] && isset($response['data']['response'][0]['id']) && isset($response['data']['response'][0]['owner_id'])) {
            return [
                'id' => $response['data']['response'][0]['id'],
                'owner_id' => $response['data']['response'][0]['owner_id']
            ];
        } else {
            Yii::$app->session->setFlash('vk_create_error', "Ошибка при сохранении изображения в ВК");
            return null;
        }
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
            'v' => $this->api_version,
        ];
        $url = 'https://api.vk.com/method/wall.edit';

        $response = $this->doRequest($url, $data);

        if ($response['success'] && isset($response['data']['response'])) {
            Yii::$app->session->setFlash('vk_update_success', 'Сообщение успешно обновлено в группе в ВК!');
        } else {
            Yii::$app->session->setFlash('tg_update_error', 'Ошибка при обновлении поста из группы в ВК');
        }

    }

    public function updatePostWithImage($post_id, $new_text_message, $new_photo) {
        $safe_image_response = $this->getWallUploadServer($new_photo);
        
        $data = [
            'owner_id' => '-' . $this->group_id,
            'post_id'  => $post_id,
            'message'  => $new_text_message,
            'access_token' => $this->user_access_token,
            'attachments' => 'photo' . $safe_image_response['owner_id'] . '_' . $safe_image_response['id'],
            'v' => $this->api_version,
        ];
        $url = 'https://api.vk.com/method/wall.edit';

        $response = $this->doRequest($url, $data);
        
        if ($response['success'] && isset($response['data']['response'])) {
            Yii::$app->session->setFlash('vk_update_success', 'Сообщение успешно обновлено в группе в ВК!');
        } else {
            Yii::$app->session->setFlash('tg_update_error', 'Ошибка при обновлении поста из группы в ВК');
        }
    }


    public function deletePost(int $post_id){
        $data = [
            'owner_id' => '-' . $this->group_id,
            'post_id' => $post_id,
            'access_token' => $this->user_access_token,
            'v' => $this->api_version,
        ];
        $response = $this->doRequest('https://api.vk.com/method/wall.delete', $data);
        
        if ($response['success']) {
            Yii::$app->session->setFlash('vk_delete_success', 'Сообщение успешно удалено из группы в ВК!');
        } else {
            Yii::$app->session->setFlash('tg_delete_error', 'Ошибка при удалении поста из группы в ВК: ' . $response['description']);
        }
    }
}

