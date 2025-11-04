# Student Swap Marketplace 🎓💱

[![PHP](https://img.shields.io/badge/PHP-7.4+-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A web-based marketplace platform designed specifically for students to buy, sell, and trade items within their campus community. StudentSwap promotes sustainability, reduces waste, and helps students save money by facilitating peer-to-peer transactions.

## 🌟 Features

### Core Functionality
- **User Authentication System**
  - Secure user registration with student ID verification
  - Login/logout functionality
  - Password recovery and reset options
  - Session management

- **Item Listings**
  - Browse items by category (Textbooks, Electronics, Furniture, etc.)
  - Search functionality to find specific items
  - Detailed item descriptions with pricing
  - View seller contact information

- **Seller Features**
  - Post new items for sale
  - Edit existing listings
  - Delete listings
  - View personal listing history

- **User Profile Management**
  - Personalized user profiles
  - View and manage posted items
  - Contact information display

### Additional Features
- **Responsive Design** - Works seamlessly on desktop, tablet, and mobile devices
- **Category-based Browsing** - Organized categories for easy navigation
- **Verified Students Only** - University email verification ensures a trusted community
- **Modern UI/UX** - Clean and intuitive interface with Font Awesome icons

## 🛠️ Technology Stack

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Custom styling with responsive design
- **JavaScript (ES6+)** - Interactive functionality
- **Font Awesome 6.0** - Icon library

### Backend
- **PHP** - Server-side scripting
- **MySQL** - Database management
- **PDO** - Database connectivity with prepared statements

### Architecture
- **Session-based Authentication** - Secure user session handling
- **MVC-inspired Structure** - Organized code separation

## 📋 Prerequisites

Before you begin, ensure you have the following installed:
- PHP 7.4 or higher
- MySQL 8.0 or higher
- Apache/Nginx web server (or XAMPP/WAMP for local development)
- Web browser (Chrome, Firefox, Safari, Edge)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/Tanvir-136/Student_Swap_Marketplace.git
cd Student_Swap_Marketplace
```

### 2. Database Setup
1. Create a MySQL database:
```sql
CREATE DATABASE student_marketplace;
```

2. Create the required tables:
```sql
-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    student_id VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Items table
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(50),
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 3. Configure Database Connection
Edit `php/db_connect.php` with your database credentials:
```php
$host = 'localhost';
$dbname = 'student_marketplace';
$username = 'your_mysql_username';  // Default: root
$password = 'your_mysql_password';  // Default: empty for local
```

### 4. Set Up Web Server
- **Using XAMPP/WAMP**: Place the project folder in `htdocs` or `www` directory
- **Using Apache**: Configure virtual host pointing to the project directory
- **Using PHP Built-in Server** (for development):
```bash
php -S localhost:8000
```

### 5. Access the Application
Open your web browser and navigate to:
- XAMPP/WAMP: `http://localhost/Student_Swap_Marketplace/`
- PHP Built-in: `http://localhost:8000/`

## 📖 Usage

### For Buyers
1. **Register/Login** - Create an account using your student email
2. **Browse Items** - Navigate to the "Browse" section
3. **Search** - Use the search bar to find specific items
4. **Filter by Category** - Select categories like Textbooks, Electronics, or Furniture
5. **Contact Seller** - View seller contact information on item listings

### For Sellers
1. **Login** - Access your account
2. **Post Item** - Click "Sell" and fill in item details
3. **Manage Listings** - View, edit, or delete your items from your profile
4. **Update Information** - Keep your contact details current

## 📁 Project Structure

```
Student_Swap_Marketplace/
├── css/
│   ├── style.css           # Main stylesheet
│   ├── listings.css        # Listings page styles
│   ├── login.css           # Login/register styles
│   ├── profile.css         # Profile page styles
│   ├── post_item.css       # Post item page styles
│   ├── edit_item.css       # Edit item page styles
│   ├── recover_password.css # Password recovery styles
│   └── about_us.css        # About page styles
├── js/
│   └── main.js             # JavaScript functionality
├── php/
│   ├── db_connect.php      # Database connection
│   ├── register.php        # User registration
│   ├── login.php           # User login
│   ├── logout.php          # User logout
│   ├── profile.php         # User profile
│   ├── listings.php        # Browse items
│   ├── post_item.php       # Create new listing
│   ├── edit_item.php       # Edit existing listing
│   ├── delete_item.php     # Delete listing
│   ├── recover_password.php # Password recovery
│   ├── reset_password.php  # Password reset
│   └── about_us.php        # About page
├── index.php               # Homepage
└── README.md              # Project documentation
```

## 🔒 Security Features

- **Password Hashing** - Uses PHP's `password_hash()` with bcrypt
- **SQL Injection Prevention** - PDO prepared statements
- **Input Sanitization** - Filter and validate user inputs
- **Session Security** - Secure session management
- **Email Validation** - Ensures valid university email addresses

## 🌐 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## 🎯 Future Enhancements

- [ ] Image upload for item listings
- [ ] Advanced search filters (price range, condition)
- [ ] User rating and review system
- [ ] Direct messaging between buyers and sellers
- [ ] Email notifications for new listings
- [ ] Admin panel for content moderation
- [ ] Payment integration
- [ ] Mobile application

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📞 Contact

**StudentSwap Team**
- Email: contact@studentswap.com
- Phone: 01723-410727

## 📄 License

This project is open source and available under the MIT License.

## 🙏 Acknowledgments

- Font Awesome for the icon library
- Unsplash for placeholder images
- The student community for inspiration and feedback

---

**Made with ❤️ for students, by students**