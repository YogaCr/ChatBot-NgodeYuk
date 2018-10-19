<?php
$array = array(
    'type' => 'bubble',
    'body' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => [
            
            array(
                "type" => "image",
                "url" => base_url()."asset/img/notifikasidimatikan2.png",
                "aspectMode"=>"cover",
                "size"=>"full"
            ),
            array(
                "type" => "box",
                "layout" => "baseline",
                "spacing" => "sm",
                "contents" => [
                    array(
                        "type" => "text",
                        "text" => "Judul : ",
                        "color" => "#aaaaaa",
                        "weight"=>"bold",
                        "size" => "md",
                        "flex" => 2
                    ),
                    array(
                        "type" => "text",
                        "text" => $tanya->judul,
                        "wrap" => true,
                        "color" => "#666666",
                        "size" => "md",
                        "flex" => 5
                    )
                ]
            )
        ]
    ),
    "footer" => array(
        "type" => "box",
        "layout" => "vertical",
        "spacing" => "sm",
        "contents" => [
            array(
                "type" => "button",
                "style" => "primary",
                "height" => "sm",
                "action" => array(
                    "type"=>"postback",
                    "label"=>"Nyalakan Notifikasi",
                    "data"=>'type=2&'.$tanya->id_tanya,
                    "text"=>"Tolong nyalakan notifikasi untuk thread ini"
                )
            ),
            array(
                "type" => "spacer",
                "size" => "sm"
            )
        ],
        "flex" => 0
    )
);

echo json_encode($array);
?>