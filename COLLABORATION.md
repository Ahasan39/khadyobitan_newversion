# Local Development Guide for Team Collaboration

This document explains how to run this project locally without changing any frontend asset paths or Blade code. This setup ensures that all 3 team members can work on different features while keeping the code compatible with the live server.

## 🚀 Getting Started

1.  **Clone the repository.**
2.  **Install dependencies:**
    ```bash
    composer install
    php artisan key:generate
    ```
3.  **Start the server:**
    ```bash
    php artisan serve
    ```

## 🛠 Why Assets Load Correctly (No Path Changes Needed)

In this project, many assets use the `/public/` prefix (e.g., `asset('public/css/style.css')`). On some local servers, this usually causes 404 errors. 

**I have implemented a "Code-Transparent" fix:**
The `index.php` and `public/index.php` files have been updated to programmatically handle requests starting with `/public/`. 
*   **Result:** You **do not** need to change any `asset()` calls in your Blade templates.
*   **Team Benefit:** Everyone can use `php artisan serve` and everything will "just work."

## 📦 Database & 500 Errors
If you experience 500 errors on the homepage (especially during "Load More Products"), I have updated `FrontendController.php` to:
1.  **Eager Load Relationships:** Using `->with('image')` to ensure product images are fetched.
2.  **Safety Checks:** The controller now handles cases where products might not have images or certain columns are missing in your local database.

## ⚠️ Known Issues & Solutions

### 1. Missing Images (404s)
If you see images missing in the console, it is likely because the physical files (e.g., in `public/uploads/product/`) were not included in your local copy. 
*   **Fix:** Obtain the `uploads` folder from the live server and place it in your local `public/` directory.

### 2. JavaScript Errors
I have fixed several "null" reference errors in `master.blade.php`, `preloader.blade.php`, and `checkout.blade.php`.
*   **Rule:** Always wrap your JavaScript selectors in `if` checks if you are not sure the element exists on every page.
    *   *Example:* `let element = document.getElementById('myId'); if (element) { ... }`

## 🤝 Merge Strategy
To avoid conflicts:
1.  **Pull before you Push:** Always run `git pull origin main` before starting your work.
2.  **Don't modify Entry Points:** Avoid changing `index.php`, `server.php`, or `.htaccess` unless absolutely necessary, as these contain the local environment fixes.

---
*Happy Coding!* 🚀
