<?php
$array = array(
    'type' => 'bubble',
    'body' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => [
            
            array(
                "type" => "image",
                "url" => base_url()."asset/img/worojawaban.png",
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
                        "text" => $pertanyaan->judul,
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
                    "type" => "uri",
                    "label" => "Lihat Jawaban",
                    "uri" => "line://app/1606775713-jROaNM1w?id_tanya=".$pertanyaan->id_tanya
                )
            ),
            array(
                "type" => "button",
                "style" => "primary",
                "height" => "sm",
                "color"=>"#d31d1d",
                "action" => array(
                    "type"=>"postback",
                    "label"=>"Matikan Notifikasi",
                    "data"=>'type=1&'.$pertanyaan->id_tanya,
                    "text"=>"Tolong matikan notifikasi untuk thread ini"
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