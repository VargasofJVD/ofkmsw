# Krisah Montessori School - MVP Deployment Guide

## Overview
This is the MVP (Minimum Viable Product) version of the Krisah Montessori School website, focusing on core functionalities:

1. **Newsletter Subscription** - Visitors can subscribe to receive updates
2. **Testimonial System** - Parents can submit testimonials for approval
3. **Dynamic Content Management** - Admin can manage news/events and testimonials
4. **Contact Form** - Simple contact form for inquiries

## Features Removed for MVP
- Student management system
- Complex admission forms
- Gallery management
- Contact message management
- Advanced admin utilities

## Database Setup

1. **Import the main database:**
   ```sql
   mysql -u your_username -p < database/krisah_db.sql
   ```

2. **Run the MVP setup script:**
   ```sql
   mysql -u your_username -p < database/mvp_setup.sql
   ```

3. **Create newsletter subscribers table:**
   ```sql
   mysql -u your_username -p < database/create_newsletter_subscribers.sql
   ```

## File Structure (MVP)
```
ofkmsw/
├── admin/
│   ├── dashboard.php (simplified)
│   ├── index.php (login)
│   ├── logout.php
│   ├── news.php
│   ├── testimonials.php
│   └── update_testimonials.php
├── pages/
│   ├── about.php
│   ├── academics.php
│   ├── contact.php
│   ├── news-detail.php
│   ├── news-events.php
│   ├── testimonial-form.php
│   └── testimonials.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── functions.php
│   └── subscribe.php (NEW)
├── config/
│   ├── config.php
│   └── database.php
├── assets/
├── uploads/
└── index.php
```

## Database Tables (MVP)
- `users` - Admin authentication
- `news_events` - Dynamic content (news/events)
- `testimonials` - Approved testimonials
- `pending_testimonials` - Testimonial requests
- `newsletter_subscribers` - Newsletter subscriptions
- `settings` - Website configuration

## Admin Access
- **URL:** `/admin/`
- **Default credentials:**
  - Username: `admin`
  - Password: `admin123`

## Key Features

### 1. Newsletter Subscription
- Form in footer
- AJAX submission
- Email validation
- Duplicate prevention
- JSON response

### 2. Testimonial System
- Public submission form
- Admin approval workflow
- Image upload support
- Rating system

### 3. Dynamic Content
- News/Events management
- Image uploads
- Rich text content
- Date scheduling

### 4. Contact Form
- Simple contact form
- Email validation
- Database storage

## Deployment Steps

1. **Upload files** to your web server
2. **Configure database** connection in `config/database.php`
3. **Set up database** using the SQL scripts
4. **Configure uploads directory** permissions (755)
5. **Test admin login** and functionality
6. **Test newsletter subscription** form
7. **Test testimonial submission** process

## Security Notes
- Change default admin password
- Secure uploads directory
- Validate all form inputs
- Use HTTPS in production
- Regular database backups

## Future Enhancements
- Student management system
- Advanced admission forms
- Gallery management
- Email notifications
- Advanced analytics
- Mobile app integration

## Support
For technical support or questions about the MVP deployment, contact the development team. 