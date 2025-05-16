# 🛍️ ManDaMel Digital Shop

This project is a **PHP-based digital shop** with a **MySQL backend** running inside Docker. It allows users to browse and purchase digital products.

---

## 🏗️ **Project Structure**

```
📁 MANDAMEL                             -- Root project folder
│
├── 📁 .github                          -- GitHub workflows and configurations
│
├── 📁 backend                          -- PHP backend application
│   ├── 📁 .devcontainer                -- Dev container config (VS Code)
│   ├── 📁 .vscode                      -- Debug configuration for VS Code
│   ├── 📁 auth                         -- Authentication logic & middleware
│   ├── 📁 businesslogic                -- Business rules for cart, users, products, etc.
│   ├── 📁 config                       -- Config files (bootstrap, DB connection)
│   ├── 📁 models                       -- PHP models for database mapping
│   ├── 📁 public                       -- Publicly accessible API endpoints (e.g. login, register)
│   ├── 📁 uploads\files                -- Uploaded product files (PDFs, ZIPs)
│   ├── 📦 vendor                       -- Composer dependencies (auto-generated)
│   ├── ⚙️ .env                         -- Environment variables (not versioned)
│   ├── ⚙️ .env.example                 -- Example environment file
│   ├── 📄 composer.json               -- Dependency declarations
│   ├── 📄 composer.lock               -- Dependency lock file
│   └── 🔌 datahandler.php              -- Generic data handler (optional)
│
├── 📁 database                         -- SQL & DBML files
│   ├── 🧱 init.sql                    -- Initial DB schema
│   ├── 🌱 seed.sql                    -- Optional: seed data
│   └── 🧩 mandamel.dbml               -- DBML model (for visualization)
│
├── 📁 frontend                         -- Frontend PHP application
│   ├── 📁 .devcontainer                -- Frontend dev container (VS Code)
│   ├── 📁 .vscode                      -- Debugging configs
│   ├── 📁 css                          -- Custom styles
│   ├── 📁 includes                     -- Header/Footer includes
│   ├── 📁 js                           -- JavaScript modules (AJAX, events, etc.)
│   ├── 📄 checkout.php                -- Checkout page
│   ├── 📄 config.php                  -- Frontend configuration
│   ├── 📄 index.php                   -- Start page
│   ├── 📄 login.php                   -- Login form
│   ├── 📄 manage_accounts.php        -- Admin: manage user accounts
│   ├── 📄 manage_products.php        -- Admin: manage products
│   ├── 📄 manage_vouchers.php        -- Admin: manage vouchers
│   ├── 📄 my_account.php             -- User profile page
│   ├── 📄 product.php                -- Product details
│   └── 📄 register.php               -- Registration page
│
├── 🧩 .code-workspace                  -- VS Code workspace settings
├── 📄 .gitignore                       -- Git ignored files & folders
├── 🐳 docker-compose.yml              -- Docker configuration
├── 🍎 MACOS_START.sh                  -- Startup script (macOS)
├── 🪟 WINDOWS_START.ps1               -- Startup script (Windows)
└── 📖 README.md                        -- This documentation file
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
