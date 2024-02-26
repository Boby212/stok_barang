<?php 
session_start();

//membuat koneksi ke database
$conn = mysqli_connect("localhost","root","","stok_barang");

//tambah barang
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    $addtotable = mysqli_query($conn, "insert into stok (namabarang, deskripsi, stok) values('$namabarang', '$deskripsi', '$stok')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
    }
};

//menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barang = $_POST['barang'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstoksekarang = mysqli_query($conn,"select * from stok where idbarang='$barang'");
    $ambildata = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildata['stok'];
    $tambahStokSekarangDenganQuantity = $stoksekarang+$qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk(idbarang, keterangan, qty) values('$barang','$penerima', '$qty')");
    $updatestokmasuk = mysqli_query($conn,"update stok set stok='$tambahStokSekarangDenganQuantity' where idbarang='$barang'");
    if($addtokeluar&&$updatestokmasuk){
        header('location:masuk.php');
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}

//barang keluar
if(isset($_POST['addbarangkeluar'])){
    $barang = $_POST['barang'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstoksekarang = mysqli_query($conn,"select * from stok where idbarang='$barang'");
    $ambildata = mysqli_fetch_array($cekstoksekarang);

    $stoksekarang = $ambildata['stok'];
    $tambahStokSekarangDenganQuantity = $stoksekarang-$qty;

    $addtokeluar = mysqli_query($conn,"insert into keluar(idbarang, penerima, qty) values('$barang','$penerima', '$qty')");
    $updatestokmasuk = mysqli_query($conn,"update stok set stok='$tambahStokSekarangDenganQuantity' where idbarang='$barang'");
    if($addtokeluar&&$updatestokmasuk){
        header('location:keluar.php');
    } else {
        echo 'Gagal';
        header('location:keluar.php');
    }
}

//update barang
if(isset($_POST['updatebarang'])){
    $idb =$_POST['idb'];
    $namabarang =$_POST['namabarang'];
    $deskripsi =$_POST['deskripsi'];

    $update = mysqli_query($conn, "update stok set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang='$idb'");
    if($update){
        header('location:index.php');
    } else {
        echo 'Gagal';
        header('location:index.php');
}
}

//hapus barang
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "delete from stok where idbarang='$idb'" );
    if($hapus){
        header(('localhost:index.php'));
    } else {
        echo 'Gagal';
        header('localhost:index.php');
    }
}

//edit data barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstok = mysqli_query($conn, "select * from stok where idbarang='$idb'");
    $stok = mysqli_fetch_array($lihatstok);
    $stoksekarang = $stok['stok'];

    $qtysekarang = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtysekarang);
    $qtysekarang = $qtynya['qty'];

    if($qty>$qtysekarang){
        $selisih = $qty - $qtysekarang;
        $kurangi = $stoksekarang + $selisih;
        $kurangistoknya = mysqli_query($conn, "update stok set stok='$kurangi' where idbarang='$idb'");
        $update = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
        if($kurangistoknya&&$update){

        header(('localhost:masuk.php'));
    } else {
        echo 'Gagal';
        header('localhost:masuk.php');
    }
} else {
    $selisih = $qtysekarang - $qty;
    $kurangi = $stoksekarang - $selisih;
    $kurangistoknya = mysqli_query($conn, "update stok set stok='$kurangi' where idbarang='$idb'");
    $update = mysqli_query($conn, "update masuk set qty='$qty', keterangan='$deskripsi' where idmasuk='$idm'");
    if($kurangistoknya&&$update){

    header(('localhost:masuk.php'));
} else {
    echo 'Gagal';
    header('localhost:masuk.php');
        }
    }
}

//Hapus barang masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastok = mysqli_query($conn, "select * from stok where idbarang='$idb'" );
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    $selisih = $stok-$qty;

    $update = mysqli_query($conn, "update stok set stok='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from masuk where idmasuk='$idm'");

    if($update&&$hapusdata){
        header('location:masuk.php');
    } else {
        header('location:masuk.php');
    }
}

//edit data barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstok = mysqli_query($conn, "select * from stok where idbarang='$idb'");
    $stok = mysqli_fetch_array($lihatstok);
    $stoksekarang = $stok['stok'];

    $qtysekarang = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtysekarang);
    $qtysekarang = $qtynya['qty'];

    if($qty>$qtysekarang){
        $selisih = $qty - $qtysekarang;
        $kurangi = $stoksekarang - $selisih;
        $kurangistoknya = mysqli_query($conn, "update stok set stok='$kurangi' where idbarang='$idb'");
        $update = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
        if($kurangistoknya&&$update){

        header(('localhost:keluar.php'));
    } else {
        echo 'Gagal';
        header('localhost:keluar.php');
    }
} else {
    $selisih = $qtysekarang - $qty;
    $kurangi = $stoksekarang + $selisih;
    $kurangistoknya = mysqli_query($conn, "update stok set stok='$kurangi' where idbarang='$idb'");
    $update = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
    if($kurangistoknya&&$update){

    header(('localhost:keluar.php'));
} else {
    echo 'Gagal';
    header('localhost:keluar.php');
        }
    }
}

//Hapus barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastok = mysqli_query($conn, "select * from stok where idbarang='$idb'" );
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];

    $selisih = $stok+$qty;

    $update = mysqli_query($conn, "update stok set stok='$selisih' where idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "delete from keluar where idkeluar='$idk'");

    if($update&&$hapusdata){
        header('location:keluar.php');
    } else {
        header('location:keluar.php');
    }
}

?>