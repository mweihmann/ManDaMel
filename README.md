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
│   │   └── 👤 xxxLogic.php            -- Placeholder for all Business logic files
│   ├── 📁 config
│   │   ├── 🧩 bootstrap.php           -- App bootstrapper for setup
│   │   └── 🗃️ db.php                  -- MySQL connection setup
│   ├── 📁 lib
│   │   └── 📁 fpdf                    -- All FPDF files for crteating pdf invoices
│   ├── 📁 models
│   │   └── 👤 xxxx.php                -- Placeholder for all model files representing the DB structure
│   ├── 📁 public                      -- Public web root (exposed to Apache)
│   │   ├── 📁 api
│   │   │   └── 🔌 xxxx.php            -- Placeholder for all API endpoints
│   │   ├── 📦 vendor                  -- Autoloaded dependencies (Composer)
│   │   └── 📄 composer.json           -- Composer dependencies config
│   ├── 🧾 .env.example                -- Environment variables
│   ├── 📄 composer.json               -- Main composer configuration
│   ├── 📦 composer.lock               -- Locked composer dependencies
│   └── 🔌 datahandler.php             -- Alternate API handler
│
├── 📁 database                        -- SQL initialization for MySQL
│   ├── 🧱 init.sql                    -- SQL schema definition
│   ├── 🧱 manda.dbml                   -- SQL DB Visualisation
│   └── 🌱 seed.sql                    -- Insert demo/seed data
│
├── 📁 frontend                        -- Frontend application
│   ├── 📁 .devcontainer               -- VS Code dev container for frontend
│   │   ├── 📄 devcontainer.json       -- Dev container setup for frontend
│   │   ├── 🐘 Dockerfile              -- Dockerfile for frontend container
│   │   └── ⚙️ xdebug.ini              -- Optional frontend Xdebug config
│   ├── 📁 .vscode
│   │   └── 🐞 launch.json             -- Xdebug launch config for frontend
│   ├── 📁 css
│   │   └── 💅 style.css               -- CSS styles
│   ├── 📁 js                          -- Placeholder for JavaScript files
│   ├── 🧾 index.php                   -- Main frontend file that loads data
│   ├── 🧾 xxxx.php                    -- Placeholder for all .php frontend files
│   └── 📝 register.php                -- Register form 
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
   - Dev Containers (`ms-vscode-remote.remote-containers`)
   - PHP Debug (`xdebug.php-debug`)
   - PHP Intelephense (`bmewburn.vscode-intelephense-client`)

---

## 📦 How to Start the Full Project

### Clone the repo:

```bash
git clone https://github.com/mweihmann/ManDaMel.git
cd ManDaMel
```

### After cloning the repository, copy the .env.example file and rename it to .env:

#### Windows in (Command Prompt)
```bash
copy .env.example .env
```

#### Windows (in PowerShell)
```bash
Copy-Item .env.example .env
```

#### macOS
```bash
cp .env.example .env
```

### With PHP Debugging (not necessary)

- Comment in the lines `code ./frontend` and `code ./backend` inside of `WINDOWS_START.ps1` or `MACOS_START.sh`

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

### Debugging (only for debugging)

> ⚠️ If you have limited RAM/CPU: open backend and frontend containers **one after the other**.

1. Open **VS Code** in the `backend` and/or `frontend` folders
2. Use Command Palette → **“Dev Containers: Reopen in Container”**
3. Once loaded:
   - Open `.vscode/launch.json`
   - Press `F5` to start the debugger

---

### 🌐 Access the Application

- Backend: [http://localhost:5000](http://localhost:5000) 
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

Initializes automatically with:
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

## 🎯 Project Purpose

This project was developed as part of the **Web Development** course of the **Bachelor of Business Informatics** program  
at the **University of Applied Sciences Technikum Vienna**.

### 👨‍💻 Contributors

| Name               |
|--------------------|
| Manuel Weihmann    |
| Daniel Stepanovic  |
| Melih Alcikaya     |
