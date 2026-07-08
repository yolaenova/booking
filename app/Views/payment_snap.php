<?php

$snapToken = $snapToken ?? '';

$booking = $booking ?? [];

?>
<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<title>Pembayaran</title>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="<?= env('MIDTRANS_CLIENT_KEY')?>"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card shadow">

<div class="card-body text-center">

<h3>Pembayaran Booking</h3>

<p>Total Pembayaran</p>

<h2>

Rp <?= number_format($booking['total_price'],0,',','.') ?>

</h2>

<button
id="pay-button"
class="btn btn-success">

Bayar Sekarang

</button>

</div>

</div>

</div>

<script>

document.getElementById('pay-button').onclick=function(){

snap.pay("<?= $snapToken ?>",{

onSuccess:function(result){

fetch("<?= base_url('payment/success')?>",{

method:"POST",

headers:{

'Content-Type':'application/x-www-form-urlencoded'

},

body:"booking_id=<?= $booking['id']?>"

})

.then(response=>response.json())

.then(data=>{

alert("Pembayaran Berhasil");

window.location="<?= base_url('booking-history')?>";

});

},

onPending:function(result){

alert("Menunggu Pembayaran");

},

onError:function(result){

alert("Pembayaran Gagal");

}

});

}

</script>

</body>
</html>