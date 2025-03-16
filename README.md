# 🛍️ ManDaMel Digital Shop

This project is a **PHP-based digital shop** with a **MySQL backend** running inside Docker. It allows users to browse and purchase digital products.

## 📌 Features
- PHP Backend with MySQL database
- Uses Docker Compose for easy deployment
- Digital products (no stock management)
- MySQL initialization with `init.sql` and `seed.sql`
- Automatically loads test data on first run

---

## 🏗️ **Project Structure**

```
/mandamel
│── /database
    │ ├── Dockerfile → Custom MySQL setup
    │ ├── init.sql → Creates tables
    │ ├── seed.sql → Inserts sample data
│── /website
    │ ├── Dockerfile → PHP + Apache setup
    │ ├── config.php → Database connection
    │ ├── index.php → Main page
│── docker-compose.yml → Defines services
│── README.md → Project documentation
```
---

## 🚀 **Getting Started**

### 🔹 **1. Install Docker & Docker Compose**
Make sure you have **Docker** and **Docker Compose** installed:
- [Download Docker](https://www.docker.com/get-started)
- [Install Docker Compose](https://docs.docker.com/compose/install/)

### 🔹 **2. Clone the Repository**
```sh
git clone https://github.com/mweihmann/ManDaMel.git
cd mandamel
```

### 🔹 **3. Start the Containers**
```sh
docker-compose up -d --build
```

### 🔹 **4. Access the Shop**
```sh
http://localhost:5000
```

---


## 🛠️ **Useful Docker Commands**

### 🔍 **Check Running Containers**
```sh
docker ps
```

### 📜 **View Logs**
Logs for MySQL database:
```sh
docker logs mandamel-db
```

Logs for PHP application:
```sh
docker logs mandamel-app
```


### 🛠️ **Manually Enter Containers**
Enter MySQL Container:
```sh
docker exec -it mandamel-db mysql -u mandamel -p
```

Enter PHP Container:
```sh
docker exec -it mandamel-app bash
```

### 🛑 **Stopping & Removing Containers**
```sh
docker-compose down
```

### ❌ **Reset Database (Delete All Data)**
```sh
docker-compose down -v
```

---


## 🛠️ Database Debugging ##

### **Check If Tables Exist**
```sh
SHOW DATABASES;
USE mandamel;
SHOW TABLES;
```

### **Check If Products Exist**
```sh
SELECT * FROM products;
```

### **Manually Run SQL Scripts**
```sh
SOURCE /docker-entrypoint-initdb.d/init.sql;
SOURCE /docker-entrypoint-initdb.d/seed.sql;
```



---

### **🚀 Purpose of this Project?**
This project was developed by **Manuel Weihmann**, **Daniel Stepanovic** & **Melih Alcikaya** as part of our **Bachelor of Business Informatics** course.
