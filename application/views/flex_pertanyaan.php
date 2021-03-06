<?php
$array = array(
    'type' => 'bubble',
    'body' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => [
            array(
                "type" => "image",
                "url" => base_url()."asset/img/worotanya4.png",
                "aspectMode"=>"cover",
                "size"=>"full"
            ),
            array(
                "type" => "box",
                "layout" => "vertical",
                "margin" => "lg",
                "spacing" => "md",
                "contents" => [
                    array(
                        "type" => "box",
                        "layout" => "baseline",
                        "spacing" => "sm",
                        "contents" => [
                            array("type" => "text",
                                "text" => "Dari : ",
                                "color" => "#aaaaaa",
                                "weight"=>"bold",
                                "size" => "md",
                                "flex" => 2),
                            array(
                                "type" => "text",
                                "text" => $nama,
                                "wrap" => true,
                                "color" => "#666666",
                                "size" => "md",
                                "flex" => 5
                            )
                        ]
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
                    ),
                    array(
                        "type" => "box",
                        "layout" => "baseline",
                        "spacing" => "sm",
                        "contents" => [
                            array(
                                "type" => "text",
                                "text" => "Bahasa : ",
                                "color" => "#aaaaaa",
                                "weight"=>"bold",
                                "size" => "md",
                                "flex" => 2
                            ),
                            array(
                                "type" => "text",
                                "text" => $pertanyaan->bahasa,
                                "wrap" => true,
                                "color" => "#666666",
                                "size" => "md",
                                "flex" => 5
                            )
                        ]
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
                    "label" => "Jawab",
                    "uri" => "line://app/1606775713-jROaNM1w?id_tanya=".$pertanyaan->id_tanya
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