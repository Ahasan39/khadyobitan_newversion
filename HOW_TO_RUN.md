# 🚀 How to Run This Project

## ✅ Quick Start

### Step 1: Start the Server
Double-click this file:
```
FIX_AND_START.bat
```

OR run in Command Prompt:
```bash
php artisan serve
```

### Step 2: Test Assets Loading
Open in browser:
```
http://127.0.0.1:8000/test-direct.php
```

If you see green checkmarks ✅, assets are loading correctly!

### Step 3: Access Main Application
```
http://127.0.0.1:8000
```

---

## 🔧 Important Rules

### ✅ DO:
- Always use `php artisan serve` to start
- Always access via `http://127.0.0.1:8000`
- Clear caches after pulling changes: `php artisan config:clear`

### ❌ DON'T:
- Don't use XAMPP/WAMP direct access
- Don't use `http://localhost:8000` (use `127.0.0.1:8000`)
- Don't change asset paths in code

---

## 🐛 Troubleshooting

### If CSS/JS not loading:

1. Stop server (Ctrl+C)
2. Clear caches:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
3. Clear browser cache (Ctrl+Shift+Delete)
4. Restart server: `php artisan serve`
5. Hard refresh browser (Ctrl+F5)

### If port 8000 is busy:
```bash
php artisan serve --port=8001
```
Then access: `http://127.0.0.1:8001`

---

## 👥 For Team Members

After pulling changes:
```bash
git pull
php artisan config:clear
php artisan serve
```

---

## 📝 Daily Workflow

```bash
# Start work
git pull
php artisan serve

# During work
# Keep server running
# Use second terminal for other commands

# Before committing
git add .
git commit -m "Your message"
git push
```

---

## ✅ Success Checklist

- [ ] Server started with `php artisan serve`
- [ ] Test page works: http://127.0.0.1:8000/test-direct.php
- [ ] Main site loads: http://127.0.0.1:8000
- [ ] CSS is applied correctly
- [ ] No 404 errors in console (F12)

---

**That's it! Keep it simple. Just use `php artisan serve` and everything works!**
