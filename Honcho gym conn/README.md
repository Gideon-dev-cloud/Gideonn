# 🏋️‍♂️ Honcho's Gym - E-Commerce & Fitness Platform

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000f?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

> **A full-stack e-commerce solution for fitness equipment, featuring a dynamic storefront, secure user authentication, and a comprehensive admin management dashboard.**

---

## 📖 Project Overview

**Honcho's Gym** is a web-based application built to simulate a real-world e-commerce environment. It solves the problem of inventory management and customer transaction tracking for a fitness brand.

Unlike simple static websites, this project utilizes a relational database to manage products, users, and orders dynamically. It demonstrates core backend competencies including **CRUD operations**, **Session Management**, and **Security best practices** (Prepared Statements against SQL Injection).

---

## 🚀 Key Features

### 🛒 Customer Experience
* **User Authentication:** Secure Sign-up and Login system using `password_hash()` and `password_verify()`.
* **Dynamic Product Catalog:** Products are fetched from the database, not hardcoded HTML.
* **Search Functionality:** Real-time SQL search to filter products by name.
* **Shopping Cart:** Session-based cart system allowing users to add/remove items before checkout.
* **Order Tracking:** "My Orders" page allowing users to see past purchase history and status.
* **Responsive Design:** Fully mobile-responsive UI using Bootstrap 5.

### 🔐 Admin Dashboard
* **Analytics Overview:** Real-time view of Total Revenue, Pending Orders, and Inventory count.
* **Product Management:** Admin can Add, Edit, or Delete products (including image uploads).
* **Order Fulfillment:** View detailed customer orders and update status (Pending -> Completed -> Cancelled).
* **Security:** Role-based access control (RBAC) ensures only admins can access the dashboard.

---

## 🛠️ Technical Stack

* **Frontend:** HTML5, CSS3, JavaScript (ES6), Bootstrap 5.
* **Backend:** Native PHP (No frameworks used, demonstrating core logic understanding).
* **Database:** MySQL (Relational Database Management).
* **Server:** Apache (via XAMPP).
* **Tools:** Visual Studio Code, Git, phpMyAdmin.

---

## 📸 Screenshots

| Homepage (Dynamic) | Admin Dashboard |
|:---:|:---:|
| <img src="./img/screenshot1.png" width="400"> | <img src="./img/screenshot2.png" width="400"> |

| Shopping Cart | Mobile View |
|:---:|:---:|
| <img src="./img/screenshot3.png" width="400"> | <img src="./img/screenshot4.png" width="400"> |

*(Note: These are placeholders. Please add screenshots to your `img` folder)*

---

## ⚙️ Installation & Setup

To run this project locally, follow these steps:

1.  **Clone the repository:**
    ```bash
    git clone [https://github.com/yourusername/honchos-gym.git](https://github.com/yourusername/honchos-gym.git)
    ```

2.  **Move to Server Directory:**
    Place the folder inside your server's root directory (e.g., `C:\xampp\htdocs\honchos-gym`).

3.  **Database Setup:**
    * Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
    * Create a new database named `honchosgym`.
    * Import the provided SQL file (`honchosgym.sql`) located in the root folder.

4.  **Configure Connection:**
    Ensure `db_config.php` matches your local credentials:
    ```php
    $host = "localhost";
    $db_user = "root";
    $db_pass = ""; // Default XAMPP password is empty
    $db_name = "honchosgym";
    ```

5.  **Run:**
    Open your browser and navigate to:
    `http://localhost/honchos-gym/gymproj.php`

---

## 🗄️ Database Schema

* **Users:** Stores customer info and hashed passwords.
* **Products:** Stores item details, price, stock quantity, and image paths.
* **Orders:** Links Users to Transactions (Total Price, Date, Status).
* **Order_Items:** Pivot table linking Orders to specific Products (Quantity, Price at time of purchase).
* **Cart:** Temporary storage for items before checkout.

---

## 🛡️ Security Highlights

* **SQL Injection Prevention:** All user inputs (Login, Search, Cart) are sanitized using PHP **Prepared Statements** (`$stmt->prepare()`).
* **XSS Protection:** Output escaping using `htmlspecialchars()` on the frontend.
* **Session Hijacking:** `session_start()` used securely across all pages.

---

## 👤 Author

**[Gideon Chimaobi]**
* **Portfolio:** [Link to your portfolio if you have one]
* **LinkedIn:** [Link to your LinkedIn]
* **GitHub:** [Link to your GitHub]

---

*Built with ❤️ and PHP.*