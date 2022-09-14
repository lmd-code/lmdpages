# LMD Pages

**Version:** 0.0.1

For rapid and simple website builds, created to quick-start my own projects.

- ✔️ Server requires PHP (ver. >= 7.4) with .htaccess enabled.
- ✔️ Must have FTP access to server.
- ❌ No database required.
- ❌ No installation script to run.
- ❌ No user accounts/logins to worry about.

## What is LMD Pages suitable for?

- Small brochure/informational sites with fewer than ~10 pages.
- Sites with a flat architecture (no sub-pages).
- Sites that do not update very often.
- Sites where the client will not be editing/updating any content themselves.
- Sites with no need for user logins.

Essentially this is a bare-bones website starter-kit without the overhead of either a database-driven content management system, or a fully-fledged static site generator.

It's one step up from putting `<?php include('header_file.php'); ?>` on *every* page! :D

## Get Started

1. Download this repo to your local dev environment.
2. In your code editor, open `/content/site-data.json` and edit for your site.
3. Create/edit page content (inc. `_header.php`/`_footer.php` content).
4. Drop in your assets (images, CSS, scripts) to the appropriate `assets/*` directory.
5. *Optional*: if running in a subdirectory, open up the root `.htaccess` file and comment-out each "Option 1" and uncomment each "Option 2" (replacing the 'your_dir' placeholders).
6. Upload the contents of your repo copy to your server root (or subdirectory).
    - You can ignore the .git related files (e.g. `.gitignore`).

## @TODO

- [ ] Get "LMD Contact" and "LMD Gallery" ready for public release to be easy drop-in solutions.
- [x] Add a method to `Markup` to return navigation items as an array of data for creating custom menus instead of forcing fixed markup.
- [ ] Write up more detailed documentation.
