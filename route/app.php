<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use app\controller\queue\Job\SimpleJob;
use EasyWeChat\Factory;
use think\facade\Log;
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');
Route::get("login/code", function () {
    // todo 只有在企业安装成功时会调用一次  所以需要在此处做获取企业永久授权码并存储的逻辑 相当于用户注册的处理
    $config = config("wechat");
    $app = Factory::openWork($config);
    $permanentByCode = $app->corp->getPermanentByCode(request()->get("auth_code"));
    Log::info("接收到的信息-permanentByCode:" . json_encode($permanentByCode, true));
    dd($permanentByCode);
});

Route::get("login", function () {
    // todo 生成安装授权连接
    $config = config("wechat");
    $app = Factory::openWork($config);
    $preAuthCode = $app->corp->getPreAuthCode()["pre_auth_code"];
    $app->corp->setSession($preAuthCode, ["auth_type" => 1]);
    return redirect($app->corp->getPreAuthorizationUrl($preAuthCode, 'http://test7.showgrid.cn/login/code', "123123"));
});
Route::get("qiyejihuo", function () {
    $config = config("wechat");
    $app = Factory::openWork($config);
    // 下单购买账号   OI000012007ECB66A6091B6E163C6T
//    dd($app->license_order->create("wpy34AEgAAxNfz2qmxZrKgf-tY9NFY_A", [
//        "buyer_userid" => "ChenGuangYu", // 为服务商账户的用户ID（明文）
//        "account_count" => [
//            "external_contact_count" => 100
//        ],
//        "account_duration" => [
//            "months" => 1
//        ]
//    ]));

    //获取订单中的账号列表    "active_code" => "LA20000000400000066A6091C"
//    dd($app->license_order->getAccountList("OI000012007ECB66A6091B6E163C6T","",2));

    // 激活账号
//    dd($app->license_account->active("LA20000000400000166A6091C","wpy34AEgAAxNfz2qmxZrKgf-tY9NFY_A","woy34AEgAAUrLGm1N-iG6qLNs24uZPMQ"));
//    dd($app->provider_access_token->getToken());
    // 转移账号
    dd($app->license_account->httpPostJson('cgi-bin/license/get_active_info_by_user', [
        'corpid' => "wpy34AEgAAxNfz2qmxZrKgf-tY9NFY_A",
        'userid'=>'woy34AEgAAHpEbBvpmFYF4ulYU1pSGlQ'
    ]));

});
Route::get("getUserList", function () {
    $config = config("wechat");
    $app = Factory::openWork($config);
    // cid => wpy34AEgAAxNfz2qmxZrKgf-tY9NFY_A
    $work = $app->work("wpy34AEgAAxNfz2qmxZrKgf-tY9NFY_A", "kJ4WpTynBktARTeR0Z5C0UGJJgdyKy32lzb9p2jcATs");
//    dd($work->user->mobileToUserId("17222222222"));// 根据手机号获取内部用户userid

    // 客服头像   "media_id" => "3R5kn72cVYpmwvZjP_g5uV90ZxQAS4STQFrQ6Az68gLcUEAqfsH-Aeo1R9bl6Ttcb"
//    dd($work->media->uploadImage("6.png"));

    // 客服id "open_kfid" => "wky34AEgAAboDC-BShqVnE5in0yobsmA"

    dd($work->kf_account->add("测试客服-888","3R5kn72cVYpmwvZjP_g5uV90ZxQAS4STQFrQ6Az68gLcUEAqfsH-Aeo1R9bl6Ttcb"));

//    dd($work->kf_servicer->list("wky34AEgAAu3F_P_W64-eWBDTPhBoNiw"));

    // 添加客服接待
//    dd($work->kf_message->sync("", "ENCCNJ73q3VaxJLAKS2TpsKiWWephjrpLSiFdxNJ8HAUB2W", 1000));
//    dd($work->kf_servicer->add("wky34AEgAAboDC-BShqVnE5in0yobsmA",["woy34AEgAAHpEbBvpmFYF4ulYU1pSGlQ"]));
//    dd($work->kf_servicer->add("wky34AEgAAu3F_P_W64-eWBDTPhBoNiw",["woy34AEgAAUrLGm1N-iG6qLNs24uZPMQ"]));


//    dd($work->kf_message->state("wky34AEgAAu3F_P_W64-eWBDTPhBoNiw","wpy34AEgAAxNfz2qmxZrKgf-tY9NFY_A"));

//    dd($work->external_contact->list("wky34AEgAAu3F_P_W64-eWBDTPhBoNiw"));
//        dd($work->external_contact->get("woy34AEgAAUrLGm1N-iG6qLNs24uZPMQ"));

//    dd($work->corp_group->batchUseridToOpenUserid(["woy34AEgAAivnsp1RU82qVF7DWSagOeQ"]));


    dd($work->kf_message->send([
        "touser" => "wmy34AEgAAO7pu5ZmFqtCOSB7eyP4KbA",
        "open_kfid" => "wky34AEgAAJaLgpOXUS2xCUCAaWmZluQ",
        "msgtype" => "text",
        "text" => [
            "content" => "你购买的物1品已发货，可点击链接查看物流状态http://work.weixin.qq.com/xxxxxx"
        ]
    ]));


});

