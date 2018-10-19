<?php defined('BASEPATH') or exit('No direct script access allowed');

use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
use \LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class Webhook extends CI_Controller
{

    private $bot;
    private $events;
    private $signature;
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_TanyaJawab');
        $this->load->model('M_User');

    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 400 Only POST method allowed');
            exit;
        }

        $body = file_get_contents('php://input');
        $this->signature = isset($_SERVER['HTTP_X_LINE_SIGNATURE']) ? $_SERVER['HTTP_X_LINE_SIGNATURE'] : "-";
        $this->events = json_decode($body, true);

        $httpClient = new CurlHTTPClient($_SERVER['CHANNEL_ACCESS_TOKEN']);
        $bot = new LINEBot($httpClient, ['channelSecret' => $_SERVER['CHANNEL_SECRET']]);

        foreach ($this->events['events'] as $event) {
            if ($event['type'] == 'postback') {
                $dat = $event['postback']['data'];
                if (substr($dat, 5, 1) == "1") {
                    if ($this->M_TanyaJawab->checkStatusNotif($event['source']['userId'], substr($dat, 7))) {
                        if ($this->M_TanyaJawab->hapusNotif($event['source']['userId'], substr($dat, 7))) {
                            $flexTemplate = file_get_contents(base_url() . "index.php/PengaturFlex/FlexNotifDimatikan/" . substr($dat, 7));
                            $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/push', [
                                'to' => $event['source']['userId'],
                                'messages' => [
                                    [
                                        'type' => 'flex',
                                        'altText' => 'Notifikasi dimatikan',
                                        'contents' => json_decode($flexTemplate, true),
                                    ]
                                ]
                            ]
                            );

                        }
                    } else {

                    }
                }
                if (substr($dat, 5, 1) == "2") {
                    if (!$this->M_TanyaJawab->checkStatusNotif($event['source']['userId'], substr($dat, 7))) {
                        if ($this->M_TanyaJawab->nyalaNotif($event['source']['userId'], substr($dat, 7))) {
                            $flexTemplate = file_get_contents(base_url() . "index.php/PengaturFlex/FlexNotifDinyalakan/" . substr($dat, 7));
                            echo $flexTemplate;
                            $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/push', [
                                'to' => $event['source']['userId'],
                                'messages' => [
                                    [
                                        'type' => 'flex',
                                        'altText' => 'Notifikasi dinyalakan',
                                        'contents' => json_decode($flexTemplate, true),
                                    ],
                                ],
                            ]
                            );

                        }
                    } else {

                    }
                }
                if(substr($dat,5,1)=="3"){
                    $tanya = $this->M_TanyaJawab->getPertanyaan(substr($dat, 9));
                    $judul = $tanya->judul;
                    $user=$tanya->user_id;
                    if($this->M_TanyaJawab->hapusPertanyaan(substr($dat, 9))){
                        $postdata = http_build_query(
                            array(
                                'alasan' => substr($dat,7,1),
                                'judul'=>$judul
                            )
                        );
            
                        $opts = array('http' => array(
                            'method' => 'POST',
                            'header' => 'Content-type: application/x-www-form-urlencoded',
                            'content' => $postdata,
                        ),
                        );
            
                        $context = stream_context_create($opts);
                        $flexTemplate = file_get_contents(base_url() . "index.php/PengaturFlex/FlexLaporThreadHapus/", true, $context);
                        $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/push', [
                            'to' => $user,
                            'messages' => [
                                [
                                    'type' => 'flex',
                                    'altText' => 'Thread anda telah dihapus',
                                    'contents' => json_decode($flexTemplate, true),
                                ],
                            ],
                        ]
                        );
                    }
                }
                if (substr($dat, 5, 1) == "4") {
                    $flexTemplate = file_get_contents(base_url() . "asset/json/".substr($dat,7));
                            $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                                'replyToken' => $event['replyToken'],
                                'messages' => [
                                    [
                                        'type' => 'flex',
                                        'altText' => 'Belajar Yuk',
                                        'contents' => json_decode($flexTemplate, true)
                                    ]
                                ]
                            ]);
                }
            } else if ($event['type'] == 'message') {
                if ($event['message']['type'] == 'text') {
                    $pesan = strtolower($event['message']['text']);
                    if ($pesan=="ajari ngoding dong") {
                        
                        $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                            'replyToken' => $event['replyToken'],
                            'messages' => [
                                array(
                                    "type" => "imagemap",
                                    "baseUrl" => base_url() . "asset/img/materi",
                                    "altText" => "Materi",
                                    "baseSize" => array(
                                        "height" => 1040,
                                        "width" => 1040
                                    ),
                                    "actions" => [
                                        array(
                                            "type" => "message",
                                            "text" => "Ajari Kotlin",
                                            "area" => array(
                                                "x" => 0,
                                                "y" => 0,
                                                "width" => 1040,
                                                "height" => 1040
                                            )
                                        )
                                    ]
                                )
                            ]
                        ]
                        );
                    } 
                    else if(substr($pesan,0,5)=="ajari"){
                        $flexTemplate = file_get_contents(base_url() . "asset/json/flex_".substr($pesan,6)."_list.json");
                        if($flexTemplate){
                             $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                                 'replyToken' => $event['replyToken'],
                                 'messages' => [
                                     [
                                         'type' => 'flex',
                                         'altText' => 'Belajar Yuk',
                                         'contents' => json_decode($flexTemplate, true)
                                     ]
                                 ]
                             ]); }
                             else{
                                 
                             }
                    }
                    else if ($pesan == "tolong matikan notifikasi untuk thread ini" || $pesan == "tolong nyalakan notifikasi untuk thread ini") {

                    } else if ($pesan == "forum") {
                        $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/reply', [
                            'replyToken' => $event['replyToken'],
                            'messages' => [
                                array(
                                    "type" => "imagemap",
                                    "baseUrl" => base_url() . "asset/img/imgmap_forum",
                                    "altText" => "Pilih menu forum",
                                    "baseSize" => array(
                                        "height" => 1040,
                                        "width" => 1040
                                    ),
                                    "actions" => [
                                        array(
                                            "type" => "uri",
                                            "linkUri" => "line://app/1606775713-49rXnRDe",
                                            "area" => array(
                                                "x" => 0,
                                                "y" => 0,
                                                "width" => 520,
                                                "height" => 1040
                                            )
                                        ),
                                        array(
                                            "type" => "uri",
                                            "linkUri" => "line://app/1606775713-bEvDgj6O",
                                            "area" => array(
                                                "x" => 520,
                                                "y" => 0,
                                                "width" => 520,
                                                "height" => 1040
                                            )
                                        )
                                    ]
                                )
                            ]
                        ]
                        );
                    } else {
                        $tm1 = new TextMessageBuilder("Pangapunten, saya tidak mengerti apa yang anda kirim");
                        $bot->replyMessage($event['replyToken'], $tm1);
                    }
                }
            } else if ($event['type'] == 'follow') {
                if ($this->M_User->checkUser($event['source']['userId'])) {
                    $tm1 = new TextMessageBuilder("Wah ketemu lagi nih. Gimana kabarnya? Ayo kalau mau belajar atau mau tanya-tanya di sini jangan sungkan ya.");
                    $stickerMessageBuilder = new StickerMessageBuilder(1, 13);
                    $multiMessageBuilder = new MultiMessageBuilder();
                    $multiMessageBuilder->add($tm1);
                    $multiMessageBuilder->add($stickerMessageBuilder);
                    $bot->replyMessage($event['replyToken'], $multiMessageBuilder);
                } else {
                    $this->M_User->insertUser($event['source']['userId']);
                    $tm1 = new TextMessageBuilder("Halo. Perkenalkan namaku Mang Kode. Kalau mau belajar atau tanya-tanya, tidak usah sungkan tanya di sini ya.");
                    $stickerMessageBuilder = new StickerMessageBuilder(1, 114);
                    $multiMessageBuilder = new MultiMessageBuilder();
                    $multiMessageBuilder->add($tm1);
                    $multiMessageBuilder->add($stickerMessageBuilder);
                    $bot->replyMessage($event['replyToken'], $multiMessageBuilder);
                }
            }
        }

    }

    public function InsertPertanyaan()
    {
        $httpClient = new CurlHTTPClient($_SERVER['CHANNEL_ACCESS_TOKEN']);
        $bot = new LINEBot($httpClient, ['channelSecret' => $_SERVER['CHANNEL_SECRET']]);
        if ($this->M_TanyaJawab->InsertPertanyaan()) {
            $idtanya = $this->M_TanyaJawab->getIdPertanyaan();
            $user = $this->M_User->getUserForSendingFlex($this->input->post('id_user'));
            $postdata = http_build_query(
                array(
                    'nama' => $this->input->post('nama'),
                )
            );

            $opts = array('http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
            ),
            );

            $context = stream_context_create($opts);
            $flexTemplate = file_get_contents(base_url() . "index.php/PengaturFlex/FlexTanya/" . $idtanya->id_tanya, true, $context);
            echo $flexTemplate;
            foreach ($user as $u) {
                $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/push', [
                    'to' => $u->user_id,
                    'messages' => [
                        [
                            'type' => 'flex',
                            'altText' => 'Ada yang nanya nih',
                            'contents' => json_decode($flexTemplate, true),
                        ],
                    ],
                ]
                );
            }
            $this->M_TanyaJawab->insertDapatNotif($this->input->post('id_user'), $idtanya->id_tanya);
            $flexTerkirim = file_get_contents(base_url() . "index.php/PengaturFlex/BerhasilDikirim/" . $idtanya->id_tanya);
            $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/push', [
                'to' => $this->input->post('id_user'),
                'messages' => [
                    [
                        'type' => 'flex',
                        'altText' => 'Pertanyaan terkirim',
                        'contents' => json_decode($flexTerkirim, true),
                    ],
                ],
            ]
            );
        }
    }

    public function EditPertanyaan()
    {
        $httpClient = new CurlHTTPClient($_SERVER['CHANNEL_ACCESS_TOKEN']);
        $bot = new LINEBot($httpClient, ['channelSecret' => $_SERVER['CHANNEL_SECRET']]);
        if ($this->M_TanyaJawab->updatePertanyaan()) {
            $idtanya = $this->input->post('id_tanya');
            $user = $this->M_TanyaJawab->getUserNotif($idtanya, $this->input->post('id_user'));
            $postdata = http_build_query(
                array(
                    'nama' => $this->input->post('nama'),
                )
            );

            $opts = array('http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
            ),
            );

            $context = stream_context_create($opts);
            $flexTemplate = file_get_contents(base_url() . "index.php/PengaturFlex/FlexTanya/" . $idtanya, true, $context);
            echo $flexTemplate;
            foreach ($user as $u) {
                $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/push', [
                    'to' => $u->user_id,
                    'messages' => [
                        [
                            'type' => 'flex',
                            'altText' => 'Ada yang nanya nih',
                            'contents' => json_decode($flexTemplate, true),
                        ],
                    ],
                ]
                );
            }
        }
    }
    public function LaporkanSpam()
    {
        $httpClient = new CurlHTTPClient($_SERVER['CHANNEL_ACCESS_TOKEN']);
        if ($this->M_TanyaJawab->insertLapor()) {
            $postdata = http_build_query(
                array(
                    'pelapor' => $this->input->post('pelapor'),
                    'alasan' => $this->input->post('alasan'),
                    'id_tanya' => $this->input->post('id_tanya'),
                )
            );

            $opts = array('http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
            ),
            );

            $context = stream_context_create($opts);
            $flexTemplate = file_get_contents(base_url() . "index.php/PengaturFlex/FlexLapor/", true, $context);
            echo $flexTemplate;
            $admin = $this->M_User->getAdmin();
            foreach ($admin as $a) {
                $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/push', [
                    'to' => $a->user_id,
                    'messages' => [
                        [
                            'type' => 'flex',
                            'altText' => 'Ada yang lapor nih',
                            'contents' => json_decode($flexTemplate, true),
                        ],
                    ],
                ]
                );
            }
        }
    }
    public function LaporkanJawaban()
    {
        $httpClient = new CurlHTTPClient($_SERVER['CHANNEL_ACCESS_TOKEN']);
        if ($this->M_TanyaJawab->insertLaporJawaban()) {
            $postdata = http_build_query(
                array(
                    'pelapor' => $this->input->post('pelapor'),
                    'alasan' => $this->input->post('alasan'),
                    'id_jawab' => $this->input->post('id_jawab'),
                    'id_tanya' => $this->input->post('id_tanya'),
                )
            );

            $opts = array('http' => array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
            ),
            );

            $context = stream_context_create($opts);
            $flexTemplate = file_get_contents(base_url() . "index.php/PengaturFlex/FlexLaporJawaban/", true, $context);
            $admin = $this->M_User->getAdmin();
            foreach ($admin as $a) {
                $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/push', [
                    'to' => $a->user_id,
                    'messages' => [
                        [
                            'type' => 'flex',
                            'altText' => 'Ada yang laporin jawaban nih',
                            'contents' => json_decode($flexTemplate, true),
                        ],
                    ],
                ]
                );
            }
        }
    }
    public function Jawab()
    {
        $httpClient = new CurlHTTPClient($_SERVER['CHANNEL_ACCESS_TOKEN']);
        $jawab = $this->M_TanyaJawab->insertJawaban();
        echo json_encode($jawab);
        $id_tanya = $this->input->post('id_tanya');
        if (!$this->M_TanyaJawab->getUserTanyaNotif($this->input->post('user_id'), $id_tanya)) {
            $this->M_TanyaJawab->insertDapatNotif($this->input->post('user_id'), $id_tanya);
        }
        $flexTemplate = file_get_contents(base_url() . "index.php/PengaturFlex/FlexJawab/" . $id_tanya);
        $dapatnotif = $this->M_TanyaJawab->getUserNotif($id_tanya, $this->input->post('user_id'));
        foreach ($dapatnotif as $u) {
            $result = $httpClient->post(LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/bot/message/push', [
                'to' => $u->user_id,
                'messages' => [
                    [
                        'type' => 'flex',
                        'altText' => 'Ada yang jawab nih',
                        'contents' => json_decode($flexTemplate, true),
                    ],
                ],
            ]
            );
        }
    }

    public function getProfile()
    {
        $httpClient = new CurlHTTPClient($_SERVER['CHANNEL_ACCESS_TOKEN']);
        $bot = new LINEBot($httpClient, ['channelSecret' => $_SERVER['CHANNEL_SECRET']]);
        $response = $bot->getProfile($this->input->post('userid'));
        if ($response->isSucceeded()) {
            $profile = $response->getJSONDecodedBody();
            $arr = array('nama' => $profile['displayName']);
            echo json_encode($arr);
        } else {
            return;
        }
    }

    public function HapusPertanyaan($id_tanya)
    {
        $this->M_TanyaJawab->hapusPertanyaan($id_tanya);
    }

    public function tes()
    {
        $arr=array(
            "type" => "imagemap",
            "baseUrl" => base_url() . "asset/img/imgmap_forum",
            "altText" => "Pilih menu forum",
            "baseSize" => array(
                "height" => 1040,
                "width" => 1040
            ),
            "actions" => [
                array(
                    "type" => "uri",
                    "linkUri" => "line://app/1606775713-49rXnRDe",
                    "area" => array(
                        "x" => 0,
                        "y" => 0,
                        "width" => 520,
                        "height" => 1040
                    )
                ),
                array(
                    "type" => "uri",
                    "linkUri" => "line://app/1606775713-bEvDgj6O",
                    "area" => array(
                        "x" => 520,
                        "y" => 0,
                        "width" => 520,
                        "height" => 1040
                    )
                )
            ]
                    );
        echo json_encode($arr);
    }
}
?>