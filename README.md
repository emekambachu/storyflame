# StoryFlame Setup

## Commands to get started
```bash
sail up -d
composer update
sail npm run dev
sail artisan migrate
hookdeck listen http://localhost:83
```


## Tech Stack
- Laravel 11
- Sail (Docker)
- MariaDB
- Tailwind CSS
- Vue.js


---

## Setup Guide for Hookdeck and Paddle Webhooks

This guide will help you set up Hookdeck to receive Paddle.com staging webhooks for your Laravel 11 application running on Docker using Laravel Sail.

### Step 1: Install Hookdeck CLI

First, install the Hookdeck CLI. You can do this using npm, yarn, Homebrew (for macOS), Scoop (for Windows), or directly via Docker.

**Using Docker:**
```bash
docker run --rm -it hookdeck/hookdeck-cli version
```

### Step 2: Log in to Hookdeck

Log in to your Hookdeck account using the CLI:

```bash
hookdeck login
```
### Step 3: Set up Hookdeck to Listen on a Port

Run the following command to start a Hookdeck session that forwards webhooks to your local Laravel application running on port 83:
```bash
hookdeck listen http://localhost:83
```
When prompted:
- Source label: `Paddle Staging - StoryFlame`
- Path to forward events: `/paddle/webhook`
- Connection label: `Paddle Webhooks - StoryFlame`

### Step 4: Configure Paddle Webhooks in Laravel
Set Up Paddle Sandbox:
Register a Paddle Sandbox account here: https://sandbox-login.paddle.com/signup

Add Paddle Configuration to .env
```bash
PADDLE_SANDBOX=true
PADDLE_VENDOR_ID=your-paddle-vendor-id
PADDLE_API_KEY=your-paddle-api-key
PADDLE_PUBLIC_KEY="your-paddle-public-key"
PADDLE_WEBHOOK_SECRET="your-paddle-webhook-secret"
```

### Step 5: Update Paddle Settings
In the Paddle dashboard, set your webhook URL to the Hookdeck URL provided when you started listening with Hookdeck. It will look something like this:
```bash
https://hkdk.events/<id_from_hook>  
```

Check Hookdeck to track requests and events:
https://dashboard.hookdeck.com/events
https://dashboard.hookdeck.com/requests
