<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .store-item { padding: 15px; margin-bottom: 10px; background: #f9f9f9; border-radius: 5px; }
        .store-name { font-weight: bold; font-size: 18px; margin-bottom: 5px; }
        .store-link { color: #0ea5e9; text-decoration: none; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>StockFlow</h1>
            <p>We found the following stores associated with your email address.</p>
        </div>

        @foreach($stores as $store)
            <div class="store-item">
                <div class="store-name">{{ $store['name'] }}</div>
                <a href="{{ $store['url'] }}" class="store-link">{{ $store['url'] }}</a>
            </div>
        @endforeach

        <p>If you didn't request this information, you can safely ignore this email.</p>

        <div class="footer">
            &copy; {{ date('Y') }} StockFlow. All rights reserved.
        </div>
    </div>
</body>
</html>
