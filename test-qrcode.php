<?php
require __DIR__ . '/config/phpqrcode/qrlib.php';
QRcode::png("HELLO BRO DIKA", "qrcode/test.png", 6, 4);
echo "✅ QR berhasil disimpan ke qrcode/test.png";
