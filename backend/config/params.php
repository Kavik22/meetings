<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'frontendUrl' => getenv('FRONTEND_URL') ?: 'http://localhost:3000',
    'telegramToken' => getenv('TELEGRAM_TOKEN'),
    'telegramChatId' => getenv('TELEGRAM_CHAT_ID'),
    'telegramChatTag' => getenv('TELEGRAM_CHAT_TAG'),
    'VKAccessToken' => getenv('VK_ACCESS_TOKEN'),
    'VKUserAccessToken' => getenv('VK_USER_ACCESS_TOKEN'),
    'VKGroupId' => getenv('VK_GROUP_ID'),
];
