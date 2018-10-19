<?php
$array = array(
    'type' => 'bubble',
    'body' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => [
            array(
                "type" => "image",
                "url" => base_url()."asset/img/laporanthreadhapus.png",
                "aspectMode"=>"cover",
                "size"=>"full"
            ),
            array(
                "type" => "box",
                "layout" => "vertical",
                "margin" => "lg",
                "spacing" => "sm",
                "contents" => [
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
                                "text" => $judul,
                                "wrap" => true,
                                "color" => "#666666",
                                "size" => "md",
                                "flex" => 5,
                            ),
                        ],
                    ),
                    array(
                        "type" => "box",
                        "layout" => "baseline",
                        "spacing" => "sm",
                        "contents" => [
                            array(
                                "type" => "text",
                                "text" => "Alasan : ",
                                "color" => "#aaaaaa",
                                "weight"=>"bold",
                                "size" => "md",
                                "flex" => 2,
                            ),
                            array(
                                "type" => "text",
                                "text" => $alasan->laporan,
                                "wrap" => true,
                                "color" => "#666666",
                                "size" => "md",
                                "flex" => 5,
                            ),
                        ],
                    ),
                ],
            ),
        ],
    ),
    "footer" => array(
        "type" => "box",
        "layout" => "vertical",
        "spacing" => "sm",
        "contents" => [
            array(
                "type" => "spacer",
                "size" => "sm",
            )
        ],
        "flex" => 0
    )
);

echo json_encode($array);
?>