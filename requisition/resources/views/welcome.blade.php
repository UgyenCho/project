<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JNEC Requisition Management System</title>

    <!-- Add Font Awesome for the icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            background-color: white; /* Changed from #f4f4f4 */
            color: black; /* Ensure text is readable on white */
        }

        /* Header Styling */
        .main-header {
            background-color: #ffffff;
            padding: 10px 0; /* Adjust padding if needed for larger logo */
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .main-nav {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Vertically center items in nav */
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .logo-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: inherit;
        }

        /* Logo Size */
        .logo-image {
            height: 90px; /* Adjust as needed */
            width: auto;  /* Ensure width adjusts proportionally */
            display: block;
        }

        .nav-items-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-link.login-link {
            text-decoration: none;
            color: #333; /* Ensure good contrast */
            font-weight: bold;
            text-transform: uppercase;
            padding: 8px 12px;
            transition: color 0.3s ease, background-color 0.3s ease; /* Added background-color transition */
            border-radius: 4px;
        }

        .nav-link.login-link:hover {
            color: #0056b3;
            background-color: #f0f0f0;
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px 10px;
            font-size: 1.3em;
            color: #555;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease, background-color 0.3s ease; /* Added background-color transition */
            border-radius: 50%;
        }

        .dropdown-toggle:hover {
            color: #0056b3;
            background-color: #f0f0f0;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #ffffff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            border: 1px solid #e0e0e0;
            z-index: 1001;
            border-radius: 4px;
            margin-top: 8px;
            padding: 5px 0;
            overflow: hidden; /* Ensures items stay within rounded corners */
        }

        .dropdown-menu a.dropdown-item {
            color: #333;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
            font-size: 0.95em;
            font-weight: normal;
            text-transform: none;
            white-space: nowrap;
            transition: background-color 0.2s ease, color 0.2s ease; /* Added transitions */
        }

        .dropdown-menu a.dropdown-item:hover {
            background-color: #007bff;
            color: #ffffff;
        }

        /* Show dropdown on hover */
        .dropdown:hover .dropdown-menu {
            display: block;
        }

        /* Decorative Section */
        .decorative-section {
            background-color:rgb(74, 131, 102); /* Set your desired color */
            height: 150px;
            width: 100%;
            /* Optional gradient overlay */
            background-image: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0));
        }

        /* Main Content Area */
        main {
            padding: 30px 20px;
            max-width: 1200px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }

        main h1 {
            text-align: center;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        main p {
            margin-bottom: 15px;
            color: #555;
        }

        hr {
            border: 0;
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }

        /* Team Section Styling */
        .team-section {
            margin-top: 40px;
            padding-top: 20px;
        }

        .team-section h2 {
            text-align: center;
            margin-bottom: 35px;
            color: #2c3e50;
            font-weight: 600;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 30px;
            justify-content: center;
        }

        .member-card {
            background-color: #fdfdfd;
            border: 1px solid #e7e7e7;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.06);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex; /* Added for alignment if needed */
            flex-direction: column; /* Stack content vertically */
            align-items: center; /* Center items horizontally */
        }

        .member-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .member-photo {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover; /* Ensures image covers space well */
            margin-bottom: 20px;
            border: 4px solid #fff; /* Optional white border */
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
            background-color: #e0e0e0; /* Placeholder background */
        }

        .member-info {
             /* Removed margin-top, padding in card handles spacing */
             width: 100%; /* Ensure info takes width */
        }

        .member-name {
            margin-bottom: 8px;
            font-size: 1.25em;
            color: #333;
            font-weight: 600;
        }

        .member-role {
            font-size: 1em;
            color: #007bff; /* Blue color for role */
            margin-bottom: 10px;
            font-style: normal; /* Removed italics */
        }

        .member-id {
            font-size: 0.9em;
            color: #777;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

    <header class="main-header">
        <nav class="main-nav">
                {{-- Make sure image path is correct --}}
                <img src="{{ asset('images/1.png') }}" alt="JNEC RMS Logo" class="logo-image">
            </a>
            <div class="nav-items-right">
                @if (Route::has('login'))
                    @auth
                        {{-- Link for logged-in users --}}
                        <a href="{{ url('/dashboard') }}" class="nav-link login-link">Dashboard</a>
                    @else
                        {{-- Link for guests - Login ONLY --}}
                        <a href="{{ route('login') }}" class="nav-link login-link">Login</a>
                        {{-- Register link block was removed --}}
                    @endauth
                @endif

                {{-- Dropdown Menu --}}
                <div class="dropdown">
                    <button class="dropdown-toggle" aria-label="View Menu">
                        <i class="fas fa-bars"></i> {{-- Hamburger icon --}}
                    </button>
                    <div class="dropdown-menu">
                         {{-- Ensure these links point to correct routes/files --}}
                        <a href="/w" class="dropdown-item">Home</a>
                        <a href="{{ route('about.page') }}" class="dropdown-item">About Us</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <section class="decorative-section"></section>

    <main>
        <h1>Welcome to the JNEC Requisition Management System</h1>
        <p>This system is designed to streamline the process of managing requisitions within Jigme Namgyel Engineering College. You can navigate using the menu options above.</p>

        <hr>

        <section class="team-section">
            <h2>Project Team Members</h2>
            <div class="team-grid">
                <!-- Team Member 1 -->
                <div class="member-card">
                     {{-- Ensure image path is correct --}}
                    <img src="{{ asset('images/3.jpg') }}" alt="Photo of Singye Lhadon" class="member-photo">
                    <div class="member-info">
                        <h3 class="member-name">Singye Lhadon</h3>
                        <p class="member-role">Team Lead / Backend Developer</p>
                        <p class="member-id">ID: 05230132</p>
                    </div>
                </div>

                <!-- Team Member 2 -->
                <div class="member-card">
                     {{-- Ensure image path is correct --}}
                    <img src="{{ asset('images/1.jpg') }}" alt="Photo of Ugyen Choden" class="member-photo">
                    <div class="member-info">
                        <h3 class="member-name">Ugyen Choden</h3>
                        <p class="member-role">Frontend Developer</p>
                        <p class="member-id">ID: 05230140</p>
                    </div>
                </div>

                 <!-- Team Member 3 -->
                <div class="member-card">
                     {{-- Ensure image path is correct --}}
                    <img src="{{ asset('images/2.jpg') }}" alt="Photo of Sonam Lhamo" class="member-photo">
                    <div class="member-info">
                        <h3 class="member-name">Sonam Lhamo</h3>
                        <p class="member-role">UI Design</p>
                        <p class="member-id">ID: 05230134</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>