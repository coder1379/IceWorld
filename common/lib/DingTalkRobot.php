<?php
/**
 * 钉钉机器人封装
 * 通过关键字触发
 */

namespace common\lib;

use Yii;
use GuzzleHttp\Client;

class DingTalkRobot
{

    /**
     * @var string
     */
    public $accessToken;

    /**
     * @var string
     */
    public $apiUrl = 'https://oapi.dingtalk.com/robot/send';

    /**
     * @var array
     */
    public $guzzleOptions = ['verify'=>false,'timeout'=>10];

    /**
     * @var array
     */
    public $msgTypeList = ['text','link','markdown','actionCard','feedCard'];

    public function init()
    {
        if ($this->accessToken === null) {
            throw new \Exception('The "accessToken" property must be set.');
        }
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    /**
     * @param array $options
     */
    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    /**
     * 发送文本消息
     * @param string $content
     * @param array $atMobiles
     * @param bool $isAtAll
     * @return mixed
     */
    public function sendTextMsg($content, array $atMobiles = [], $isAtAll = false)
    {
        $query = [
            'msgtype' => 'text',
            'text' => [
                'content' => $content,
            ],
            'at' => [
                'isAtAll' => $isAtAll
            ],
        ];
        if (is_array($atMobiles) && count($atMobiles)>0) {
            $query['at']['atMobiles'] = $atMobiles;
        }
        return $this->sendMsg($query);
    }

    /**
     * 发送链接
     * @param string $title
     * @param string $text
     * @param string $picUrl
     * @param string $messageUrl
     * @return mixed
     */
    public function sendLinkMsg($title, $text, $picUrl = '', $messageUrl)
    {
        $query = [
            'msgtype' => 'link',
            'link' => [
                'title' => $title,
                'text' => $text,
                'picUrl' => $picUrl,
                'messageUrl' => $messageUrl
            ],
        ];
        return $this->sendMsg($query);
    }

    /**
     * 发送MarkDown 消息
     * @param string $title
     * @param string $content
     * @param array $atMobiles
     * @param bool $isAtAll
     * @return mixed
     */
    public function sendMarkdownMsg($title, $content, array $atMobiles = [], $isAtAll = false)
    {
        $query = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => $content,
            ],
            'at' => [
                'isAtAll' => $isAtAll
            ],
        ];
        if (is_array($atMobiles) && count($atMobiles)>0) {
            $query['at']['atMobiles'] = $atMobiles;
        }
        return $this->sendMsg($query);
    }

    /**
     * 整体跳转ActionCard类型
     * @param $title
     * @param $content
     * @param $singleURL
     * @param int $hideAvatar
     * @param int $btnOrientation
     * @param string $singleTitle
     * @return mixed
     */
    public function sendActionCardMsg($title, $content, $singleURL, $hideAvatar = 0, $btnOrientation = 0, $singleTitle = '阅读原文')
    {
        $query = [
            'msgtype' => 'actionCard',
            'actionCard' => [
                'title' => $title,
                'text' => $content,
                'hideAvatar' => $hideAvatar,
                'btnOrientation' => $btnOrientation,
                'singleTitle' => $singleTitle,
                'singleURL' => $singleURL
            ],
        ];
        return $this->sendMsg($query);
    }

    /**
     * 独立跳转ActionCard类型
     * @param $title
     * @param $content
     * @param int $hideAvatar
     * @param int $btnOrientation
     * @param array $btns
     * @return mixed
     */
    public function sendSingleActionCardMsg($title, $content, $hideAvatar = 0, $btnOrientation = 0, array $btns=[])
    {
        $query = [
            'msgtype' => 'actionCard',
            'actionCard' => [
                'title' => $title,
                'text' => $content,
                'hideAvatar' => $hideAvatar,
                'btnOrientation' => $btnOrientation,
                'btns' => $btns
            ],
        ];
        return $this->sendMsg($query);
    }

    /**
     * FeedCard类型
     * @param array $links
     * @return mixed
     */
    public function sendFeedCardMsg(array $links=[])
    {
        if(!\is_array($links)){
            throw new InvalidValueException('this data must be array');
        }
        if(count($links) == count($links, 1)){
            throw new InvalidValueException('this data must be dyadic array');
        }
        $query = [
            'msgtype' => 'feedCard',
            'feedCard' => [
                'links'=> $links
            ],
        ];
        return $this->sendMsg($query);
    }

    /**
     * @param string $type
     * @param array $msgData
     * @return mixed
     */
    public function sendMsg(array $msgData=[])
    {

            $response = $this->getHttpClient()->post($this->apiUrl."?access_token=".$this->accessToken, [
                \GuzzleHttp\RequestOptions::JSON => $msgData,
                'headers' => [
                    'Content-Type'=> 'application/json;charset=utf-8'
                ],
            ])->getBody()->getContents();
            return $response;
    }
}