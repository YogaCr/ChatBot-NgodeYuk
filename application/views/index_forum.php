<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        Forum Mang Kode
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <link href="<?= base_url()?>asset/css/open-iconic-bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url()?>asset/style.css">
</head>

<body>
    <div class="container">
        <h2>Forum Mang Kode</h2>
        <hr>
        <?php foreach($pertanyaan as $p){?>

        <div class="row">
            <div class="col-md-12">
                <a href="<?= base_url()?>index.php/Tanyajawab/PageTanya?id_tanya=<?= $p->id_tanya?>">
                    <h5>
                        <?= $p->judul?>
                    </h5>
                </a>
            </div>
            <div class="col-md-4">
                <p>Bahasa :
                    <?= $p->bahasa?>
                </p>
            </div>
        </div>
        <hr>
        <?php }?>
    </div>
</body>

</html>