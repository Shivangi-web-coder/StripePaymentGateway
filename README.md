# Stripe Payment Gateway in Laravel

This project integrates the Stripe payment gateway into a Laravel application. It allows users to subscribe to plans, add coupon codes, and upgrade or downgrade their subscription.

## Features

- **Subscription Management**: Users can subscribe to different plans.
- **Coupon Codes**: Apply coupon codes for discounts on subscriptions.
- **Upgrade/Downgrade**: Users can easily switch between different subscription plans.

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/your-username/your-repository-name.git
    cd your-repository-name
    ```

2. Install dependencies:
    ```bash
    composer install
    npm install
    npm run dev
    ```

3. Set up the environment variables:
    ```bash
    cp .env.example .env
    ```
   - Update the `.env` file with your database, Stripe API keys, and other necessary configurations.

4. Run migrations:
    ```bash
    php artisan migrate
    ```

5. Seed the database (if necessary):
    ```bash
    php artisan db:seed
    ```

6. Serve the application:
    ```bash
    php artisan serve
    ```

## Usage

- **Subscription**: Users can select a plan and subscribe.
- **Coupons**: During checkout, users can apply a coupon code to get a discount.
- **Plan Management**: Users can upgrade or downgrade their subscription plan.

## Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -m 'Add some feature'`).
5. Push to the branch (`git push origin feature-branch`).
6. Open a Pull Request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
