<!DOCTYPE html>
<html>
<head>
    <title>Cek Status Pembayaran</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<div class="container">
    <h3 class="mb-4">Cek Status Pembayaran Midtrans</h3>

    <form id="status-form" class="mb-3">
        <div class="input-group">
            <input type="text" id="order_id" class="form-control" placeholder="Masukkan Order ID" required>
            <button type="submit" class="btn btn-primary">Cek Status</button>
        </div>
    </form>

    <div id="result" style="display: none;">
        <h5>Hasil:</h5>
        <ul class="list-group">
            <li class="list-group-item"><strong>Order ID:</strong> <span id="res-order"></span></li>
            <li class="list-group-item"><strong>Status Transaksi:</strong> <span id="res-status"></span></li>
            <li class="list-group-item"><strong>Fraud Status:</strong> <span id="res-fraud"></span></li>
            <li class="list-group-item"><strong>Metode Pembayaran:</strong> <span id="res-payment"></span></li>
            <li class="list-group-item"><strong>Pesan:</strong> <span id="res-message"></span></li>
        </ul>
    </div>
</div>

<script>
    document.getElementById('status-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const orderId = document.getElementById('order_id').value;

        fetch(`/cek-status/${orderId}`)
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                document.getElementById('res-order').innerText = data.order_id;
                document.getElementById('res-status').innerText = data.status;
                document.getElementById('res-fraud').innerText = data.fraud || '-';
                document.getElementById('res-payment').innerText = data.payment_type || '-';
                document.getElementById('res-message').innerText = data.message;

                document.getElementById('result').style.display = 'block';
            })
            .catch(err => {
                alert('Gagal mengambil status: ' + err.message);
            });
    });
</script>

</body>
</html>
