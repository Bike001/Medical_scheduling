# Medical Scheduling Platform

The Medical Scheduling Platform is a comprehensive tool designed for healthcare professionals and patients to manage medical appointments, communications, and profile information efficiently. It features dynamic appointment scheduling, real-time messaging, advanced search capabilities, and robust user profile management.

## Features

- **Dynamic Appointment Scheduling**: Users can set, manage, and view appointments dynamically.
- **Instant Messaging**: Allows for real-time communication between healthcare providers and patients.
- **Profile Management**: Users can update their personal and medical information.
- **Advanced Search Functionality**: Facilitates the search for healthcare providers by name, specialty, or location.
- **Secure Database**: Utilizes PHP and MySQL for secure and efficient data management.

## How to Run

### Prerequisites

- XAMPP (or any similar local server software with PHP and MySQL support)
- Browser with JavaScript enabled

### Setup

1. Clone the repository to your local machine.
2. Place the `medical_scheduling` folder under the `htdocs` directory in your XAMPP installation.
3. Start XAMPP and ensure that Apache and MySQL are running.

### Creating the Database

1. Open your web browser and navigate to `http://localhost/medical_scheduling/create.php`. This will set up the necessary database and tables for the application.

### Using the Application

1. Visit `http://localhost/medical_scheduling/index.html` to access the main page.
2. Register a new account by clicking the 'Sign Up' button and filling in the required information.
3. Once registered, log in via the 'Login' page at `http://localhost/medical_scheduling/login.html`.
4. After logging in, you can:
   - Search for users by name, zip code, or title.
   - Book appointments.
   - Send and receive messages.
   - Update your profile.

## Testing

To test the application:
1. Create accounts with different roles (e.g., Doctor, Medical Supplier).
2. Log in and explore functionalities like sending messages, booking appointments, and updating profiles.

## Additional Information

For detailed steps on specific functionalities or troubleshooting, refer to the accompanying documentation files or visit the [wiki](URL-to-your-wiki-or-docs).

## Contribution

Contributions are welcome! Please fork the repository and submit a pull request with your enhancements.

## License

This project is licensed under the [MIT License](LICENSE.md).
