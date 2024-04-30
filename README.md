# HTEAMS

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/Anarki2314/HTeams.git
    ```
2. Navigate to the project directory:
    ```bash
    cd HTeams
    ```
3. Install dependencies using `npm` and `composer`:
    ```bash
    npm install
    composer install
    ```
4. Copy the .env.example file to .env and fill update the necessary configuration:\
   For Linux:
    ```bash
    cp .env.example .env
    ```
    For Windows:
    ```bash
    copy .env.example .env
    ```
5. Generate a new application key:
    ```bash
    php artisan key:generate
    ```
6. Create the database

7. Migrate and seed the database :
    ```bash
    php artisan migrate --seed
    ```
8. Run the server:
    ```bash
    php artisan serve
    npm run dev
    ```
9. Visit http://localhost:8000 in your browser.
