# Deploying to cPanel shared hosting

Follow these in order. Nothing here needs SSH or Composer on the server —
everything's already vendored/built locally.

## 1. Create the database

cPanel → **MySQL® Databases**
1. Create a database (e.g. `chefched_db` — cPanel will prefix it with your account name, e.g. `username_chefched_db`).
2. Create a database user with a strong password.
3. Add that user to the database with **ALL PRIVILEGES**.
4. Note the final DB name, username and password — cPanel prefixes both with your account name.

## 2. Import the schema

cPanel → **phpMyAdmin** → select your new database → **Import** tab → choose `schema.sql` from this project → Go.

Confirms you now have 4 tables: `admin_users`, `enquiries`, `contact_messages`, `submission_log`.

## 3. Create a mailbox for notification emails

cPanel → **Email Accounts** → create e.g. `noreply@yourdomain.com` with a strong password.
This is what `api/lib/mailer.php` authenticates as via SMTP — using the server's own mailbox is far
more likely to land in the inbox than PHP's bare `mail()` function.

## 4. Get your CallMeBot WhatsApp API key

1. On the phone that should receive enquiry alerts, save this contact: **+34 644 20 47 56**.
2. Send that contact the WhatsApp message: `I allow callmebot to send me messages`
3. CallMeBot replies with your personal API key within a minute or two. Keep it.

## 5. Upload the files

Via **File Manager** or an FTP/SFTP client, upload the *entire project* into `public_html`
(or a subfolder/subdomain if you want the site somewhere other than the domain root). That includes:

- All the `.html` pages, `css/`, `script/`, `images/`, `extra-images/`, `fonts/`, `style.css`
- The `api/` folder **including `api/vendor/`** (PHPMailer — no Composer needed on the server)
- The `admin/` folder (the built dashboard — static files only)
- `schema.sql` and `create_admin.php` (delete `create_admin.php` after step 7)
- **`.htaccess`** at the project root — this is what gives every page its clean URL (e.g.
  `/gallery` instead of `/gallery.html`). It's a hidden file (dotfile), so make sure your FTP
  client or File Manager is set to show hidden files, or it'll silently get skipped on upload.
  cPanel's Apache already allows `.htaccess` overrides by default, so no extra setup is needed —
  if clean URLs 404 after upload, the most likely cause is this file didn't actually make it up.

Do **not** upload `admin-dashboard/` (that's the dashboard's source project — only its build
output in `admin/` is needed on the server) or `archive/`/`brand-assets/` (reference material, not part of the live site).

## 6. Confirm PHP version

cPanel → **MultiPHP Manager** → select your domain → set PHP to **8.1 or newer**.

## 7. Fill in real config

On the server, edit `api/config.php` (or upload a fresh copy) using `api/config.php.sample` as the
template, with:
- `DB_HOST`/`DB_NAME`/`DB_USER`/`DB_PASS` from step 1 (and set `DB_DRIVER` to `mysql`)
- `SMTP_*` from step 3
- `WHATSAPP_PHONE` (the receiving number, international format, no `+`) and `WHATSAPP_API_KEY` from step 4, and `WHATSAPP_ENABLED = true`
- `APP_ENV = 'production'`

## 8. Create the first admin login

Visit `https://yourdomain.com/create_admin.php?username=YOURNAME&password=YOURPASSWORD` once in
your browser. It refuses to run a second time once an admin exists. **Delete `create_admin.php`
from the server immediately after.**

## 9. Confirm HTTPS

cPanel → **SSL/TLS Status** → run **AutoSSL** if it isn't already active. The site and dashboard
should only ever be used over `https://`.

## 10. Test for real

1. Submit the booking form on `/reservations.html` and the contact form on `/contact-us.html` with
   real-looking test data.
2. Confirm: a WhatsApp message arrives, a notification email arrives, and the row appears at
   `https://yourdomain.com/admin/` after logging in.
3. Change an enquiry's status in the dashboard and confirm it sticks after a refresh.
4. Try the **Reply on WhatsApp** button on an enquiry — it should open WhatsApp with that
   customer's number and a pre-filled greeting.

---

## If something's wrong

- **Forms submit but nothing shows in the dashboard / no notifications**: check `api/config.php`
  has real values, not the placeholders. PHP errors are written to cPanel's error log (**Metrics →
  Errors**), not shown to visitors — check there first.
- **WhatsApp never arrives but email does**: re-check the CallMeBot activation message was sent
  from the *same* phone number configured in `WHATSAPP_PHONE`, and that the API key hasn't expired
  (CallMeBot keys occasionally need re-activating the same way).
- **Dashboard shows a blank page**: open browser dev tools → Console. Almost always means `admin/`
  wasn't uploaded correctly, or the domain is serving it from the wrong path.
