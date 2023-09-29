<?php
function Ribuan($angka)
{
    $hasil_rupiah = number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= base_url('assets/css/paper.css'); ?>">
    <title>Print Penjualan <?= $faktur; ?></title>
    <style>
        @page {
            size: A4
        }
        html,
        body {
            margin: 0;
            padding: 10px;
            font-family: "Calibri";
        }
        .sheet {
            overflow: visible;
            height: auto !important;
        }
        @media print {
            @page:first {
                margin-left: 0px;
                margin-right: 0px;
                margin-top: 0px;
                margin-bottom: 50px;
            }
            @page {
                margin-left: 0px;
                margin-right: 0px;
                margin-top: 50px;
                margin-bottom: 0px;
            }
            html,
            body {
                margin: 0;
                padding: 10px;
                font-family: Arial, Helvetica, sans-serif;
            }
            #printContainer {
                margin: left;
                padding: 10px;
                text-align: justify;
                font-size: 100%;
            }
            .sheet {
                overflow: visible;
                height: auto !important;
            } 
        }
        .Division{
                float: left;
                width: 200px;
                height: 200px;
                border: 2px solid #000000;
                margin-left: 100px;
            }
        table, th, td{
            border-collapse: collapse;
            
        }
        th, td{
            padding: 5px;
        }
        th{
            text-align: left;
        }
        .flex-container {
            display: flex;
        }
        .flex-child {
            flex: 1;
            border: 2px solid yellow;
        }  
        .flex-child:first-child {
            margin-right: 20px;
        }      
    </style>
</head>

<body class="A4" onLoad="javascript:window.print();">
    <section class="sheet padding-10mm">
        <div id="printContainer"></div>
            <div style="line-height: normal;">
                <!-- <div style="text-align:center;margin-bottom: 10px;margin-top: 20px;">
                    <img src="<?= base_url() . $logo; ?>" width="50" height="50" alt="Logo">
                </div> -->
                <!-- <div style="text-align:center;margin-bottom: 10px;"><strong><?= $toko['nama_toko']; ?></strong></div>
                <?php if ($toko['NIB'] != 0) : ?><div style="text-align:center;font-size: 14px;">NIB: <?= $toko['NIB']; ?></div><?php endif; ?>
                <div style="text-align:center;font-size: 14px;"><?= $toko['alamat_toko']; ?></div>
                <div style="text-align:center;font-size: 14px;">Telp/WA: <?= $toko['telp']; ?></div> -->
                <!-- <hr /> -->
                <div class="division" style="float: left; text-align:left;font-size: 16px;">
                    INVOICE PENJUALAN
                </div>
                <br />
                <br />

              <div class="flex-container">
                    <div class="flex-child magenta">
                        PT. <?= $toko['nama_toko']; ?><br />
                        <?= $toko['alamat_toko']; ?><br />
                        Telp : <?= $toko['telp']; ?><br />
                        Fax : <?= $toko['telp']; ?><br />
                        Tanggal Kirim : <?= dayshortdate_indo(date('Y-m-d', strtotime($penjualan['created_at']))) . ' ' . date('H:i', strtotime($penjualan['created_at'])); ?><br /><br />
                    </div>
                    <div class="flex-child green">
                    <table >
                        <tbody>
                        <tr><td>No Transaksi</td><td>:</td><td><?= $penjualan['faktur']; ?></td></tr>
                        <tr><td>Tanggal</td><td>:</td><td><?= dayshortdate_indo(date('Y-m-d', strtotime($penjualan['created_at']))) . ' ' . date('H:i', strtotime($penjualan['created_at'])); ?></td></tr>
                        <tr><td>Sales</td><td>:</td><td></td></tr>
                        <tr><td>Pelanggan</td><td>:</td><td><?= $penjualan['nama_kontak']; ?></td></tr>
                        <tr><td>Alamat</td><td>:</td><td></td></tr>
                        <tr><td>Telepon</td><td>:</td><td></td></tr>
                        </tbody>
                    </table>
                </div>

                </div>
                <hr />
    
                <hr />
                <table style="width:100%">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Item</th>
                            <th>Keterangan</th>
                            <th>Qty Satuan</th>
                            <th>Harga</th>
                            <th>Disc % </th>
                            <th>Harga Disk</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                <?php foreach ($item as $item) { ?>
                <tbody>
                    <tr>
                        <td><?= $item->qty ?></td>
                        <td></td>
                        <td><?= $item->nama_barang; ?></td>
                        <td><?= $item->satuan ?></td>
                        <td><?= $item->harga_jual ?></td>
                        <td><?= $item->diskon_persen?></td>
                        <td><?= $item->diskon?></td>
                        <td><?= Ribuan($item->jumlah) ?></td>
                    </tr>
                </tbody>
                <?php } ?>
                </table>
                <hr />
                <!-- Berhenti -->
                <div style="float: left;">
                    <span style="font-size: 16px;line-height: 1.5">Keterangan : </span>
                    <table style="text-align: center;font-size: 16px;">
                        <tbody>
                            <tr>
                                <td width="200">Hormat Kami
                                    <br />
                                    <br />
                                    <br />
                                    <br />
                                    (.......................)
                                </td>

                                <td width="200">Penerima
                                    <br />
                                    <br />
                                    <br />
                                    <br />
                                    (.......................)
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="text-align: right;font-size: 16px;">
                    Subtotal (<?= $penjualan['jumlah']; ?> item): <?= Ribuan($penjualan['subtotal']) ?><br />
                    PPN <?= $penjualan['PPN'] ?>%: <?= Ribuan($penjualan['pajak']) ?><br />
                    Diskon <?= $penjualan['diskon_persen'] ?>%: <?= Ribuan($penjualan['diskon']) ?><br />
                    <?php if($toko['pembulatan'] == 1) : ?>
                        Pembulatan: <?= Ribuan($penjualan['pembulatan']) ?><br />
                    <?php endif ?>
                    <strong>Total: <?= Ribuan($penjualan['total']) ?></strong><br />
                    Bayar: <?= Ribuan($penjualan['bayar']) ?><br />
                    <?php if ($penjualan['kembali'] >= 0) { ?>
                        Kembali: <?= Ribuan($penjualan['kembali']) ?><br />
                    <?php } else { ?>
                        Kurang: <?= Ribuan($penjualan['kembali']) ?><br />
                    <?php } ?>
                </div>
                <br />
                <br />
                <div style="text-align:center;font-size: 12px;">
                    <?= $toko['footer_nota']; ?>. Dicetak menggunakan Aplikasi <?= $appname ?> by <?= $companyname ?>
                </div>
            </div>
        </div>
    </section>
</body>

</html>