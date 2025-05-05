# 🛠️ Config Array Flattener

> 🔁 **Reacting to Stack Overflow question [#79606568](https://stackoverflow.com/questions/79606568)**

This simple procedural PHP script converts a deeply nested configuration array (similar to a YAML config structure) into flat arrays that resemble database table rows: **categories**, **settings**, and **values**.

---

## 📦 Features

- ✅ Parses nested arrays of configuration settings
- ✅ Assigns auto-increment IDs to categories, settings, and values
- ✅ Preserves parent-child relationships (e.g. `basic`, `provisioning -> snom`)
- ✅ Outputs three structures:
  - `categories`: id, name, parent, order
  - `settings`: id, category ID, name, type, description, etc.
  - `values`: id, setting ID, default value

---

## 🚀 Quick Start

```bash
git clone https://github.com/marktaborosi/stackoverflow-79606568.git
cd stackoverflow-79606568
composer install
php -S localhost:3000 -t src/
```

Then visit: [http://localhost:3000](http://localhost:3000)

> 💡 You can also change the port if 3000 is occupied.

---

## 🗂️ File Structure

```
src/
  └── index.php      # Procedural script that does the flattening
vendor/              # Composer packages (e.g. larapack/dd)
composer.json        # Composer config (autoload, dependencies)
```

---

## 🧪 Example Output

- `dump($categories)` shows all categories with their hierarchy.
- `dump($settingsList)` shows all individual settings.
- `dump($values)` shows the default values and links to settings.

Example:
```
--- Categories ---
basic => [id: 1, parent: 0, name: "basic", order: 1]
provisioning => [id: 2, parent: 0, name: "provisioning", order: 2]
snom => [id: 3, parent: 2, name: "snom", order: 3]
...
```

---

## 📚 Dependencies

- [larapack/dd](https://github.com/larapack/dd) – for the `dump()` function

Install with:

```bash
composer install
```

---

## 👤 Author

**Mark Taborosi**  
📧 mark.taborosi@gmail.com

---

## 📜 License

MIT
