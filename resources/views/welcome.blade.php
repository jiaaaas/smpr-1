<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMPR - Staff Management System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #F4F7F6;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            background-color: #265073;
            padding: 20px;
            color: white;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            /* box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); */
        }
        .container {
            max-width: 900px;
            margin: auto;
            text-align: center;
            padding: 40px 20px;
            flex-grow: 1;
        }
        .logo {
            max-width: 550px;
            margin: 20px auto;
        }
        .clock {
            font-size: 3rem;
            font-weight: bold;
            color: #265073;
            margin-top: 20px;
        }
        .button-container {
            margin-top: 50px;
        }
        .btn {
            background-color: #265073;
            color: white;
            padding: 14px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            display: inline-block;
            transition: background 0.3s ease, transform 0.2s;
        }
        .btn:hover {
            background-color: #1B3B5F;
            transform: scale(1.05);
        }
        footer {
            background-color: #265073;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    {{-- <header class="header">
        Staff Management System with Performance Report Generator 
    </header> --}}

    <div class="header">
    </div>
    
    <main class="container">
        <h1 class="text-4xl font-bold">Welcome to SMPR</h1>
        <p class="text-lg text-gray-700 mt-2">Staff Management System With Performance Report Generator</p>
        
        <img src="{{ asset('images/logoamtis.jpg') }}" alt="AMTIS SOLUTION" class="logo"> 
        
        <div id="clock" class="clock"></div>
        
        <div class="button-container">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn ml-2">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </main>
    
    {{-- <footer>
        &copy; 2024 AMTIS SOLUTION SDN. BHD. All Rights Reserved.
    </footer> --}}

    <script>
        function updateClock() {
            const now = new Date();
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds} ${ampm}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
