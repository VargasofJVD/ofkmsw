My go to Git



# Krisah Montessori School Website

A professional, responsive school website for Krisah Montessori School, located in the Western Region of Ghana. The site reflects the values of a Montessori education system—calm, learner-centered, and community-driven.

## Project Structure

### Public Website Pages
- Homepage with hero banner, welcome message, and CTAs
- About Us (mission, headteacher's message, values)
- Academics (Preschool, Lower Primary, Upper Primary)
- Admissions (steps, downloadable forms, FAQs, fee overview)
- News & Events (admin-controlled updates)
- Gallery (image and video sections)
- Testimonials (parent/student feedback)
- Contact Page (form, map, phone/email/social media)

### Admin Dashboard
- Secure login (PHP sessions + password hashing)
- Admin can:
  - Add/edit/delete News & Events
  - Upload and manage Gallery files (images/videos)
  - Update About Us and Contact sections
  - View submitted admission forms
  - Manage student records (name, class, guardian info)

### Technical Implementation
- Modular PHP structure (include header, footer, navbar)
- Tailwind CSS for responsive design
- Optimized for deployment on shared hosting (cPanel)
- Clean, readable, and well-commented code
- Culturally relevant visuals (reflecting Ghana)
- SEO-ready and accessible

## Installation

1. Clone this repository to your local XAMPP htdocs folder
2. Import the database schema from `database/krisah_db.sql`
3. Configure database connection in `config/database.php`
4. Access the website at `http://localhost/ofkms`
5. Access the admin dashboard at `http://localhost/ofkms/admin` (default credentials in README)

## Technologies Used

- PHP 7.4+
- MySQL
- Tailwind CSS
- JavaScript
- HTML5