<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Property Tag — {{ $qr->reference_number }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f1f5f9; }
        @media print {
            body { background-color: #ffffff; }
            .no-print { display: none !important; }
            .print-card { border: 2px solid #000000 !important; box-shadow: none !important; margin: 0 !important; }
        }
    </style>
</head>
<body class="p-6 flex flex-col items-center justify-center min-h-screen">

    <!-- Action bar -->
    <div class="no-print mb-6 flex items-center gap-3">
        <button onclick="window.print()" class="px-5 py-2.5 bg-purple-900 hover:bg-purple-800 text-white font-extrabold rounded-xl text-xs shadow-lg transition-all flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-print"></i> Print Physical Tag
        </button>
        <button onclick="window.close()" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold rounded-xl text-xs transition-all cursor-pointer">
            Close Window
        </button>
    </div>

    <!-- Printable Property Tag Card -->
    <div class="print-card w-full max-w-sm bg-white border-2 border-purple-900 rounded-3xl p-6 shadow-2xl text-center space-y-4 relative">
        
        <!-- Header -->
        <div class="flex items-center justify-center gap-3 border-b-2 border-purple-900 pb-3">
            <img src="{{ asset('images/sjut-logo.png') }}" alt="SJUT Logo" class="h-10 w-auto">
            <div class="text-left">
                <h1 class="text-xs font-extrabold text-purple-950 uppercase leading-tight">St. John's University</h1>
                <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-widest block">Lost & Found Property Tag</span>
            </div>
        </div>

        <div>
            <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-widest block">Property Reference #</span>
            <span class="text-lg font-extrabold font-mono text-purple-950 block tracking-wider">{{ $qr->reference_number }}</span>
        </div>

        <!-- Rendered QR Code -->
        <div class="flex flex-col items-center justify-center p-3 bg-slate-50 border border-slate-200 rounded-2xl">
            <div id="qrcode-print" class="p-2 bg-white rounded-xl shadow-inner"></div>
            <span class="text-[9px] text-slate-500 mt-2 font-semibold">Scan with camera to identify or report item</span>
        </div>

        <!-- Safe Info Box -->
        <div class="text-left bg-purple-50/60 p-3 rounded-xl border border-purple-200 space-y-1.5 text-xs">
            <div class="flex justify-between items-center">
                <span class="font-bold text-slate-600 text-[10px] uppercase">Item Name:</span>
                <span class="font-extrabold text-purple-950">{{ $item->item_name }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="font-bold text-slate-600 text-[10px] uppercase">Category:</span>
                <span class="font-bold text-slate-800">{{ $item->category->category_name ?? 'General' }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="font-bold text-slate-600 text-[10px] uppercase">Storage:</span>
                <span class="font-bold text-slate-800">{{ $item->storage_location ?? 'Security Office Desk' }}</span>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="border-t border-slate-200 pt-3 text-[10px] text-slate-500 space-y-0.5">
            <p class="font-bold text-purple-900 uppercase">SJUT Central Security Office</p>
            <p>Main Campus, Dodoma, Tanzania • Phone: +255 26 239 0044</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new QRCode(document.getElementById("qrcode-print"), {
                text: "{{ route('qr.scan', $qr->qr_token) }}",
                width: 140,
                height: 140,
                colorDark : "#3b0764",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        });
    </script>
</body>
</html>
