<?php 
$arr = array(
  "type"=> "imagemap",
  "baseUrl"=> base_url()."asset/img/imgmap_forum/1040.png",
  "altText"=> "This is an imagemap",
  "baseSize"=>array(
      "height"=> 1040,
      "width"=> 1040
  ),
  "actions"=> [
      array(
          "type"=> "uri",
          "linkUri"=> "https://example.com/",
          "area"=> array(
              "x"=> 0,
              "y"=> 0,
              "width"=> 520,
              "height"=> 1040
          )
        ),
      array(
          "type"=> "message",
          "text"=> "Hello",
          "area"=> array(
              "x"=> 520,
              "y"=> 0,
              "width"=> 520,
              "height"=> 1040
      )
      )
  ]
);

echo json_encode($arr);
?>