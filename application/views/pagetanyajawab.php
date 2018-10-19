<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        <?= $pertanyaan->judul;?>
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
    <div class="loader"></div>
    <div class="container" style="display:none;">
        <h2>
            <?php 
                echo $pertanyaan->judul;
            ?>
        </h2>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-outline-info float-right" id="notif" onclick="setNotif()"><span class="oi oi-bell"></span></button>
            </div>
            <div class="col-md-12">
                <div id="penanya">Dari : </div>
            </div>
            <div class="col-md-12">
                <p>Bahasa :
                    <?= $pertanyaan->bahasa;?>
                </p>
            </div>
        </div>
        <p style="margin:24px 0">
            <?= $pertanyaan->pertanyaan;?>
        </p>
        <p style="font-weight:bold">Dijawab oleh
            <?= $banyakpenjawab?> orang</p>
        <div id="button-action-pertanyaan">

        </div>
        <div id="jawaban">
            <?php foreach($jawaban as $j){ ?>
            <hr>
            <div id="jawaban<?=$j->id_jawaban;?>" class="row">
                <div class="col-md-12">
                    <div id="nama<?=$j->id_jawaban; ?>">Jawaban dari : </div>
                    <p id="textjwb<?=$j->id_jawaban;?>" class="textjwb">
                        <?= $j->jawaban;?>
                    </p>
                </div>
                <div class="col-md-12" id="div-button<?= $j->id_jawaban;?>">
                    <div id="div_penyelesaian<?= $j->id_jawaban?>">
                        <?php if($j->id_jawaban==$pertanyaan->id_jawaban){?>
                        <button type="button" class="btn btn-outline-success" disabled="" id="penyelesaian">Penyelesaian</button>
                        <?php } else{?>
                        <button type="button" class="btn btn-outline-primary btn-selesai" onclick="setJawaban('<?= $j->id_jawaban;?>')">Jadikan
                            sebagai penyelesaian</button>
                        <?php } ?>
                    </div>
                </div>

            </div>
            <?php }?>
        </div>
        <hr>
        <div class="form-group">
            <label>Jawab di sini : </label>
            <textarea class="form-control" rows="10" id="text_jawaban"></textarea>
        </div>
        <button type="button" class="btn btn-default border" id="kirim">Kirim</button>
    </div>
    <div id="snackbar">Form harus diisi semua</div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="$('#myModal').hide();$('#button_lapor').html('')">&times;</span>
            <span>Alasan : </span><select class="js-example-basic-single" id="alasan">
                <option value="1">Spam / sudah pernah ditanyakan sebelumnya</option>
                <option value="2">Mengandung kata-kata yang tidak pantas</option>
            </select>
            <div id="button_lapor"></div>
        </div>
    </div>

    <div id="modalHapusThread" class="modal">
        <div class="modal-content">
            <span class="close" onclick="$('#modalHapusThread').hide();">&times;</span>
            <div class="row">
                <div class="col-md-12">Apakah anda yakin ingin menghapus thread ini?</div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-outline-success" onclick="hapusThread('<?= $pertanyaan->id_tanya ?>')">Ya</button>
                    <button type="button" class="btn btn-outline-danger" onclick="$('#modalHapusThread').hide();" style="margin-left:12px;">Tidak</button></div>
            </div>
        </div>
    </div>
    <div id="modalHapusJawaban" class="modal">
        <div class="modal-content">
            <span class="close" onclick="$('#modalHapusJawaban').hide();">&times;</span>
            <div class="row">
                <div class="col-md-12">Apakah anda yakin ingin menghapus jawaban ini?</div>
            </div>
            <div class="col-md-12" id="buttonhapusjawaban"></div>
        </div>
    </div>
</body>

