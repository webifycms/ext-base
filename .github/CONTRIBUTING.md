# 👋 Hey there, fellow contributor

> Thank you for being here and considering a contribution to WebifyCMS!
> Together we can make great software.

---

## ⚖️ Developer Certificate of Origin (DCO)

To keep WebifyCMS open, secure, and legally clean, we use a **Developer Certificate of Origin (DCO)** 
instead of a complex contributor agreement. 

By contributing to this project, you certify that you have the right to submit the code
under the project's open-source license.

### How to Sign Your Work
All you need to do is sign off on your git commits.
You can do this automatically by adding the `-s` flag when you commit:

```bash
git commit -s -m "Your meaningful commit message"
```

This automatically appends a line to your commit message like this:
`Signed-off-by: Your Name <your.email@example.com>`

*Note: Pull Requests with unsigned commits cannot be merged. If you forget to sign a commit,
you can fix it using `git commit --amend -s` before pushing.*

---

## 🛠️ Let's Set Up for Development

### Prerequisites

* An environment capable of running Docker or Docker Desktop.

### 1. Installation

Clone the main repository and use the [WebifyCMS Installer](https://github.com/webifycms/installer)
package to initialize the local stack setup.

### 2. Environment Configuration

Navigate to your application `app` directory and copy the sample environment file:

```bash
cd app && cp .env.sample .env
```

Open the newly created `.env` file and adjust the values for your local setup.
Ensure the environment variables are configured for local debugging:

* `APP_ENVIRONMENT='development'`
* `APP_DEBUG=true`

Add your configured `APP_BASE_URL` value to your local system's host file if you are using a custom local domain.

At this stage, your local folder structure should match this layout:

```
.
└── webifycms-stack/
    ├── app/
    │   ├── ...
    │   ├── .env
    │   ├── .env.sample
    │   ├── composer.json
    │   ├── composer.lock
    │   ├── src/
    │   ├── extensions/
    │   ├── themes/
    │   ├── vendor/
    │   └── ...
    ├── extensions/
    │   └── ext-base/
    └── themes/
        └── theme-canvas/
```

### 3. Install Dependencies

Switch to your working branches inside the `app` and `extensions` directories, then install the PHP packages:

```bash
php composer install
```

### 4. Run the Application

You can spin up the application quickly using PHP's built-in development web server:

```bash
php -S localhost:8000 -t public/
```

The application will now be available in your browser at `http://localhost:8000`.

> 💡 **Note:** The built-in PHP server is single-threaded and intended strictly for local development.
> Use a dedicated web server like Nginx, Apache, or Caddy for production deployments.