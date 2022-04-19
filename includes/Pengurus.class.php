<?php

class Pengurus extends DB
{
    function __construct($db_host='', $db_user='', $db_password='', $db_name='')
	{
		$this->DB($db_host, $db_user, $db_password, $db_name);
	}
    
    function getpengurus()
    {
        $query = "SELECT * FROM pengurus";
        return $this->execute($query);
    }

    function getDetail($id)
    {
        $query = "SELECT * FROM pengurus WHERE id=$id";
        return $this->execute($query);
    }

    function getPengurusBidang($id)
    {
        $query = "SELECT * FROM pengurus WHERE id_bidang=$id";
        return $this->execute($query);
    }

    function getJabatan($id)
    {
        $query = "SELECT * FROM bidang_divisi WHERE id_bidang=$id";
        return $this->execute($query);
    }

    function getSelect($id)
    {
        $query = "SELECT * FROM bidang_divisi WHERE id_divisi=$id";
        return $this->execute($query);
    }

    function addRecord()
    {
        $nim      = htmlspecialchars($_POST["nim"]);
        $nama     = htmlspecialchars($_POST["nama"]);
        $semester = ($_POST["semester"] != "") ? $_POST["semester"] : "NULL";
        $bidang   = $_POST["bidang"];
        $image    = $this->upload();

        $query = "INSERT INTO pengurus VALUES (NULL, '$nim', '$nama', $semester, $bidang, '$image')";
        return $this->execute($query);
    }

    function upload()
    {
        $fileName = $_FILES["image"]["name"];
        $fileTemp = $_FILES["image"]["tmp_name"];
        $fileType = explode(".", $fileName);
        $fileType = strtolower(end($fileType));
        $validExt = ["jpg", "jpeg", "png"];

        if(!in_array($fileType, $validExt)) return "anonymous.jpg";

        $fileImage = uniqid() . "." . $fileType;
        move_uploaded_file($fileTemp, "img/".$fileImage);

        return $fileImage;
    }

    function updateRecord($id)
    {
        $nim      = htmlspecialchars($_POST["nim"]);
        $nama     = htmlspecialchars($_POST["nama"]);
        $semester = ($_POST["semester"] != "") ? $_POST["semester"] : "NULL";
        $bidang   = $_POST["bidang"];
        $image    = $this->upload();

        $query = "UPDATE pengurus SET nim='$nim', nama='$nama', semester=$semester, id_bidang=$bidang";
        if($image != "anonymous.jpg") $query .= ", image='$image'";
        $query .= " WHERE id=$id";

        return $this->execute($query);
    }

    function delete($id)
    {
        $query = "DELETE FROM pengurus WHERE id=$id";

        $this->getDetail($id);
        $file = $this->getResult()[0]["image"];
        if($file != "") unlink("img/".$file);
        
        return $this->execute($query);
    }

    function getDivisiAll()
    {
        $query = "SELECT * FROM divisi";
        return $this->execute($query);
    }

    function getDivisi($id)
    {
        $query = "SELECT * FROM divisi WHERE id_divisi=$id";
        return $this->execute($query);
    }
}

?>