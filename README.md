<img src="src/public/favicon.svg" alt="logo" width="100"/>

# P10 Game

This is the P10 Game Website, a silly game where players predict the driver who will qualify P10 for each race in the Formula 1 Season.

## 🎯 Game Objective

Each player predicts which driver will qualify in **P10** for each event. Points are awarded as follows:

- ✅ Exact P10 prediction: **2 points**
- 🎯 Closest to P10 (P9 → P1): **1 point**
- ❌ No close match: **0 points**

## ⚙️ Tech Stack

- Laravel 11 + Breeze
- Tailwind CSS
- MySQL 8
- PHP 8.2 (via Docker)
- Docker Compose
- GitHub Container Registry (GHCR) for image publishing

## 🚀 Getting Started

### 1. Clone the Repo

```bash
git clone https://github.com/<your-user>/p10.git
cd p10
```

### 2. Setup Environment

```bash
cp .env.example .env
```

Make sure to update database credentials and other secrets as needed.

### 3. Start the App with Docker

```bash
docker-compose up --build
```

### 4. Access the App

- App: http://localhost
- phpMyAdmin: http://localhost:8080
- Login/Register via Breeze auth scaffolding

## 🐳 Prebuilt Docker Image

You can also pull and run the prebuilt Docker image from GitHub Container Registry:

```bash
docker pull ghcr.io/teacupuk/p10:latest
```

This includes the full Laravel app and NGINX+PHP environment built into a single container.

Run it with:

```bash
docker run -p 80:80 ghcr.io/teacupuk/p10:latest
```

## ✍️ Admin Dashboard

Once authenticated, visit `/dashboard` to:

- Manage F1 Events
- Edit qualifying results (P1–P20)
- Input player predictions
- View leaderboard and breakdown

## 📸 Screenshots

TBD – include leaderboard, admin panel, event breakdown examples.

## 📄 License

MIT License © 2025
