<?php

namespace app\clients;

use Yii;
use yii\helpers\Url;
use app\clients\CURLClient;

class TelegramClient extends CURLClient
{
    public $telegram_token;
    public $telegram_chat_id;
    public $telegram_chat_tag;


    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->telegram_token = Yii::$app->params['telegramToken'] ?? '';
        $this->telegram_chat_id = Yii::$app->params['telegramChatId'] ?? '';
        $this->telegram_chat_tag = Yii::$app->params['telegramChatTag'] ?? '';
    }


    public function sendMessage($text_message, $photo = null): ?array
    {
        $data = [
            'chat_id' => $this->telegram_chat_tag,
            'parse_mode' => 'HTML',
        ];

        if (isset($photo)) {
            $url = "https://api.telegram.org/bot{$this->telegram_token}/sendPhoto";
            $data["photo"] = $photo;
            $data["caption"] = $text_message;
        } else {
            $url = "https://api.telegram.org/bot{$this->telegram_token}/sendMessage";
            $data["text"] = $text_message;
        }


        $response = $this->doRequest($url, $data);
        if ($response['success'] && $response['data']['ok']) {
            Yii::$app->session->setFlash('tg_create_success', 'Сообщение успешно отправлено в телеграмме!');
            return $response['data'];
        } else {
            Yii::$app->session->setFlash('tg_create_error', 'Ошибка при отправке сообщения в телеграмме: ' . $response['description']);
            return null;
        }
    }


    public function updateMessage($message_id, $new_text_message, $new_photo = null, $is_media = false)
    {
        if ($is_media) {
            if (isset($new_photo)) {
                $this->updateMediaMessage($message_id, $new_text_message, $new_photo);
                return null;
            } else {
                Yii::$app->session->setFlash('tg_update_error', 'При обновлении медиа сообщения необходимо передавать url изображения!');
                return null;
            }
        } else {
            if (isset($new_photo)) {
                $response = $this->recreateMessage($message_id, $new_text_message, $new_photo);
                return $response;
            } else {
                $this->updateTextMessage($message_id, $new_text_message);
                return null;
            }
        }

    }

    public function recreateMessage($message_id, $new_text_message, $new_photo)
    {
        $this->deleteMessage($message_id);
        $response = $this->sendMessage($new_text_message, $new_photo);

        if (Yii::$app->session->hasFlash('tg_delete_success') && Yii::$app->session->hasFlash('tg_create_success')) {
            Yii::$app->session->removeFlash('tg_delete_success');
            Yii::$app->session->removeFlash('tg_create_success');
            Yii::$app->session->setFlash('tg_update_success', 'Сообщение успешно обнавлено в телеграмм канале!');
        }

        return $response;
    }

    public function updateTextMessage($message_id, $new_text_message)
    {
        $data = [
            'chat_id' => $this->telegram_chat_id,
            'message_id' => $message_id,
            'text' => $new_text_message,
            'parse_mode' => 'HTML',
        ];
        $response = $this->doRequest("https://api.telegram.org/bot{$this->telegram_token}/editMessageText", $data);

        if ($response['success'] && $response['data']['ok']) {
            Yii::$app->session->setFlash('tg_update_success', 'Сообщение успешно обнавлено в телеграмм канале!');
        } else {
            Yii::$app->session->setFlash('tg_update_error', 'Ошибка при обновлении сообщения в телеграмм канале');
        }
    }

    public function updateMediaMessage($message_id, $new_text_message, $new_photo)
    {
        $url = "https://api.telegram.org/bot{$this->telegram_token}/editMessageMedia";

        $data = [
            'chat_id' => $this->telegram_chat_id,
            'message_id' => $message_id,
            'media' => json_encode([
                'type' => 'photo',
                'media' => $new_photo,
                'caption' => $new_text_message,
                'parse_mode' => 'HTML',
            ]),
        ];

        $response = $this->doRequest($url, $data);

        if ($response['success'] && $response['data']['ok']) {
            Yii::$app->session->setFlash('tg_update_success', 'Сообщение успешно обнавлено в телеграмм канале!');
            return $response['data'];
        } else {
            Yii::$app->session->setFlash('tg_update_error', 'Ошибка при обновлении сообщения в телеграмм канале');
            return null;
        }

    }


    public function deleteMessage($message_id)
    {
        $url = "https://api.telegram.org/bot{$this->telegram_token}/deleteMessage";

        $data = [
            'chat_id' => $this->telegram_chat_id,
            'message_id' => $message_id,
        ];

        $response = $this->doRequest($url, $data);

        if ($response['success'] && $response['data']['ok']) {
            Yii::$app->session->setFlash('tg_delete_success', "Сообщение успешно удалено из телеграмм канала!");
        } else {
            Yii::$app->session->setFlash('tg_delete_error', 'Ошибка при удалении сообщения из телеграмм канала');
        }
    }
}