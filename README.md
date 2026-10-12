# Travel Books — Travel Agency Accounts

E-Manager-এর সবুজ dashboard ধারণা অনুসরণ করে তৈরি **PHP 8+ / MySQL / Apache shared-hosting** web application। Node.js production-এ বাধ্যতামূলক নয়; cPanel shared hosting-এ সরাসরি চালানো যাবে।

## যেসব মডিউল আছে

- Secure session login (`password_hash` / `password_verify`) এবং admin/staff role
- Dashboard: আয়, ব্যয়, net profit, receivable, আসন্ন ভ্রমণ ও due customer
- Booking & service: air ticket, hotel, tour package, visa, transport, insurance, other
- Customer CRM: phone, email, passport, NID, address, notes
- Supplier ledger base: airline, hotel, visa provider, transport supplier এবং opening balance
- Transactions: income, expense, customer collection, supplier payment, refund; payment method ও reference
- Passport / visa / NID / ticket expiry tracking
- Reports: category-wise accounting এবং service profitability
- Agency settings, বাংলা/ইংরেজি toggle, responsive mobile UI
- MySQL transaction ও foreign-key integrity; prepared statements; Apache security headers

## cPanel installation

1. cPanel-এ একটি MySQL database এবং database user বানিয়ে user-কে সব privilege দিন।
2. এই folder-এর সব file `public_html/travel-books/`-এ upload করুন।
3. `https://yourdomain.com/travel-books/install.php` খুলুন।
4. Database তথ্য ও admin password দিন।
5. Install সফল হলে নিরাপত্তার জন্য `install.php` মুছে দিন।
6. Login: username `admin`, আপনার install-এর admin password।

> `config/config.local.php` installer নিজে তৈরি করে। এটি কখনও public repository-তে commit করবেন না। HTTPS ব্যবহার করুন এবং নিয়মিত MySQL backup নিন।

## Manual install

phpMyAdmin-এ `database/schema.sql` import করে `config/config.php` কপি করে `config/config.local.php` বানান এবং credentials বসান। তারপর admin password hash update করুন বা installer ব্যবহার করুন।

## Local smoke test

PHP 8+ থাকলে:

```bash
php -S 127.0.0.1:8080
```

তারপর browser-এ `http://127.0.0.1:8080/install.php`।

## গুরুত্বপূর্ণ accounting scope

Booking sale amount ও cost amount থেকে gross profit, paid amount থেকে customer receivable, এবং transactions table থেকে cash/bank/mobile/card movement বের হয়। বাস্তব agency-তে VAT/TAX, airline BSP/ARC reconciliation, multi-currency, payroll, bank reconciliation ও audit export প্রয়োজন হলে পরের phase-এ যোগ করা যাবে।