<script>
    var laporkanpertanyaan;
    var pelapor;
    var user_id;
    var id_tanya = <?= $id_tanya;?>;
    var id_penanya = '<?= $pertanyaan->user_id;?>';
    var total = 0;
    var penyelesaian = '<?= $pertanyaan->id_jawaban?>';



    function inisialisasi() {
        $('.js-example-basic-single').select2();
        $.ajax({
            type: 'POST',
            url: '<?= base_url()?>index.php/webhook/getProfile',
            data: {
                userid: id_penanya
            },
            dataType: 'json',
            success: function (data) {
                $('#penanya').append(data.nama);
            }
        });
        <?php if($banyakpenjawab==0){?>
        $('.loader').hide();
        $('.container').show();
        <?php }?>
        <?php foreach($jawaban as $j){ ?>
        $.ajax({
            type: 'POST',
            url: '<?= base_url()?>index.php/webhook/getProfile',
            data: {
                userid: '<?= $j->user_id?>'
            },
            dataType: 'json',
            success: function (data) {
                total++;
                var uid = '<?= $j->user_id?>';
                $('#nama' + <?= $j->id_jawaban;?>).append(data.nama);
                if (uid == user_id) {
                    var html =
                        '<div class="col-md-12" style="margin:12px 0;"><button type="button" onclick="showEditJawaban(\'<?= $j->id_jawaban;?>\')" class="btn btn-outline-primary"><span class="oi oi-pencil" title="pencil" aria-hidden="true"></span></button>' +
                        '<button type="button" class="btn btn-outline-danger" style="margin-left:12px;" onclick="showHapusJawaban(\'<?= $j->id_jawaban;?>\')"><span class="oi oi-trash" title="trash" aria-hidden="true"></span></button></div>' +
                        '<div class="col-md-12" id="edit<?= $j->id_jawaban;?>"></div>';
                    $('#jawaban<?=$j->id_jawaban;?>').append(html);
                }
                if (total == <?= $banyakpenjawab;?>) {
                    $('.loader').hide();
                    $('.container').show();
                    <?php if($this->input->get('id_jawab')){?>
                    $('html, body').animate({
                        scrollTop: $("#jawaban<?=$this->input->get('id_jawab')?>").offset().top
                    }, 1000);
                    <?php }?>
                }
            }
        });
        <?php }?>

        $.ajax({
            type: "POST",
            url: "<?= base_url();?>index.php/Tanyajawab/getStatusNotif",
            data: {
                id_tanya: id_tanya,
                id_user: user_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.action == "nyala") {
                    $('#notif').addClass('btn-info');
                    $('#notif').removeClass('btn-outline-info');
                } else if (response.action == "mati") {
                    $('#notif').removeClass('btn-info');
                    $('#notif').addClass('btn-outline-info');
                }
            }
        });
    }

    liff.init(data => {
        liff.getProfile().then((profile) => {
            if (data.context.userId == id_penanya) {
                var html =
                    '<a href="<?=base_url()?>index.php/Tanyajawab/Tanya?id_tanya=<?=$id_tanya?>"><button type="button" class="btn btn-outline-primary"><span class="oi oi-pencil" title="pencil" aria-hidden="true"></span></button></a>' +
                    '<button type="button" class="btn btn-outline-danger" style="margin-left:12px;" onclick="showHapusThread()"><span class="oi oi-trash" title="trash" aria-hidden="true"></span></button>';
                $('#button-action-pertanyaan').html(html);
                $('#penyelesaian').after(
                    '<button type="button" class="btn btn-outline-danger btn-selesai" onclick="hapusPenyelesaian()">Batalkan sebagai penyelesaian</button>'
                );
            } else {
                $('.btn-selesai').remove();
                var html =
                    '<button type="button" class="btn btn-outline-warning" onclick="showLaporThread()"><span class="oi oi-warning"' +
                    'title="warning" aria-hidden="true"></span></button>';
                $('#button-action-pertanyaan').html(html);
            }

            $.ajax({
                type: 'POST',
                data: {
                    user_id: data.context.userId,
                    id_tanya: <?= $id_tanya;?>
                },
                url: '<?= base_url()?>index.php/Tanyajawab/SudahTanya',
                dataType: 'json',
                success: function (data) {
                    laporkanpertanyaan = data.sudah_tanya;
                }
            });
            user_id = data.context.userId;
            pelapor = profile.displayName;
            inisialisasi();
        });
    });

    $('#kirim').click(function () {
        if (!$('#text_jawaban').val()) {
            $('#snackbar').html('Form harus diisi semua');
            $('#snackbar').addClass("show");
            setTimeout(function () {
                $('#snackbar').removeClass("show");
            }, 3000);
            return;
        } else {
            $.ajax({
                type: 'POST',
                data: {
                    id_tanya: id_tanya,
                    user_id: user_id,
                    jawaban: $('#text_jawaban').val()
                },
                url: '<?= base_url()?>index.php/Webhook/Jawab',
                dataType: 'json',
                success: function (data) {

                    var html = '<hr>' +
                        '<div id="jawaban' + data.id_jawaban + '" class="row">' +
                        '<div class="col-md-12">' +
                        '<div id="nama' + data.id_jawaban + '">Jawaban dari : ' + pelapor +
                        '</div><p id="textjwb' + data.id_jawaban + '" class="textjwb">' + $(
                            '#text_jawaban').val() + '</p></div><div id="div_penyelesaian' + data.id_jawaban +
                        '">';

                    if (user_id == id_penanya) {
                        html += '<div class="col-md-12" id="div-button' + data.id_jawaban + '">' +
                            '<button type="button" class="btn btn-outline-primary btn-selesai" onclick="setJawaban(\'' +
                            data.id_jawaban + '\')">Jadikan sebagai penyelesaian</button>' +
                            '</div>';
                    }
                    html +=
                        '</div><div class="col-md-12" style="margin:12px 0;"><button type="button" class="btn btn-outline-primary" onclick="showEditJawaban(\'' +
                        data.id_jawaban +
                        '\')"><span class="oi oi-pencil" title="pencil" aria-hidden="true"></span></button>' +
                        '<button type="button" onclick="showHapusJawaban(\'' + data.id_jawaban +
                        '\')" class="btn btn-outline-danger" style="margin-left:12px;"><span class="oi oi-trash" title="trash" aria-hidden="true"></span></button></div></div>';
                    $('#snackbar').html('Jawaban terkirim');
                    $('#snackbar').addClass("show");
                    $('#jawaban').append(html);
                    $('#text_jawaban').val("");
                    setTimeout(function () {
                        $('#snackbar').removeClass("show");
                    }, 3000);
                }
            });
        }
    });

    function LaporkanThread() {
        if (!laporkanpertanyaan) {
            $.ajax({
                type: 'POST',
                data: {
                    pelapor: pelapor,
                    user_id: user_id,
                    alasan: $('#alasan').val(),
                    id_tanya: id_tanya
                },
                url: '<?= base_url()?>index.php/webhook/LaporkanSpam',
                success: function () {
                    $('#myModal').hide();
                    laporkanpertanyaan = true;
                    $('#snackbar').html('Laporan telah terkirim');
                    $('#snackbar').addClass("show");
                    setTimeout(function () {
                        $('#snackbar').removeClass("show");
                    }, 3000);
                }
            });
        } else {
            $('#snackbar').html('Anda pernah melaporkan pertanyaan ini');
            $('#snackbar').addClass("show");
            setTimeout(function () {
                $('#snackbar').removeClass("show");
            }, 3000);
        }

    }

    function showLaporThread() {
        var html =
            '<button type="button" class="btn btn-default border" id="kirim" onclick="LaporkanThread()">Kirim</button>';
        $('#button_lapor').html(html);
        $('#myModal').show();
    }

    function showLaporJawaban(id_jawaban) {
        var html = '<button type="button" class="btn btn-default border"  onclick="LaporkanJawaban(\'' + id_jawaban +
            '\')">Kirim</button>';
        $('#button_lapor').html(html);
        $('#myModal').show();
    }

    function showHapusThread() {
        $('#modalHapusThread').show();
    }

    function showHapusJawaban(id_jawaban) {
        var html = '<button type="button" class="btn btn-outline-success" onclick="hapusJawaban(\'' + id_jawaban +
            '\')">Ya</button>' +
            '<button type="button" class="btn btn-outline-danger" onclick="$(\'#modalHapusJawaban\').hide();" style="margin-left:12px;">Tidak</button>'
        $('#buttonhapusjawaban').html(html);
        $('#modalHapusJawaban').show();
    }

    function hapusJawaban(id_jawaban) {
        $.ajax({
            type: 'POST',
            data: {
                id_jawab: id_jawaban
            },
            url: '<?=base_url()?>index.php/Tanyajawab/hapusJawaban',
            success: function () {
                $('#modalHapusJawaban').hide();
                $('#jawaban' + id_jawaban).remove();
                $('#snackbar').html('Jawaban berhasil dihapus');
                $('#snackbar').addClass("show");
                setTimeout(function () {
                    $('#snackbar').removeClass("show");
                }, 3000);
            }
        });

    }

    function LaporkanJawaban(id_jawaban) {
        $.ajax({
            type: 'POST',
            data: {
                pelapor: pelapor,
                user_id: user_id,
                alasan: $('#alasan').val(),
                id_jawab: id_jawaban,
                id_tanya: id_tanya
            },
            url: '<?= base_url()?>index.php/webhook/LaporkanJawaban',
            success: function () {
                $('#myModal').hide();
                $('#snackbar').html('Laporan telah terkirim');
                $('#snackbar').addClass("show");
                setTimeout(function () {
                    $('#snackbar').removeClass("show");
                }, 3000);
            }
        });
    }

    function showEditJawaban(id) {
        if ($('#edit' + id).html()) {
            $('#edit' + id).html('');
        } else {
            $.ajax({
                type: 'POST',
                data: {
                    id_jawaban: id
                },
                url: '<?= base_url()?>index.php/Tanyajawab/getJawaban',
                dataType: 'json',
                success: function (data) {
                    var html = '<div class="form-group">' +
                        '<label>Edit jawaban anda di sini : </label>' +
                        '<textarea class="form-control" rows="10" id="text_jawaban' + id + '">' + data.jawaban +
                        '</textarea></div>' +
                        '<button type="button" class="btn btn-default border" onclick="editJawaban(\'' + id +
                        '\')">Edit Jawaban</button>';
                    $('#edit' + id).html(html);
                }
            });
        }
    }

    function editJawaban(id) {
        $.ajax({
            type: 'POST',
            data: {
                id_jawaban: id,
                jawaban: $('#text_jawaban' + id).val()
            },
            url: '<?= base_url()?>index.php/Tanyajawab/editJawaban',
            success: function () {
                $('#textjwb' + id).text($('#text_jawaban' + id).val());
                $('#edit' + id).html('');
                $('#snackbar').html('Jawaban anda berhasil diedit');
                $('#snackbar').addClass("show");
                setTimeout(function () {
                    $('#snackbar').removeClass("show");
                }, 3000);
            }
        });
    }

    function hapusThread(id) {
        $.ajax({
            type: 'POST',
            data: {
                
            },
            url: '<?= base_url()?>index.php/Webhook/HapusPertanyaan/'+id,
            success: function () {
                liff.closeWindow();
            }
        });
    }

    function setJawaban(id_jawaban) {
        $.ajax({
            type: 'POST',
            data: {
                id_tanya: id_tanya,
                id_jawaban: id_jawaban
            },
            url: '<?= base_url()?>index.php/Tanyajawab/setPenyelesaian',
            success: function () {
                var html =
                    '<button type="button" class="btn btn-outline-success" disabled="" id="penyelesaian">Penyelesaian</button>';
                html +=
                    '<button type="button" class="btn btn-outline-danger btn-selesai" onclick="hapusPenyelesaian()">Batalkan sebagai penyelesaian</button>';
                if (penyelesaian != null) {
                    $('#div_penyelesaian' + penyelesaian).html(
                        '<button type="button" class="btn btn-outline-primary btn-selesai" onclick="setJawaban(' +
                        penyelesaian + ')">Jadikan sebagai penyelesaian</button>');
                }
                penyelesaian = id_jawaban;
                $('#div_penyelesaian' + id_jawaban).html(html);
            }
        });
    }

    function hapusPenyelesaian() {
        $.ajax({
            type: "POST",
            url: "<?= base_url()?>index.php/Tanyajawab/hapusPenyelesaian",
            data: {
                id_tanya: id_tanya
            },
            success: function () {
                $('#div_penyelesaian' + penyelesaian).html(
                    '<button type="button" class="btn btn-outline-primary btn-selesai" onclick="setJawaban(' +
                    penyelesaian + ')">Jadikan sebagai penyelesaian</button>');
                penyelesaian = null;
            }
        });
    }

    function setNotif() {
        $.ajax({
            type: "POST",
            url: "<?= base_url();?>index.php/Tanyajawab/setNotif",
            data: {
                id_tanya: id_tanya,
                id_user: user_id
            },
            dataType: "json",
            success: function (response) {
                if (response.action == "nyala") {
                    $('#snackbar').html('Notifikasi dinyalakan');
                    $('#snackbar').addClass("show");
                    setTimeout(function () {
                        $('#snackbar').removeClass("show");
                    }, 3000);
                    $('#notif').addClass('btn-info');
                    $('#notif').removeClass('btn-outline-info');
                } else if (response.action == "hapus") {
                    $('#snackbar').html('Notifikasi dimatikan');
                    $('#snackbar').addClass("show");
                    setTimeout(function () {
                        $('#snackbar').removeClass("show");
                    }, 3000);
                    $('#notif').removeClass('btn-info');
                    $('#notif').addClass('btn-outline-info');
                }
                $('#notif').blur();
            }
        });
    }
</script>

</html>