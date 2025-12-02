@php
  $configData = Helper::appClasses();
@endphp

{{-- @extends('layouts/layoutMaster') --}}

{{-- @section('title', 'Login - Toko Kue PAD') --}}

{{-- @section('page-style') --}}
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      background: #f5f5f5;
    }

    .login-container {
      width: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      background: white;
      padding: 2rem;
    }

    .login-form {
      width: 100%;
      max-width: 350px;
    }

    .login-form h2 {
      text-align: center;
      color: #333;
      margin-bottom: 1.5rem;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 600;
      color: #555;
    }

    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s;
      position: relative;
    }

    .form-control:focus {
      outline: none;
      border-color: #FF6B00;
      box-shadow: 0 0 0 2px rgba(255, 107, 0, 0.2);
    }

    .form-control::placeholder {
      color: #999;
    }

    .icon {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #999;
    }

    .btn-login {
      width: 100%;
      padding: 12px;
      background: #FF6B00;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn-login:hover {
      background: #EA580C;
    }

    .error-message {
      color: #e53e3e;
      font-size: 14px;
      text-align: center;
      margin-top: 0.5rem;
    }

    .welcome-container {
      width: 50%;
      background: linear-gradient(to right, #FF6B00, #9C4A00);
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 2rem;
      text-align: center;
    }

    .welcome-container img {
      max-width: 150px;
      margin-bottom: 1.5rem;
    }

    .welcome-container h1 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      font-weight: 700;
    }

    .welcome-container p {
      font-size: 1.1rem;
      opacity: 0.9;
    }
  </style>
{{-- @endsection --}}

{{-- @section('content') --}}
  <div class="login-container">
    <div class="login-form">
      <h2>Halaman Login</h2>

      <form method="POST" action="{{ url('login') }}">
        @csrf

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" value="{{ old('username') }}" required autofocus
            placeholder="Enter your username" class="form-control">
          <i class="fa-solid fa-user icon"></i>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required placeholder="Enter your password"
            class="form-control">
          <i class="fa-solid fa-lock icon"></i>
        </div>

        @error('username')
          <p class="error-message">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn-login">Login</button>
      </form>
    </div>
  </div>

  <div class="welcome-container">
    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Toko Kue">
    <h1>Hello, Welcome!</h1>
    <p>Silakan masuk untuk melanjutkan.</p>
  </div>

<script>
  // Helper: set a cookie with optional days to expire
  function setCookie(name, value, days) {
    let expires = '';
    if (days) {
      const date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = '; expires=' + date.toUTCString();
    }
    const secure = location.protocol === 'https:' ? '; Secure' : '';
    // Use SameSite=Lax to allow simple cross-site navigation while being reasonably safe
    document.cookie = `${name}=${encodeURIComponent(value)}${expires}; path=/; SameSite=Lax${secure}`;
  }

  // Helper: read a cookie by name
  function getCookie(name) {
    const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
  }

  document.addEventListener('DOMContentLoaded', () => {
    // If a token cookie already exists, redirect according to stored role so user doesn't have to login again
    const existingToken = getCookie('token');
    if (existingToken) {
      const isAdmin = getCookie('is_admin') === '1';
      if (isAdmin) window.location.href = '/admin';
      else window.location.href = '/branch';
      return; // stop further execution on login page
    }

    const form = document.querySelector('form');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      const username = document.querySelector('#username').value;
      const password = document.querySelector('#password').value;

      try {
        // your API endpoint from .env (example: https://api.example.com)
        const apiUrl = "{{ env('API_URL') }}/login";

        const response = await fetch(apiUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify({ username, password })
        });

        if (!response.ok) {
          if (response.status === 401) {
            alert('Invalid credentials.');
          } else {
            alert('Server error: ' + response.status);
          }
          return;
        }

        const data = await response.json();

        // Persist token & role in cookies so user stays logged in across browser sessions
        // NOTE: This is not HttpOnly (can't be set from JS). For higher security store token in HttpOnly cookie from server.
        setCookie('token', data.token, 30); // 30 days
        setCookie('is_admin', data.admin ? '1' : '0', 30);

        // Redirect based on role
        if (data.admin) {
          window.location.href = '/admin';
        } else {
          window.location.href = '/branch';
        }

      } catch (error) {
        console.error('Login failed:', error);
        alert('Could not connect to server.');
      }
    });
  });
</script>


{{-- @endsection --}}