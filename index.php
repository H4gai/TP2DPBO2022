<?php

include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Pengurus.class.php");

$data_tp2 = new Pengurus($db_host, $db_user, $db_pass, $db_name);
$data_tp2->open();

// get divisi value now
if(isset($_GET["id_divisi"])) $data_tp2->getDivisi($_GET["id_divisi"]);
else $data_tp2->getDivisiAll();
$logo = $data_tp2->getResult()[0];

if(isset($_GET["id_hapus"]))
{
    $data_tp2->delete($_GET["id_hapus"]);
    $data_tp2->close();

    // not from default divisi
    if(isset($_GET["id_divisi"])) header("Location: index.php?id_divisi=" . $_GET['id_divisi']);
    else header("Location: index.php");
    exit;
}
else if(isset($_GET["id"]))
{
    $data_tp2->getDetail($_GET["id"]);
    $result = $data_tp2->getResult();
}
// get data of pengurus divisi
else
{
    $result = array();
    $data_tp2->getSelect($logo["id_divisi"]);
    $select = $data_tp2->getResult();
    foreach($select as $item)
    {
        $data_tp2->getPengurusBidang($item["id_bidang"]);
        $result = array_merge($result, $data_tp2->getResult());
    }
}

$data = null;

foreach ($result as $list) {
    $data_tp2->getJabatan($list["id_bidang"]);
    $bidang = $data_tp2->getResult()[0]["jabatan"];
    if(isset($_GET["id"]))
    {
        $data .= "<div></div>
                <div class='content'>
                    <img src='img/" . $list["image"] . "'>
                    <p class='nama-pengurus'>". $list["nama"] . "</p>
                    <p class='jabatan'>" . $bidang ."</p>
                    <div id='action'>
                        <a href='index.php?ADD_ID_DIVISI'>Back</a>
                        <a href='create.php?ADD_ID_DIVISI&id=" . $list["id"] . "'>Edit</a>
                        <a href='index.php?ADD_ID_DIVISI&id_hapus=" . $list["id"] . "'>Hapus</a>
                    </div>
                </div>";
    }
    else
    {
        $data .= 
            "<a href='index.php?ADD_ID_DIVISI&id=" . $list["id"] . "'>
                    <div class='content'>
                        <img src='img/" . $list["image"] . "'>
                        <p class='nama-pengurus'>". $list["nama"] . "</p>
                        <p class='jabatan'>" . $bidang ."</p>
                    </div>
                </a>";
    }
}

// get link to add anggota reference divisi
$addIdDivisi = (isset($_GET["id_divisi"])) ? "id_divisi=" . $_GET["id_divisi"] : "";

$data_tp2->close();
$tp2 = new Template("templates/index.html");
$tp2->replace("DATA_TABEL", $data);
$tp2->write();
