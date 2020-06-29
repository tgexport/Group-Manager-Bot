<?php
declare(strict_types=1);

use AnarchyService\Base;
use AnarchyService\DB\DB;
use AnarchyService\Get;
use AnarchyService\SendRequest\Chat;
use AnarchyService\SendRequest\Send;

require_once 'vendor/autoload.php';

$tg = new Base();
$DB = DB::Database();
if (isset($argv[1])) {
    $argument = trim($argv[1]);
    if ($argument != '') {
        Get::set(file_get_contents($argument));
        unlink($argument);
    }
} else {
    Get::set($tg->getWebhookUpdates());
}

$Group = $DB->SelectData('Database', Get::$chat_id);
if (!$Group) {
    $Administrators = Chat::getChatAdministrators(Get::$chat_id)->result;
    $Admins = [];
    foreach ($Administrators as $administrator) {
        if ($administrator->status == 'administrator') $Admins[] = $administrator->user->id;
        elseif ($administrator->status == 'creator') $Creator = $administrator->user->id;
    }
    $DefaultSettings = [
        [
            'Working' => '1',
            'name' => 'وضعیت فعال بودن ربات در گروه'
        ],
        [
            'WelcomeMSGStatus' => '1',
            'name' => 'وضعیت ارسال پیام خوش آمدگویی'
        ],
        [
            'WelcomeMSG' => 'سلام به گروه خوش آمدید',
            'name' => 'متن پیام خوش آمدگویی'
        ],
        [
            'MeMSGStatus' => '1',
            'name' => 'وضعیت ارسال پیام اینفو'
        ],
        [
            'ConversationStatus' => '1',
            'name' => 'وضعیت پاسخ دهی ربات به کاربران'
        ],
        [
            'SpamReportStatus' => '1',
            'name' => 'وضعیت ارسال گزارش اسپم'
        ],
        [
            'SpamReportInTime' => '5',
            'name' => 'حداقل تعداد ارسال پیام برای گزارش اسپم'
        ],
        [
            'SpamReportInSec' => '5',
            'name' => 'حداقل زمان(ثانیه) برای گزارش اسپم'
        ],
        [
            'CaptchaStatus' => '1',
            'name' => 'وضعیت ارسال کپچا'
        ],
        [
            'DelTGServicesStatus' => '0',
            'name' => 'وضعیت حذف پیام های سرویس تلگرام'
        ],
        [
            'DelLinkStatus' => '0',
            'name' => 'وضعیت حذف لینک'
        ],
        [
            'DelMentionStatus' => '0',
            'name' => 'وضعیت حذف منشن'
        ],
        [
            'DelForwardStatus' => '0',
            'name' => 'وضعیت حذف فوروارد'
        ]
    ];
    $DB->CreateTable('Database', Get::$chat_id, [
        'Chat_id' => Get::$chat_id,
        'Chat_title' => Get::$chat_title,
        'BotAdder' => Get::$from_id,
        'Creator' => $Creator,
        'Administrators' => json_encode($Admins),
        'Settings' => json_encode($DefaultSettings)
    ]);
    $msg = 'سلام'.PHP_EOL.'برای فعال سازی رایگان ربات، من رو به عنوان ادمین گروه انتخاب کنید'.PHP_EOL.'با فرستادن راهنما هم می‌توانید آموزش استفاده از ربات را یاد بگیرید 😃';
    Send::sendMessage(Get::$chat_id, $msg);
    $Group = $DB->SelectData('Database', Get::$chat_id);
}
