<!DOCTYPE html>
<html>
<head>
    <title>Midtrans Simple Checkout</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>
    <h2>Bayar Rp10.000</h2>
    <button id="pay-button">Bayar Sekarang</button>

    <script>
        document.getElementById('pay-button').addEventListener('click', function () {
            fetch('/snap-token')
                .then(res => res.json())
                .then(data => {
                    snap.pay(data.token, {
                        onSuccess: function(result) {
                            alert("Pembayaran berhasil!");
                            console.log(result);
                        },
                        onPending: function(result) {
                            alert("Menunggu pembayaran.");
                            console.log(result);
                        },
                        onError: function(result) {
                            alert("Pembayaran gagal!");
                            console.log(result);
                        }
                    });
                });
        });
    </script>
</body>
</html>
