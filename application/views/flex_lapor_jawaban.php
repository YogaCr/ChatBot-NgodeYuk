<?php
$array = array(
    'type' => 'bubble',
    'body' => array(
        'type' => 'box',
        'layout' => 'vertical',
        'contents' => [
            array(
                "type" => "image",
                "url" => base_url()."asset/img/laporanmasuk.png",
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
                            array("type" => "text",
                                "text" => "Pelapor : ",
                                "color" => "#aaaaaa",
                                "weight"=>"bold",
                                "size" => "md",
                                "flex" => 2),
                            array(
                                "type" => "text",
                                "text" => $pelapor,
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
                                "text" => "Jawaban : ",
                                "color" => "#aaaaaa",
                                "weight"=>"bold",
                                "size" => "md",
                                "flex" => 2
                            ),
                            array(
                                "type" => "text",
                                "text" => $jawaban->jawaban,
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
                "type" => "button",
                "style" => "primary",
                "height" => "sm",
                "action" => array(
                    "type" => "uri",
                    "label" => "Lihat Jawaban",
                    "uri" => "line://app/1606775713-jROaNM1w?id_tanya=".$pertanyaan->id_tanya."&id_jawab=".$jawaban->id_jawaban,
                )
            ),array(
                "type" => "button",
                "style" => "primary",
                "color"=>"#d31d1d",
                "height" => "sm",
                "action" => array(
                    "type"=>"postback",
                    "label"=>"Hapus Jawaban",
                    "data"=>'type=4&'.$tanya->id_tanya,
                    "text"=>"Hapus jawaban tersebut"
                )
            ),
            array(
                "type" => "spacer",
                "size" => "sm",
            )
        ],
        "flex" => 0,
    )
);

echo json_encode($array);
?>