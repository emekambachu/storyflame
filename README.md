# StoryFlame Setup

## Commands to get started
```bash
sail up -d
composer update
sail npm run dev
sail artisan migrate
sail artisan queue:work
hookdeck listen http://localhost:<your_port>
```


## Tech Stack
- Laravel 11
- Sail (Docker)
- MariaDB
- Tailwind CSS
- Vue.js


---

## Using Code2Prompt to Generate Prompts

This is a useful tool to create a markdown file of our repository to improve prompting when working LLM's on our codebase.

Install Code2Prompt
```bash
pip install code2prompt
```

Check usage at: https://github.com/raphaelmansuy/code2prompt

### Popular Commands

**Specific Folders**

Required for use with Claude Projects. Includes only the specified folders and shows tokens.
```bash
code2prompt --path /path/to/project/storyflame --output /path/to/output/storyflame_app-v1.0.md --tokens --filter "app/**"
```

**Full Project with excludes**

Includes all core project files and shows tokens.
```bash
code2prompt --path /path/to/project --output
/path/to/output/storyflame_codebase-v1.0.md --tokens --exclude "**/vendor/**,**/node_modules/**,**/
storage/**"
```

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

---

## Checkout Dummy Credit Card

Use the following dummy credit card details for testing:
```bash
Card Number: 4111 1111 1111 1111
Expiry Date: 12/30
CVV: 123
```

---

# Cron Job Setup Guide for Laravel Sail

This guide provides instructions for setting up cron jobs to run Laravel Sail's scheduler on both macOS and Windows systems.

## macOS Setup

1. Open Terminal.

2. Edit your crontab file using nano:
   ```
   export EDITOR=nano && crontab -e
   ```

3. Add the following line to the crontab file:
   ```
   * * * * * cd /path/to/your/project && ./vendor/bin/sail artisan schedule:run >> /dev/null 2>&1
   ```
   Replace `/path/to/your/project` with the actual path to your Laravel project.

4. Save and exit nano:
    - Press `Ctrl + X`
    - Press `Y` to confirm saving changes
    - Press `Enter` to confirm the file name

5. Verify your crontab entry:
   ```
   crontab -l
   ```

6. Initiate queue worker:
   ```
    sail artisan queue:work
    ```
### Additional macOS Setup

On macOS, you may need to grant Full Disk Access to cron:

1. Go to System Preferences > Security & Privacy > Privacy
2. Select "Full Disk Access" from the left sidebar
3. Click the lock icon to make changes
4. Click the '+' button and add `/usr/sbin/cron`

## Windows Setup

Windows doesn't have a built-in cron job scheduler, but you can use the Task Scheduler:

1. Open Task Scheduler (search for it in the Start menu).

2. Click "Create Basic Task" in the right panel.

3. Name the task (e.g., "Laravel Scheduler") and click "Next".

4. Select "Daily" and click "Next".

5. Set the start time to any time (it will run every minute regardless) and click "Next".

6. Select "Start a program" and click "Next".

7. In the "Program/script" field, enter:
   ```
   C:\Windows\System32\wsl.exe
   ```

8. In the "Add arguments" field, enter:
   ```
   -e ./vendor/bin/sail artisan schedule:run
   ```

9. In the "Start in" field, enter your project path in WSL format, e.g.:
   ```
   /mnt/c/Users/YourUsername/path/to/your/project
   ```

10. Click "Next", then "Finish".

11. Right-click the newly created task and select "Properties".

12. In the "Triggers" tab, edit the daily trigger:
    - Change "Repeat task every:" to "1 minute"
    - Change "for a duration of:" to "Indefinitely"
    - Click "OK" to save changes

13. Start queue worker:
    ```
    sail artisan queue:work
    ```

## Important Notes

- Ensure your system is running for the cron job to work.
- Laravel Sail must be up (`sail up -d`) for the scheduler to function.
- The project path in the cron job or task should match your actual Laravel project location.
- Always test your scheduled tasks to ensure they're running correctly.
