# 🛍️ ManDaMel Digital Shop

This project is a **PHP-based digital shop** with a **MySQL backend** running inside Docker. It allows users to browse and purchase digital products.

---

## 🏗️ **Project Structure**

```
📁 ManDaMel                            -- Root project folder
│
├── 📁 backend                         -- PHP backend application
│   ├── 📁 .devcontainer               -- VS Code dev container for backend
│   │   ├── 📄 devcontainer.json       -- Dev container configuration for VS Code
│   │   ├── 🐘 Dockerfile              -- Dockerfile to set up backend environment
│   │   └── ⚙️ xdebug.ini              -- Xdebug config for backend container
│   ├── 📁 .vscode
│   │   └── 🐞 launch.json             -- VS Code launch config for Xdebug
│   ├── 📁 businesslogic
│   │   └── 👤 UserLogic.php           -- Business logic for handling user data
│   ├── 📁 config
│   │   ├── 🧩 bootstrap.php           -- App bootstrapper for setup
│   │   └── 🗃️ db.php                  -- MySQL connection setup
│   ├── 📁 models
│   │   └── 👤 User.php                -- User model representing DB structure
│   ├── 📁 public                      -- Public web root (exposed to Apache)
│   │   ├── ⚙️ .vscode                 -- Debug config for frontend (optional)
│   │   ├── 📁 api
│   │   │   └── 🔌 serviceHandler.php  -- API endpoint to handle AJAX calls
│   │   ├── 📦 vendor                  -- Autoloaded dependencies (Composer)
│   │   ├── 📄 composer.json           -- Composer dependencies config
│   │   ├── 🧪 debug-test.php          -- Simple PHP file for debugging Xdebug
│   │   └── 📄 index.php               -- Entry point for public/backend frontend
│   ├── 🧾 .env.example                -- Environment variables
│   ├── 📄 composer.json               -- Main composer configuration
│   ├── 📦 composer.lock               -- Locked composer dependencies
│   └── 🔌 datahandler.php             -- Alternate API handler
│
├── 📁 database                        -- SQL initialization for MySQL
│   ├── 🧱 init.sql                    -- SQL schema definition
│   └── 🌱 seed.sql                    -- Insert demo/seed data
│
├── 📁 frontend                        -- Frontend application
│   ├── 📁 .devcontainer               -- VS Code dev container for frontend
│   │   ├── 📄 devcontainer.json       -- Dev container setup for frontend
│   │   └── 🐘 Dockerfile              -- Dockerfile for frontend container
│   ├── 📁 .vscode
│   │   └── 🐞 launch.json             -- Xdebug launch config for frontend
│   ├── 📁 css
│   │   └── 💅 style.css               -- CSS styles
│   ├── 📁 js                          -- Placeholder for JavaScript files
│   ├── ⚙️ config.php                  -- Frontend config logic (PHP)
│   ├── 🧾 index.php                   -- Main frontend file that loads data
│   ├── 🧾 index2.php                  -- Alternative frontend layout or version (should be deleted)
│   ├── 📝 register.php                -- Register form or placeholder
│   └── ⚙️ xdebug.ini                  -- Optional frontend Xdebug config
│
├── 🧩 .code-workspace                 -- VS Code workspace file
├── 📄 .gitignore                      -- Git ignore rules (node_modules, vendor, env, IDE files, etc.)
├── 🐳 docker-compose.yml              -- Defines all containers and services
├── 🍎 MACOS_START.sh                  -- Startup script for macOS
├── 🪟 WINDOWS_START.ps1               -- Startup script for Windows
└── 📖 README.md                       -- Project documentation (this file)
```

## ✅ Prerequisites

1. [Docker Desktop](https://www.docker.com/products/docker-desktop/)
2. [Visual Studio Code](https://code.visualstudio.com/)
3. VS Code Extensions:
   - Remote - Containers (`ms-vscode-remote.remote-containers`)
   - PHP Debug (`xdebug.php-debug`)
   - PHP Intelephense (`bmewburn.vscode-intelephense-client`)

---

## 📦 How to Start the Full Project

### Clone the repo:

```bash
git clone <your-repo-url>
cd ManDaMel
```

### After cloning the repository, copy the .env.example file and rename it to .env:
```bash
cp .env.example .env
```

### Windows

1. Open PowerShell as Administrator
2. Run the startup script: `WINDOWS_START.ps1`

```powershell
./WINDOWS_START.ps1
```

---

### macOS

1. Open Terminal
2. Run the startup script:

```bash
chmod +x MACOS_START.sh
./MACOS_START.sh
```

---

### After

> ⚠️ If you have limited RAM/CPU: open backend and frontend containers **one after the other**.

3. Open **VS Code** in the `backend` and/or `frontend` folders
4. Use Command Palette → **“Dev Containers: Reopen in Container”**
5. Once loaded:
   - Open `.vscode/launch.json`
   - Press `F5` to start the debugger

---

### 🌐 Access the Application

- Backend: [http://localhost:5000](http://localhost:5000) 
- API Test: [http://localhost:5000/api/serviceHandler.php?method=getAllUsers](http://localhost:5000/api/serviceHandler.php?method=getAllUsers) 
- Frontend: [http://localhost:3000](http://localhost:3000)

---

### 📊 MySQL Access

- Via phpMyAdmin GUI: [http://localhost:8080](http://localhost:8080)
- Credentials:
  - **Host:** `mandamel-db`
  - **User:** `mandamel`
  - **Pass:** `mandamel`

---

### 🗄️ Database Initialization

Initialisiert sich automatisch mit:
- `database/init.sql`
- `database/seed.sql`

---


## ✅ Useful Commands

| Command                         | Description                         |
|--------------------------------|-------------------------------------|
| `docker ps` | View Running Containers                                 |
| `docker compose up -d --build` | Start all services                  |
| `docker compose down`          | Stop and remove services            |
| `docker exec -it <container> bash` | Open shell inside a container  |
| `docker exec -it mandamel-db mysql -u mandamel -pmandamel mandamel` | Open database via console |
| `USE mandamel;` | Select the mandamel database |
| `SHOW TABLES;` | List all tables in the selected database |
| `DESCRIBE users;` | Show the structure (columns & types) of the users table |
| `SELECT * FROM users;` | Fetch all entries from the users table |
| `EXIT;` | Exit MySQL prompt |
| `docker exec -it mandamel-app cat /tmp/xdebug.log` | Show Xdebug log to verify connection |
| `docker logs mandamel-db` | MySQL Logs |
| `docker logs mandamel-app` | Backend Logs |




---

## 🧹 Reset the Project (if needed)
```bash
docker compose down -v
```
This removes **all data** including MySQL volumes.

---

### **🚀 Purpose of this Project?**
This project was developed by **Manuel Weihmann**, **Daniel Stepanovic** & **Melih Alcikaya** as part of our **Bachelor of Business Informatics** course.