Route::get("sync", function () {
    $config = config("wechat");
    $app = Factory::openWork($config);
    $work = $app->work("wpy34AEgAAxNfz2qmxZrKgf-tY9NFY_A", "kJ4WpTynBktARTeR0Z5C0UGJJgdyKy32lzb9p2jcATs");

    $params = [
        'cursor' => "",
        'token' => "ENCGktNzXdww62CEFThTBZsTjSiXbEQ5XMgkcCSk1afaSfM",
        'limit' => 10,
        'open_kfid'=>'wky34AEgAAJaLgpOXUS2xCUCAaWmZluQ'
    ];

    dd($work->kf_message->httpPostJson('cgi-bin/kf/sync_msg', $params));
});

Route::get("sendKfMsg",function (){
    $config = config("wechat");
    $app = Factory::openWork($config);
    $work = $app->work("wpy34AEgAAxNfz2qmxZrKgf-tY9NFY_A", "kJ4WpTynBktARTeR0Z5C0UGJJgdyKy32lzb9p2jcATs");
    dd($work->kf_message->send([
        "touser" => "wmy34AEgAAO7pu5ZmFqtCOSB7eyP4KbA",
        "open_kfid" => "wky34AEgAAFnb3xGak6FFC99S2FJyM0A",
        "msgtype" => "text",
        "text" => [
            "content" => "你购买的物1品已发货，可点击链接查看物流状态http://work.weixin.qq.com/xxxxxx"
        ]
    ]));
});
Route::get("sendAppMsg", function () {
    $config = config("wechat");
    $app = Factory::openWork($config);
//    dd($app->corp->getAuthorization("wpy34AEgAAxNfz2qmxZrKgf-tY9NFY_A", "kJ4WpTynBktARTeR0Z5C0UGJJgdyKy32lzb9p2jcATs"));

    $work = $app->work("wpy34AEgAAxNfz2qmxZrKgf-tY9NFY_A", "kJ4WpTynBktARTeR0Z5C0UGJJgdyKy32lzb9p2jcATs");

    dd($work->message->send([
        "touser" => "@all",
        "msgtype" => "text",
        "agentid"=>"1000013",
        "text" => [
            "content" => "你购买的物1品已发货，可点击链接查看物流状态http://work.weixin.qq.com/xxxxxx"
        ]
    ]));


});


Route::any("/callback/data", function () {
    $config = config("wechat");
    $app = Factory::openWork($config);
    $server = $app->server;
    $server->push(function ($message) {
        Log::info("接收到的信息-data:" . json_encode($message, true));
        //数据回调
        if (isset($message['MsgType'])) {
            switch ($message['MsgType']) {
                case 'event':
                    return '事件消息';//详情 https://work.weixin.qq.com/api/doc/90001/90143/90376#%E5%88%A0%E9%99%A4%E6%88%90%E5%91%98%E4%BA%8B%E4%BB%B6
                    break;
                case 'text':
                    Log::info("接收到的信息-text:" . json_encode($message, true));
                    return '文本消息';//详情 https://work.weixin.qq.com/api/doc/90001/90143/90375#%E5%9B%BE%E7%89%87%E6%B6%88%E6%81%AF
                    break;
                case 'image':
                    return '图片消息';
                    break;
                //等等...不再一一举例
                default:
                    return '其他消息';
                    break;
            }
        }

    });
    $response = $server->serve();
    $response->send();
});

Route::any("/callback/command", function () {
    $config = config("wechat");
    $app = Factory::openWork($config);
    $server = $app->server;
    $server->push(function ($message) {
        Log::info("接收到的信息-command:" . json_encode($message, true));
        //指令回调
        if (isset($message['InfoType'])) {
            switch ($message['InfoType']) {
                //推送suite_ticket
                //SDK 默认会处理事件 suite_ticket ，并会缓存 suite_ticket
                case 'suite_ticket':
                    break;
                //授权成功通知
                case 'create_auth':
                    break;
                //变更授权通知
                case 'cancel_auth':
                    break;
                //通讯录事件通知
                case 'change_contact':
                    switch ($message['ChangeType']) {
                        case 'create_user':
                            return '新增成员事件';
                            break;
                        case 'update_user':
                            return '更新成员事件';
                            break;
                        case 'delete_user':
                            return '删除成员事件';
                            break;
                        case 'create_party':
                            return '新增部门事件';
                            break;
                        case 'update_party':
                            return '更新部门事件';
                            break;
                        case 'delete_party':
                            return '删除部门事件';
                            break;
                        case 'update_tag':
                            return '标签成员变更事件';
                            break;
                    }
                    break;
                default:
                    return 'fail';
                    break;
            }
        }

    });
    $response = $server->serve();
    $response->send();
});