# Go Numerology API

This project is a Go-based API for numerology analysis, migrated from an original PHP application. It uses the Gin framework for routing and connects to a PostgreSQL database.

## Features

*   **Member Registration:** Securely register new users with password hashing (`/api/v1/member/register`).
*   **Phone Number Analysis:** Get a numerological analysis of a phone number (`/api/v1/phone/analyze/:phoneNumber`).

## Getting Started

### Prerequisites

*   Go (version 1.18 or higher)
*   PostgreSQL
*   Git

### 1. Local Development

1.  **Clone the repository:**
    ```bash
    git clone <YOUR_GIT_REPOSITORY_URL>
    cd go_numerology_api
    ```

2.  **Set up the database:**
    *   Make sure you have a running PostgreSQL instance.
    *   Create a database (e.g., `numerology_db`).
    *   Apply the database schema by running the SQL script:
        ```bash
        psql -U your_username -d numerology_db -f db/migrations/001_initial_schema.up.sql
        ```
    *   **Important:** You will also need to manually insert the data for the `numbers` table from the original `zoqlszwh_ananyadb.sql` file for the phone analysis to work. A proper data migration strategy should be implemented for a real application.

3.  **Configure Environment Variables:**
    *   The application reads its configuration from environment variables. You can create a `.env` file in the `go_numerology_api` directory for local development. The application will automatically load it.

    *   **`.env` file example:**
        ```
        # .env
        PORT=8080
        DATABASE_URL="postgres://your_username:your_password@localhost:5432/numerology_db?sslmode=disable"
        ```

4.  **Install dependencies and run the server:**
    ```bash
    go mod tidy
    go run ./cmd/server
    ```
    The server will start on the port specified in your `.env` file (e.g., `http://localhost:8080`).

### 2. Deployment to Ubuntu Server

This project includes a `deploy.sh` script to automate deployment.

1.  **Initial Server Setup:**
    *   Ensure your Ubuntu server has `git` and `go` installed.
    *   Set the required environment variables (`DATABASE_URL`, `PORT`) in the shell profile of the deployment user (e.g., `~/.bashrc` or `~/.profile`). This is crucial for security.
        ```bash
        # Add these lines to ~/.profile
        export DATABASE_URL="postgres://tayap:YOUR_REAL_PASSWORD@43.228.85.200/tayap?sslmode=disable"
        export PORT="8080"
        ```
        Then, run `source ~/.profile` to apply the changes.

2.  **Run the script:**
    *   Upload the `deploy.sh` script to your server.
    *   Make it executable: `chmod +x deploy.sh`
    *   Run it: `./deploy.sh`

    The script will clone/update the repo, build the binary, and run the application in the background using `nohup`.

    **For production:** It is highly recommended to manage the application using a `systemd` service instead of `nohup` for better reliability and process management.
